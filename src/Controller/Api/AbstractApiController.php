<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use App\Service\ApiService;
use App\Service\QueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class AbstractApiController extends AbstractController
{
    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @var ApiService
     */
    protected $api;

    /**
     * @var QueryService
     */
    protected $query;

    public function __construct(RequestStack $requestStack, ApiService $apiService, QueryService $queryService)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->api = $apiService;
        $this->query = $queryService;
    }
}
