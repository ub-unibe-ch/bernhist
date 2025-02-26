<?php

namespace App\Controller\Api;

use App\Entity\Topic;
use App\Service\ApiService;
use App\Service\QueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/topic', defaults: ['_format' => 'json'])]
class TopicController extends AbstractController
{
    #[Route(path: '/list/', name: 'api_topic_list')]
    public function list(ApiService $apiService, QueryService $queryService): JsonResponse
    {
        $topics = $apiService->createList($queryService->getTopicRoot());

        return $this->json($topics);
    }

    #[Route(path: '/tree/', name: 'api_topic_tree')]
    public function tree(ApiService $apiService, QueryService $queryService): JsonResponse
    {
        $topics = $apiService->createTree($queryService->getTopicRoot());

        return $this->json($topics);
    }

    #[Route(path: '/{id}/', name: 'api_topic')]
    public function show(Topic $topic, ApiService $apiService): JsonResponse
    {
        return $this->json($apiService->toArray($topic));
    }
}
