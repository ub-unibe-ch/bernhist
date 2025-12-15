<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Topic;
use App\Repository\LocationRepository;
use App\Service\QueryService;
use App\Service\ValuePresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/query')]
class QueryController extends AbstractController
{
    public function __construct(private readonly \App\Service\QueryService $queryService, private readonly \App\Repository\LocationRepository $locationRepo, private readonly \App\Service\ValuePresenter $valuePresenter)
    {
    }

    #[Route(path: '/', name: 'query')]
    public function selectLocation(Request $request): Response
    {
        $topicId = $request->query->get('topicId', '0');

        return $this->render('query/location/index.html.twig', [
            'location' => $this->queryService->getLocationRoot(),
            'topicId' => $topicId,
        ]);
    }

    #[Route(path: '/location/{id}/', name: 'query_location')]
    public function selectTopic(Location $location): Response
    {
        return $this->render('query/topic/index.html.twig', [
            'location' => $location,
            'topic' => $this->queryService->getTopicRoot($location),
        ]);
    }

    #[Route(path: '/location/{locationId}/topic/{id}/', name: 'query_topic')]
    public function selectYearRange(int $locationId, Topic $topic): RedirectResponse
    {
        $location = $this->locationRepo->find($locationId);
        if (null === $location) {
            throw new NotFoundHttpException();
        }

        $yearsFrom = $this->queryService->getYearsFrom($location, $topic);
        $yearsTo = $this->queryService->getYearsTo($location, $topic);

        if ([] === $yearsFrom || [] === $yearsTo) {
            $this->addFlash('warning', 'Für das zuvor gewählte Thema "'.$topic.'" sind für diesen Ort keine Einträge vorhanden.');

            return $this->redirectToRoute('query_location', ['id' => $location->getId()]);
        }

        $yearFrom = $yearsFrom[0];
        $yearTo = $yearsTo[\count($yearsTo) - 1];

        return $this->redirectToRoute('query_result', ['locationId' => $location->getId(), 'id' => $topic->getId(), 'yearFrom' => $yearFrom, 'yearTo' => $yearTo]);
    }

    #[Route(path: '/location/{locationId}/topic/{id}/{yearFrom}-{yearTo}/', name: 'query_result')]
    public function showResult(int $locationId, Topic $topic, int $yearFrom, int $yearTo): Response
    {
        $location = $this->locationRepo->find($locationId);
        if (null === $location) {
            throw new NotFoundHttpException();
        }

        $yearsFrom = $this->queryService->getYearsFrom($location, $topic);
        $yearsTo = $this->queryService->getYearsTo($location, $topic);

        if ([] === $yearsFrom || [] === $yearsTo) {
            throw new NotFoundHttpException();
        }

        if (!\in_array($yearFrom, $yearsFrom, true) || !\in_array($yearTo, $yearsTo, true)) {
            throw new NotFoundHttpException();
        }

        if ($yearTo < $yearFrom) {
            $this->addFlash('warning', 'Bitte wählen Sie bei "Jahr bis" einen Wert der gleich oder grösser ist, als "Jahr von".');
            $results = [];
        } else {
            $results = $this->queryService->getDataEntries($location, $topic, $yearFrom, $yearTo);
        }

        $this->valuePresenter::setDataEntries($results);

        return $this->render('query/result/index.html.twig', [
            'location' => $location,
            'topic' => $topic,
            'yearFrom' => $yearFrom,
            'yearTo' => $yearTo,
            'yearsFrom' => $yearsFrom,
            'yearsTo' => $yearsTo,
            'results' => $results,
            'valuePresenter' => $this->valuePresenter,
        ]);
    }

    #[Route(path: '/location/{locationId}/topic/{id}/{yearFrom}-{yearTo}/chartist/', name: 'query_result_chartist')]
    public function chartistResult(int $locationId, Topic $topic, int $yearFrom, int $yearTo): JsonResponse
    {
        $location = $this->locationRepo->find($locationId);
        if (null === $location) {
            throw new NotFoundHttpException();
        }

        $yearsFrom = $this->queryService->getYearsFrom($location, $topic);
        $yearsTo = $this->queryService->getYearsTo($location, $topic);

        if ([] === $yearsFrom || [] === $yearsTo) {
            throw new NotFoundHttpException();
        }

        if (!\in_array($yearFrom, $yearsFrom, true) || !\in_array($yearTo, $yearsTo, true)) {
            throw new NotFoundHttpException();
        }

        if ($yearTo < $yearFrom) {
            throw new NotFoundHttpException();
        }

        /* @var \App\Entity\DataEntry[] $results */
        $results = $this->queryService->getDataEntries($location, $topic, $yearFrom, $yearTo);
        $this->valuePresenter::setDataEntries($results);

        $valuesByYear = [];
        for ($year = $yearFrom; $year <= $yearTo; ++$year) {
            $valuesByYear[$year] = null;
        }

        foreach ($results as $dataEntry) {
            if ($dataEntry->getYearFrom() === $dataEntry->getYearTo()) {
                $valuesByYear[$dataEntry->getYearFrom()] = (float) $dataEntry->getValue();
            } else {
                $years = ($dataEntry->getYearTo() ?? 0) - ($dataEntry->getYearFrom() ?? 0) + 1;
                $value = (int) $dataEntry->getValue() / $years;
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
