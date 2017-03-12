<?php
declare(strict_types = 1);

namespace FondBot\Http\Controllers;

use FondBot\Bot;
use FondBot\Database\Entities\Channel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handle(Request $request, Channel $channel, Bot $bot)
    {
        return $bot->process($request, $channel);
    }
}