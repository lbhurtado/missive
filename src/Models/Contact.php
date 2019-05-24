<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Traits\HasOTP;
use LBHurtado\Missive\Traits\HasAirtime;
use LBHurtado\Missive\Classes\MobileHandle;
use LBHurtado\Missive\Traits\HasSchemalessAttributes;

class Contact extends MobileHandle
{
    use HasAirtime, HasOTP, HasSchemalessAttributes;

    protected $fillable = [
        'mobile',
        'handle',
        'uri',
    ];

    public $casts = [
        'extra_attributes' => 'array',
    ];

    protected function getTableIndex(): string
    {
        return 'contacts';
    }

    public function setURIAttribute($value)
    {
        $this->extra_attributes['uri'] = $value;

        return $this;
    }

    public function getURIAttribute(): string
    {
        return $this->extra_attributes['uri'];
    }
}
