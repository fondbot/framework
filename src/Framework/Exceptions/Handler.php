<?php

declare(strict_types=1);

namespace FondBot\Framework\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;

class Handler extends BaseHandler
{
    /** {@inheritdoc} */
    protected function renderHttpException(HttpException $e): Response
    {
        return $this->convertExceptionToResponse($e);
    }
}
