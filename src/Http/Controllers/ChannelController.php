<?php
declare(strict_types=1);

namespace FondBot\Http\Controllers;

use FondBot\Database\Entities\Channel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class ChannelController extends Controller
{

    use ValidatesRequests;

    public function webhook(Channel $channel)
    {
        dd($channel);
    }

}