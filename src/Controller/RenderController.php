<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Renderer\RendererFactory;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/render')]
class RenderController extends AbstractController
{
    #[Route(path: '/BERNHIST_{locationId}_{id}_{yearFrom}-{yearTo}.{format}', name: 'render_result')]
    public function renderFile(int $locationId, Topic $topic, int $yearFrom, int $yearTo, string $format, LocationRepository $locationRepo, RendererFactory $rendererFactory): Response
    {
        $location = $locationRepo->find($locationId);
        if (null === $location) {
            throw new NotFoundHttpException();
        }

        $renderer = $rendererFactory->create($format);

        return $renderer->render($location, $topic, $yearFrom, $yearTo);
    }
}
