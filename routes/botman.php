<?php

use BotMan\BotMan\BotMan;
use App\Http\Controllers\BotManController;
use App\Conversations\mainConversation;

$botman = resolve('botman');

$botman->hears('hi', function ( $bot ) { $bot->reply('Hello!'); } );

$botman->hears('di', function ( $bot ) { $bot->reply('na'); } );

$botman->hears('Start conversation', BotManController::class.'@startConversation');


$botman->hears('/start', function ( BotMan $bot ) { $bot->startConversation(new App\Conversations\mainConversation()); } );

//$botman->hears('/start', BotManController::class);
