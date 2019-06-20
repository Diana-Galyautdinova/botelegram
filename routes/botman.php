<?php

use BotMan\BotMan\BotMan;
use App\Http\Controllers\BotManController;
use App\Conversations\mainConversation;

$botman = resolve('botman');

$botman->hears('Привет', BotManController::class.'@startConversation');
