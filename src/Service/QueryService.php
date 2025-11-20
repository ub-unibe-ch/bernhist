<?php

namespace App\Service;

use App\Entity\DataEntry;
use App\Entity\Location;
use App\Entity\Topic;
use App\Repository\DataEntryRepository;
use App\Repository\LocationRepository;
use App\Repository\TopicRepository;

class QueryService
{
    public function __construct(private readonly LocationRepository $locationRepo, private readonly TopicRepository $topicRepo, private readonly DataEntryRepository $dataEntryRepo)
    {
    }

    public function getLocationRoot(): ?Location
    {
        $location = $this->locationRepo->findRoot();

        if (!$location instanceof Location) {
            return null;
        }

        $allowedLocations = $this->locationRepo->findWithDataEntries();

        if (!$this->hasAllowedLocation($location, $allowedLocations)) {
            return null;
        }

        $this->removeSubLocations($location, $allowedLocations);

        return $location;
    }

    public function getTopicRoot(?Location $location = null): ?Topic
    {
        $topic = $this->topicRepo->findRoot();

        $allowedTopics = $this->topicRepo->findWithDataEntries($location);

        if (!$this->hasAllowedTopic($topic, $allowedTopics)) {
            return null;
        }

        $this->removeSubTopics($topic, $allowedTopics);

        return $topic;
    }

    /**
     * @return int[]
     */
    public function getYearsFrom(Location $location, Topic $topic): array
    {
        return $this->dataEntryRepo->findYearsFrom($location, $topic);
    }

    /**
     * @return int[]
     */
    public function getYearsTo(Location $location, Topic $topic): array
    {
        return $this->dataEntryRepo->findYearsTo($location, $topic);
    }

    /**
     * @param Location[] $allowedLocations
     */
    protected function hasAllowedLocation(Location $location, array $allowedLocations): bool
    {
        foreach ($allowedLocations as $allowedLocation) {
            if ($location->hasDescendant($allowedLocation)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return DataEntry[]
     */
    public function getDataEntries(?Location $location = null, ?Topic $topic = null, ?int $yearFrom = null, ?int $yearTo = null, ?int $offset = null, ?int $limit = null): array
    {
        return $this->dataEntryRepo->findByLocationTopicYear($location, $topic, $yearFrom, $yearTo, $offset, $limit);
    }

    public function getDataEntriesTotal(?Location $location = null, ?Topic $topic = null, ?int $yearFrom = null, ?int $yearTo = null): int
    {
        return $this->dataEntryRepo->findByLocationTopicYearTotal($location, $topic, $yearFrom, $yearTo);
    }

    /**
     * @param Location[] $allowedLocations
     */
    protected function removeSubLocations(Location $location, array $allowedLocations): void
    {
        if (!$this->hasAllowedLocation($location, $allowedLocations)) {
            $parent = $location->getParent();
            if ($parent instanceof Location) {
                $parent->removeChild($location);
            }
        } else {
            foreach ($location->getChildren() as $child) {
                $this->removeSubLocations($child, $allowedLocations);
            }
        }
    }

    /**
     * @param Topic[] $allowedTopics
     */
    protected function hasAllowedTopic(Topic $topic, array $allowedTopics): bool
    {
        return array_any($allowedTopics, fn(self $allowedTopic): bool => $topic->hasDescendant($allowedTopic));
    }

    /**
     * @param Topic[] $allowedTopics
     */
    protected function removeSubTopics(Topic $topic, array $allowedTopics): void
    {
        if (!$this->hasAllowedTopic($topic, $allowedTopics)) {
            $parent = $topic->getParent();
            if ($parent instanceof Topic) {
                $parent->removeChild($topic);
            }
        } else {
            foreach ($topic->getChildren() as $child) {
                $this->removeSubTopics($child, $allowedTopics);
            }
        }
    }
}
