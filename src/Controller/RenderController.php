<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Renderer\RendererFactory;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/render')]
class RenderController extends AbstractController
{
    public function __construct(private readonly LocationRepository $locationRepo, private readonly RendererFactory $rendererFactory)
    {
    }

    #[Route(path: '/BERNHIST_{locationId}_{id}_{yearFrom}-{yearTo}.{format}', name: 'render_result')]
    public function renderFile(int $locationId, Topic $topic, int $yearFrom, int $yearTo, string $format): Response
    {
        $location = $this->locationRepo->find($locationId);
        if (null === $location) {
            throw new NotFoundHttpException();
        }

        $renderer = $this->rendererFactory->create($format);

        return $renderer->render($location, $topic, $yearFrom, $yearTo);
    }
}
