<?php

namespace LBHurtado\Missive\Models;

use Illuminate\Database\Eloquent\Model;

class Airtime extends Model
{
    protected $fillable = [
        'key',
        'credits',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('missive.table_names.airtimes'));
    }
}