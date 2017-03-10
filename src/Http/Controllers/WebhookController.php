<?php
declare(strict_types=1);

namespace FondBot\Http\Controllers;

use FondBot\Bot;
use FondBot\Database\Entities\Channel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handle(Channel $channel, Bot $bot)
    {
        $bot->process($channel);

        return 'OK';
    }

}