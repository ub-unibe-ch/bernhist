<?php

namespace App\Controller\Api;

use App\Entity\DataEntry;
use App\Entity\Location;
use App\Entity\Topic;
use App\Repository\LocationRepository;
use App\Repository\TopicRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/record", defaults={"_format": "json"})
 */
class RecordController extends AbstractApiController
{
    /**
     * @Route("/list/", name="api_record_location")
     */
    public function list(LocationRepository $locationRepo, TopicRepository $topicRepo): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $locationId = $this->request->get('locationId', 0);
        $location = $locationRepo->find($locationId);
        if (!empty($locationId) && empty($location)) {
            throw new NotFoundHttpException();
        }

        $topicId = $this->request->get('topicId', 0);
        $topic = $topicRepo->find($topicId);
        if (!empty($topicId) && empty($topic)) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->createRecordList(true, $location, $topic));
    }

    /**
     * @Route("/list/full/", name="api_record_list_full")
     */
    public function fullList(LocationRepository $locationRepo, TopicRepository $topicRepo): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $locationId = $this->request->get('locationId', 0);
        $location = $locationRepo->find($locationId);
        if (!empty($locationId) && empty($location)) {
            throw new NotFoundHttpException();
        }

        $topicId = $this->request->get('topicId', 0);
        $topic = $topicRepo->find($topicId);
        if (!empty($topicId) && empty($topic)) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->createRecordList(false, $location, $topic));
    }

    /**
     * @Route("/{id}/", name="api_record")
     */
    public function record(DataEntry $record): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json($this->api->toArray($record, false));
    }

    /**
     * @Route("/{id}/full/", name="api_record_full")
     */
    public function fullRecord(DataEntry $record): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json($this->api->toArray($record));
    }

    protected function createRecordList(bool $minimized, ?Location $location = null, ?Topic $topic = null)
    {
        $limit = 500;

        if ($minimized) {
            $limit = 2500;
        }

        $yearFrom = $this->request->get('from', null);
        $yearTo = $this->request->get('to', null);
        $page = (int) $this->request->get('page', 1);
        $offset = ($page - 1) * $limit;

        $recordsFrom = $offset + 1;
        $recordsTo = $recordsFrom + ($limit - 1);

        $totalRecords = $this->query->getDataEntriesTotal($location, $topic, $yearFrom, $yearTo);

        if ($totalRecords < $recordsTo) {
            $recordsTo = $totalRecords;
        }

        $pagesTotal = ceil($totalRecords / $limit);

        $dataEntries = $this->query->getDataEntries($location, $topic, $yearFrom, $yearTo, $offset, $limit);

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
            $records['records'][] = $this->api->toArray($dataEntry, !$minimized);
        }

        return $records;
    }
}
