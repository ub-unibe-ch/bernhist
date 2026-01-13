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
    public function __construct(private readonly ApiService $apiService, private readonly QueryService $queryService)
    {
    }

    #[Route(path: '/list/', name: 'api_location_list')]
    public function list(): JsonResponse
    {
        $locations = $this->apiService->createList($this->queryService->getLocationRoot());

        return $this->json($locations);
    }

    #[Route(path: '/tree/', name: 'api_location_tree')]
    public function tree(): JsonResponse
    {
        $locations = $this->apiService->createTree($this->queryService->getLocationRoot());

        return $this->json($locations);
    }

    #[Route(path: '/{id}/', name: 'api_location')]
    public function show(Location $location): JsonResponse
    {
        return $this->json($this->apiService->toArray($location));
    }
}
