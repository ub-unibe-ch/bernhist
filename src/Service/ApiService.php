<?php

namespace App\Service;

use App\Entity\DataEntry;
use App\Entity\Location;
use App\Entity\Topic;

class ApiService
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(DataEntry|Topic|Location|null $entry, bool $listMode = true, bool $treeInfo = true): array
    {
        if (null === $entry) {
            return [];
        }

        if ($entry instanceof DataEntry) {
            if ($listMode) {
                return [
                    'id' => $entry->getId(),
                    'location' => $this->toArray($entry->getLocation(), true, false),
                    'topic' => $this->toArray($entry->getTopic(), true, false),
                    'year_from' => $entry->getYearFrom(),
                    'year_to' => $entry->getYearTo(),
                    'value' => (float) $entry->getValue(),
                    'unit' => $entry->getTopic()?->getType()?->getName(),
                ];
            }

            return [
                'id' => $entry->getId(),
                'location' => $entry->getLocation()?->getId(),
                'topic' => $entry->getTopic()?->getId(),
                'year_from' => $entry->getYearFrom(),
                'year_to' => $entry->getYearTo(),
                'value' => (float) $entry->getValue(),
                'unit' => $entry->getTopic()?->getType()?->getName(),
            ];
        }

        $field = 'location';
        $type = 'type';

        if ($entry instanceof Topic) {
            $field = 'topic';
            $type = 'unit';
        }

        if (!$treeInfo) {
            return [
                'id' => $entry->getId(),
                'name' => $entry->getName(),
                $type => $entry->getType()?->getName(),
                'has_records' => true,
            ];
        }
        $parent = $entry->getParent()?->getId();

        if ($entry instanceof Location && null !== $entry->getIsStartNode() && $entry->getIsStartNode()) {
            $parent = null;
        }

        $result = [
            $field => [
                'id' => $entry->getId(),
                'name' => $entry->getName(),
                $type => $entry->getType()?->getName(),
                'has_records' => true,
            ],
        ];

        if ($listMode) {
            $children = [];
            foreach ($entry->getChildren() as $child) {
                $children[] = $child->getId();
            }

            $result['tree'] = [
                'parent' => $parent,
                'children' => $children,
            ];
        } else {
            $result['tree'] = [
                'parent' => $parent,
            ];
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $result
     *
     * @return array<string, mixed>
     */
    public function createList(Topic|Location|null $node, &$result = []): array
    {
        if (null === $node) {
            return [];
        }

        $result[] = $this->toArray($node);

        foreach ($node->getChildren() as $child) {
            $this->createList($child, $result);
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function createTree(Topic|Location|null $node): array
    {
        if (null === $node) {
            return [];
        }

        $children = [];
        foreach ($node->getChildren() as $child) {
            $children[] = $this->createTree($child);
        }

        $result = $this->toArray($node, false);
        $result['tree']['children'] = $children;

        return $result;
    }
}
