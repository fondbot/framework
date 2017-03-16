<?php

declare(strict_types=1);

namespace FondBot\Http\Controllers;

use FondBot\Bot;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use FondBot\Contracts\Database\Entities\Channel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VerificationController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handle(Request $request, Channel $channel, Bot $bot)
    {
        $bot->setRequest($request);

        return $bot->verify($channel);
    }
}
