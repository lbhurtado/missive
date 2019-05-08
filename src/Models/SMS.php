<?php

namespace LBHurtado\Missive\Models;

use Illuminate\Database\Eloquent\Model;
use LBHurtado\Missive\Traits\HasAContact;
use LBHurtado\Missive\Contracts\Contactable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SMS extends Model
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

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'from', 'mobile');
    }

    public function destination(): BelongsTo
    {
    	return $this->belongsTo(Relay::class, 'to', 'mobile');
    }
}
