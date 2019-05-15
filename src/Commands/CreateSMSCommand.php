<?php

namespace LBHurtado\Missive\Commands;

use Illuminate\Http\Request;
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

//    protected $request;
//
//    public function __construct(Request $request)
//    {
//        $this->request = $request;
//        foreach ($request as $property => $value) {
//            $this->{$property} = $value;
//        }
//    }

    public function getProperties():array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'message' => $this->message,
        ];
//        $attributes = $this->request->only(config('tactician.fields'));
//
//        \Log::info($attributes);
//        return $attributes;
    }
}
