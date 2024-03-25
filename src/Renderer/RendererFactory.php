<?php

namespace App\Renderer;

use App\Service\QueryService;
use App\Service\ValuePresenter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RendererFactory
{
    public function __construct(private readonly QueryService $queryService, private readonly ValuePresenter $presenter)
    {
    }

    public function create(string $format): RendererInterface
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
