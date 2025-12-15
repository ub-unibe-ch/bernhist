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
    public function __construct(private readonly ApiService $apiService, private readonly QueryService $queryService)
    {
    }

    #[Route(path: '/list/', name: 'api_topic_list')]
    public function list(): JsonResponse
    {
        $topics = $this->apiService->createList($this->queryService->getTopicRoot());

        return $this->json($topics);
    }

    #[Route(path: '/tree/', name: 'api_topic_tree')]
    public function tree(): JsonResponse
    {
        $topics = $this->apiService->createTree($this->queryService->getTopicRoot());

        return $this->json($topics);
    }

    #[Route(path: '/{id}/', name: 'api_topic')]
    public function show(Topic $topic): JsonResponse
    {
        return $this->json($this->apiService->toArray($topic));
    }
}
