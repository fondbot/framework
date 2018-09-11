<?php

declare(strict_types=1);

namespace FondBot\Foundation\Middleware;

use Closure;
use FondBot\FondBot;
use Illuminate\Http\Request;
use FondBot\Channels\Channel;
use FondBot\Contracts\Channels\Manager;

class InitializeKernel
{
    private $kernel;
    private $channelManager;

    public function __construct(FondBot $kernel, Manager $channelManager)
    {
        $this->kernel = $kernel;
        $this->channelManager = $channelManager;
    }

    public function handle(Request $request, Closure $next)
    {
        $channel = $this->resolveChannel($request->route('channel'));

        if ($channel->getSecret() !== null && $request->route('secret') !== $channel->getSecret()) {
            abort(403);
        }

        $this->kernel->initialize($channel);

        return $next($request);
    }

    private function resolveChannel($value): Channel
    {
        if (is_string($value)) {
            $value = $this->channelManager->create($value);
        }

        return $value;
    }
}
