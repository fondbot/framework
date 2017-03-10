<?php
declare(strict_types=1);

namespace FondBot\Http\Controllers;

use FondBot\Database\Entities\Channel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{

    use ValidatesRequests;

    public function handle(Channel $channel)
    {
        dd($channel);
    }

}