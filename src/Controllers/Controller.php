<?php

declare(strict_types=1);

namespace FondBot\Controllers;

use FondBot\Foundation\Kernel;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Controller
{
    public function run(): ResponseInterface
    {
        return new HtmlResponse('FondBot v'.Kernel::VERSION);
    }
}
