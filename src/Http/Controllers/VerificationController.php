<?php

declare(strict_types=1);

namespace FondBot\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use FondBot\Bot;
use FondBot\Contracts\Database\Entities\Channel;

class VerificationController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handle(Request $request, Channel $channel, Bot $bot)
    {
        $bot->setRequest($request);
        return $bot->verify($channel);
    }
}
