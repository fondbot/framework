<?php

declare(strict_types=1);

namespace FondBot\Framework\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;

class Handler extends BaseHandler
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $e
     * @return \Illuminate\Http\Response|string|Response
     */
    public function render($request, Exception $e)
    {
        // Catch all exceptions if route is webhook
        if (!$e instanceof HttpException && $request->routeIs('fondbot.webhook')) {
            return 'Something went wrong.';
        }

        return parent::render($request, $e);
    }

    /** {@inheritdoc} */
    protected function renderHttpException(HttpException $e): Response
    {
        return $this->convertExceptionToResponse($e);
    }
}
