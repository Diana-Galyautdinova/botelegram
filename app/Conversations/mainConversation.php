<?php

namespace App\Conversations;

use App\messengerUser as database;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class mainConversation extends Conversation
{
    public $response = [];

    public function run () {
        $this->setName();
    }
    private function setName() {
        $question = Question::create("Привет! Как тебя зовут?");

        $this->ask( $question, function ( Answer $answer ) {

            if( $answer->getText () != '' ){
                array_push ($this->response, $answer->getText());
                $this->askWeather ();
            }
        });
    }
    private function askWeather () {
        $question = Question::create("Тебе нравится погода на улице?");
        $question->addButtons( [
            Button::create('Да')->value(1),
            Button::create('Нет')->value(2)
        ]);

        $this->ask($question, function (Answer $answer) {

            if($answer->getValue() == '1') {
                $attachment = new Image('http://povodok.by/files/laughing_dog_2.jpg');
                $message = OutgoingMessage::create('Мне тоже')
                    ->withAttachment($attachment);
                $this->bot->reply($message);

            } else {
                $attachment = new Image('https://vgif.ru/gifs/155/vgif-ru-25820.gif');
                $message = OutgoingMessage::create('Жаль. Тогда посмотри, как собаке весело')
                    ->withAttachment($attachment);
                $this->bot->reply($message);
            }

            array_push($this->response, $answer);
            $this->exit();
        });
    }
    private function exit() {
        $db = new database();
        $db->id_chat = $this->bot->getUser()->getId();
        $db->name = $this->response[0];
        $db->response = $this->response[1];
        $db->save();

        $message = OutgoingMessage::create('До новых встреч!');
        $this->bot->reply($message);

        return true;
    }
}
