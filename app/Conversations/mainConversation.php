<?php

namespace App\Conversations;

use App\messengerUser as database;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;

class mainConversation extends conversation
{

    protected $response = [];

    public function run () {

        $this->setName();
    }

    private function setName() {

        //$question = BotManQuestion::create("������! ��� ���� �����?");
        $this->ask( "������! ��� ���� �����?", function ( BotManAnswer $answer ) {

            if( $answer->getText () !== '' ){

                array_push ($this->response, $answer->getText());
                $this->askWeather ();
            }
        });
    }

    private function askWeather () {

        $question = BotManQuestion::create("���� �������� ������ �� �����?");
        $question->addButtons( [
            Button::create('��')->value(1),
            Button::create('���')->value(2)
        ]);
        $this->ask($question, function (BotManAnswer $answer) {
            // ����� ����� ������� ����� ���� �������, �� ��� ��� �� ����� ������
            array_push ($this->response, $answer);
            $this->exit();
        });
    }

    private function exit() {

        $db = new database();
        $db->id_chat = $this->bot->getUser()->getId();
        $db->name = $this->response[0];
        $db->response = $this->response[1];
        $db->save();

        $attachment = new Image('/Users/adel/Documents/ilVEWt0TU3w.jpg');
        $message = OutgoingMessage::create('�� ����� ������!')
            ->withAttachment($attachment);
        $this->bot->reply($message);

        return true;
    }
}
