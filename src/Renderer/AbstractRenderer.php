<?php

namespace App\Renderer;

use App\Entity\DataEntry;
use App\Entity\Location;
use App\Entity\Topic;
use App\Service\QueryService;
use App\Service\ValuePresenter;

abstract class AbstractRenderer implements RendererInterface
{
    /**
     * @var ValuePresenter
     */
    protected $presenter;

    public function __construct(private readonly QueryService $queryService, ValuePresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @return DataEntry[]
     */
    protected function getData(Location $location, Topic $topic, ?int $yearFrom, ?int $yearTo): array
    {
        $entries = $this->queryService->getDataEntries($location, $topic, $yearFrom, $yearTo);
        $this->presenter::setDataEntries($entries);

        return $entries;
    }
}
