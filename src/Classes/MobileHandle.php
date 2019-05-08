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

    public function getMobile()
    {
    	return $this->mobile;
    }

    public function setMobile($mobile)
    {
    	$this->mobile = $mobile;

    	return $this;
    }

    public function getHandle()
    {
    	return $this->handle;
    }

    public function setHandle($handle)
    {
    	$this->handle = $handle;

    	return $this;
    }

    abstract protected function getTableIndex(): string;
}
