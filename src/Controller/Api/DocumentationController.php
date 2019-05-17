<?php

namespace App\Controller\Api;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Service\QueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class DocumentationController extends AbstractController
{
    /**
     * @Route("/", name="api_documentation")
     */
    public function documentation(QueryService $queryService)
    {
        return $this->render('swagger.html.twig');
    }
}
