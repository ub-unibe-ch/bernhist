<?php

namespace App\Renderer;


use App\Entity\Location;
use App\Entity\Topic;
use App\Service\QueryService;
use App\Service\ValuePresenter;

abstract class AbstractRenderer implements RendererInterface
{
    /**
     * @var QueryService
     */
    private $queryService;

    /**
     * @var ValuePresenter
     */
    protected $presenter;

    public function __construct(QueryService $queryService, ValuePresenter $presenter)
    {
        $this->queryService = $queryService;
        $this->presenter = $presenter;
    }

    /**
     * @param Location $location
     * @param Topic $topic
     * @param int|null $yearFrom
     * @param int|null $yearTo
     * @return \App\Entity\DataEntry[]
     */
    protected function getData(Location $location, Topic $topic, ?int $yearFrom, ?int $yearTo): array
    {
        $entries = $this->queryService->getDataEntries($location, $topic, $yearFrom, $yearTo);
        $this->presenter::setDataEntries($entries);

        return $entries;
    }
}