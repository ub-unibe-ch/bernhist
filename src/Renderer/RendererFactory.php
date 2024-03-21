<?php

namespace App\Renderer;

use App\Service\QueryService;
use App\Service\ValuePresenter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RendererFactory
{
    private $queryService;

    private $presenter;

    public function __construct(QueryService $queryService, ValuePresenter $presenter)
    {
        $this->queryService = $queryService;
        $this->presenter = $presenter;
    }

    public function create($format): RendererInterface
    {
        if ('xlsx' == $format) {
            return new ExcelRenderer($this->queryService, $this->presenter);
        }
        if ('pdf' == $format) {
            return new PdfRenderer($this->queryService, $this->presenter);
        }

        throw new NotFoundHttpException();
    }
}
