<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Topic;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/topic", defaults={"_format": "json"})
 */
class TopicController extends AbstractApiController
{
    /**
     * @Route("/list/", name="api_topic_list")
     */
    public function list(): JsonResponse
    {
        $topics = $this->api->createList($this->query->getTopicRoot());

        return $this->json($topics);
    }

    /**
     * @Route("/tree/", name="api_topic_tree")
     */
    public function tree(): JsonResponse
    {
        $topics = $this->api->createTree($this->query->getTopicRoot());

        return $this->json($topics);
    }

    /**
     * @Route("/{id}/", name="api_topic")
     */
    public function show(Topic $topic): JsonResponse
    {
        return $this->json($this->api->toArray($topic));
    }
}
