<?php

namespace App\Controller\Api;

use App\Entity\DataEntry;
use App\Entity\Location;
use App\Entity\Topic;
use App\Repository\LocationRepository;
use App\Repository\TopicRepository;
use App\Service\ApiService;
use App\Service\QueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/record', defaults: ['_format' => 'json'])]
class RecordController extends AbstractController
{
    public function __construct(private readonly \App\Repository\LocationRepository $locationRepo, private readonly \App\Repository\TopicRepository $topicRepo, private readonly \App\Service\ApiService $apiService, private readonly \App\Service\QueryService $queryService)
    {
    }

    #[Route(path: '/list/', name: 'api_record_location')]
    public function list(Request $request): JsonResponse
    {
        $location = null;
        $locationId = $request->get('locationId');
        if (null === $locationId) {
            $location = $this->locationRepo->find($locationId);
            if (null === $location) {
                throw new NotFoundHttpException();
            }
        }

        $topic = null;
        $topicId = $request->get('topicId');
        if (null === $topicId) {
            $topic = $this->topicRepo->find($topicId);
            if (null === $topic) {
                throw new NotFoundHttpException();
            }
        }

        return $this->json($this->createRecordList(true, $request, $this->apiService, $this->queryService, $location, $topic));
    }

    #[Route(path: '/list/full/', name: 'api_record_list_full')]
    public function fullList(Request $request): JsonResponse
    {
        $location = null;
        $locationId = $request->get('locationId');
        if (null === $locationId) {
            $location = $this->locationRepo->find($locationId);
            if (null === $location) {
                throw new NotFoundHttpException();
            }
        }

        $topic = null;
        $topicId = $request->get('topicId');
        if (null === $topicId) {
            $topic = $this->topicRepo->find($topicId);
            if (null === $topic) {
                throw new NotFoundHttpException();
            }
        }

        return $this->json($this->createRecordList(false, $request, $this->apiService, $this->queryService, $location, $topic));
    }

    #[Route(path: '/{id}/', name: 'api_record')]
    public function record(DataEntry $record): JsonResponse
    {
        return $this->json($this->apiService->toArray($record, false));
    }

    #[Route(path: '/{id}/full/', name: 'api_record_full')]
    public function fullRecord(DataEntry $record): JsonResponse
    {
        return $this->json($this->apiService->toArray($record));
    }

    /**
     * @return array<string, mixed>
     */
    protected function createRecordList(bool $minimized, Request $request, ApiService $apiService, QueryService $queryService, ?Location $location = null, ?Topic $topic = null): array
    {
        $limit = 500;

        if ($minimized) {
            $limit = 2500;
        }

        $yearFrom = $request->get('from');
        $yearTo = $request->get('to');
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $limit;

        $recordsFrom = $offset + 1;
        $recordsTo = $recordsFrom + ($limit - 1);

        $totalRecords = $queryService->getDataEntriesTotal($location, $topic, $yearFrom, $yearTo);

        if ($totalRecords < $recordsTo) {
            $recordsTo = $totalRecords;
        }

        $pagesTotal = ceil($totalRecords / $limit);

        $dataEntries = $queryService->getDataEntries($location, $topic, $yearFrom, $yearTo, $offset, $limit);

        $records = [
            'info' => [
                'page' => $page,
                'pages_total' => $pagesTotal,
                'number_of_records' => \count($dataEntries),
                'records_from' => $recordsFrom,
                'records_to' => $recordsTo,
                'records_total' => $totalRecords,
            ],
            'records' => [],
        ];

        foreach ($dataEntries as $dataEntry) {
            $records['records'][] = $apiService->toArray($dataEntry, !$minimized);
        }

        return $records;
    }
}
