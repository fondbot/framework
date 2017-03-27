<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Http\Controllers;

use FondBot\BotFactory;
use FondBot\Channels\Channel;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WebhookController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handle(BotFactory $factory, Request $request, Channel $channel)
    {
        $bot = $factory->create(
            resolve(Container::class),
            $channel,
            $request->isJson() ? $request->json()->all() : $request->all(),
            $request->headers->all()
        );

        return $bot->process();
    }
}
