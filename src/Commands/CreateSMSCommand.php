<?php

namespace LBHurtado\Missive\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class CreateSMSCommand implements CommandInterface
{
    public $from;

    public $to;

    public $message;

    public function __construct($from, $to, $message)
    {
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
    }

    public function getProperties():array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'message' => $this->message,
        ];
    }
}
