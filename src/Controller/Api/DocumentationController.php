<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class DocumentationController extends AbstractController
{
    #[Route(path: '/', name: 'api_documentation')]
    public function documentation(): Response
    {
        return $this->render('swagger.html.twig');
    }
}
