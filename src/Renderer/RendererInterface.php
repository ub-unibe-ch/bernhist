<?php

namespace App\Renderer;


use App\Entity\Location;
use App\Entity\Topic;
use Symfony\Component\HttpFoundation\Response;

Interface RendererInterface
{
    public function render(Location $location, Topic $topic, ?int $yearFrom = null, ?int $yearTo = null): Response;
}