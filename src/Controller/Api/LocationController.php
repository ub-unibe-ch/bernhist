<?php

namespace App\Controller\Api;

use App\Entity\Location;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/location", defaults={"_format": "json"})
 */
class LocationController extends AbstractApiController
{
    /**
     * @Route("/list/", name="api_location_list")
     */
    public function list(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $locations = $this->api->createList($this->query->getLocationRoot());

        return $this->json($locations);
    }

    /**
     * @Route("/tree/", name="api_location_tree")
     */
    public function tree(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $locations = $this->api->createTree($this->query->getLocationRoot());

        return $this->json($locations);
    }

    /**
     * @Route("/{id}/", name="api_location")
     */
    public function show(Location $location): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json($this->api->toArray($location));
    }
}
