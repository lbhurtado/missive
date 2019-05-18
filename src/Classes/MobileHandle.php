<?php

namespace LBHurtado\Missive\Classes;

use Illuminate\Database\Eloquent\Model;

abstract class MobileHandle extends Model
{   
    protected $fillable = [
    	'mobile',
    	'handle',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config("missive.table_names.{$this->getTableIndex()}"));
    }

    abstract protected function getTableIndex(): string;
}
