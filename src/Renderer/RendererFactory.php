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
        if($format == 'xlsx')
        {
            return new ExcelRenderer($this->queryService, $this->presenter);
        }elseif($format == 'pdf'){
            return new PdfRenderer($this->queryService, $this->presenter);
        }

        throw new NotFoundHttpException();
    }
}