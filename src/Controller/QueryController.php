<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Location;
use App\Entity\Topic;
use App\Repository\LocationRepository;
use App\Service\QueryService;
use App\Service\ValuePresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/query')]
class QueryController extends AbstractController
{
    #[Route(path: '/', name: 'query')]
    public function selectLocation(Request $request, QueryService $queryService): Response
    {
        $topicId = $request->get('topicId', 0);

        return $this->render('query/location/index.html.twig', [
            'location' => $queryService->getLocationRoot(),
            'topicId' => $topicId,
        ]);
    }

    #[Route(path: '/location/{id}/', name: 'query_location')]
    public function selectTopic(Location $location, QueryService $queryService): Response
    {
        return $this->render('query/topic/index.html.twig', [
            'location' => $location,
            'topic' => $queryService->getTopicRoot($location),
        ]);
    }

    #[Route(path: '/location/{locationId}/topic/{id}/', name: 'query_topic')]
    public function selectYearRange(int $locationId, Topic $topic, LocationRepository $locationRepo, QueryService $queryService): RedirectResponse
    {
        $location = $locationRepo->find($locationId);
        if (empty($location)) {
            throw new NotFoundHttpException();
        }

        $yearsFrom = $queryService->getYearsFrom($location, $topic);
        $yearsTo = $queryService->getYearsTo($location, $topic);

        if (empty($yearsFrom) || empty($yearsTo)) {
            $this->addFlash('warning', 'Für das zuvor gewählte Thema "'.$topic.'" sind für diesen Ort keine Einträge vorhanden.');

            return $this->redirectToRoute('query_location', ['id' => $location->getId()]);
        }

        $yearFrom = $yearsFrom[0];
        $yearTo = $yearsTo[\count($yearsTo) - 1];

        return $this->redirectToRoute('query_result', ['locationId' => $location->getId(), 'id' => $topic->getId(), 'yearFrom' => $yearFrom, 'yearTo' => $yearTo]);
    }

    #[Route(path: '/location/{locationId}/topic/{id}/{yearFrom}-{yearTo}/', name: 'query_result')]
    public function showResult(int $locationId, Topic $topic, int $yearFrom, int $yearTo, LocationRepository $locationRepo, QueryService $queryService, ValuePresenter $valuePresenter): Response
    {
        $location = $locationRepo->find($locationId);
        if (empty($location)) {
            throw new NotFoundHttpException();
        }

        $yearsFrom = $queryService->getYearsFrom($location, $topic);
        $yearsTo = $queryService->getYearsTo($location, $topic);

        if (empty($yearsFrom) || empty($yearsTo)) {
            throw new NotFoundHttpException();
        }

        if (!\in_array($yearFrom, $yearsFrom) || !\in_array($yearTo, $yearsTo)) {
            throw new NotFoundHttpException();
        }

        if ($yearTo < $yearFrom) {
            $this->addFlash('warning', 'Bitte wählen Sie bei "Jahr bis" einen Wert der gleich oder grösser ist, als "Jahr von".');
            $results = [];
        } else {
            $results = $queryService->getDataEntries($location, $topic, $yearFrom, $yearTo);
        }

        $valuePresenter::setDataEntries($results);

        return $this->render('query/result/index.html.twig', [
            'location' => $location,
            'topic' => $topic,
            'yearFrom' => $yearFrom,
            'yearTo' => $yearTo,
            'yearsFrom' => $yearsFrom,
            'yearsTo' => $yearsTo,
            'results' => $results,
            'valuePresenter' => $valuePresenter,
        ]);
    }

    #[Route(path: '/location/{locationId}/topic/{id}/{yearFrom}-{yearTo}/chartist/', name: 'query_result_chartist')]
    public function chartistResult(int $locationId, Topic $topic, int $yearFrom, int $yearTo, LocationRepository $locationRepo, QueryService $queryService, ValuePresenter $valuePresenter): JsonResponse
    {
        $location = $locationRepo->find($locationId);
        if (empty($location)) {
            throw new NotFoundHttpException();
        }

        $yearsFrom = $queryService->getYearsFrom($location, $topic);
        $yearsTo = $queryService->getYearsTo($location, $topic);

        if (empty($yearsFrom) || empty($yearsTo)) {
            throw new NotFoundHttpException();
        }

        if (!\in_array($yearFrom, $yearsFrom) || !\in_array($yearTo, $yearsTo)) {
            throw new NotFoundHttpException();
        }

        if ($yearTo < $yearFrom) {
            throw new NotFoundHttpException();
        }

        /* @var \App\Entity\DataEntry[] $results */
        $results = $queryService->getDataEntries($location, $topic, $yearFrom, $yearTo);
        $valuePresenter::setDataEntries($results);

        $valuesByYear = [];
        for ($year = $yearFrom; $year <= $yearTo; ++$year) {
            $valuesByYear[$year] = null;
        }

        foreach ($results as $dataEntry) {
            if ($dataEntry->getYearFrom() == $dataEntry->getYearTo()) {
                $valuesByYear[$dataEntry->getYearFrom()] = (float) $dataEntry->getValue();
            } else {
                $years = $dataEntry->getYearTo() - $dataEntry->getYearFrom() + 1;
                $value = $dataEntry->getValue() / $years;
                $valuesByYear[$dataEntry->getYearFrom()] = (float) $value;
                $valuesByYear[$dataEntry->getYearTo()] = (float) $value;
            }
        }

        $chartistData = [];

        foreach ($valuesByYear as $year => $value) {
            $chartistData['labels'][] = $year;
            $chartistData['series'][0][] = $value;
        }

        return $this->json($chartistData);
    }
}
