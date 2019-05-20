<?php

namespace LBHurtado\Missive\Classes;

use Illuminate\Database\Eloquent\Model;

abstract class SMSAbstract extends Model
{
    protected $fillable = [
        'from',
        'to',
        'message',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('missive.table_names.smss'));
    }

    public function getMessage(): string
    {
        return trim($this->message);
    }
}
