<?php

namespace App\Controller\Api;

use App\Entity\Location;
use App\Service\ApiService;
use App\Service\QueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/location', defaults: ['_format' => 'json'])]
class LocationController extends AbstractController
{
    #[Route(path: '/list/', name: 'api_location_list')]
    public function list(ApiService $apiService, QueryService $queryService): JsonResponse
    {
        $locations = $apiService->createList($queryService->getLocationRoot());

        return $this->json($locations);
    }

    #[Route(path: '/tree/', name: 'api_location_tree')]
    public function tree(ApiService $apiService, QueryService $queryService): JsonResponse
    {
        $locations = $apiService->createTree($queryService->getLocationRoot());

        return $this->json($locations);
    }

    #[Route(path: '/{id}/', name: 'api_location')]
    public function show(Location $location, ApiService $apiService): JsonResponse
    {
        return $this->json($apiService->toArray($location));
    }
}
