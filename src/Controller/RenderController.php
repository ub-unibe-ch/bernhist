<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Topic;
use App\Renderer\RendererFactory;
use App\Repository\DataEntryRepository;
use App\Repository\LocationRepository;
use App\Repository\TopicRepository;
use App\Service\QueryService;
use App\Service\ValuePresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/render")
 */
class RenderController extends AbstractController
{
    /**
     * @Route("/BERNHIST_{locationId}_{id}_{yearFrom}-{yearTo}.{format}", name="render_result")
     */
    public function renderFile(int $locationId, Topic $topic, int $yearFrom, int $yearTo, $format, LocationRepository $locationRepo, RendererFactory $rendererFactory)
    {
        $location = $locationRepo->find($locationId);
        if(empty($location)){
            throw new NotFoundHttpException();
        }

        $renderer = $rendererFactory->create($format);
        return $renderer->render($location, $topic, $yearFrom, $yearTo);
    }
}
