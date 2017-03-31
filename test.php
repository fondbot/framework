<?php


// УДАЛИТЬ var -dumper
// токен в конфигурации файла
use FondBot\Channels\Slack\SlackDriver;
use GuzzleHttp\Client;

require './vendor/autoload.php';
$config = require './config/fondbot.php';

$guzzle = new Client();
$slack  = new SlackDriver($guzzle);

$slack->fill(['token' => $config['channels']['slack']['token']], $_GET);

dump($slack->sendMessage($slack->getUser(), 'Hello Hi!'));