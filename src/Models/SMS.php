<?php

namespace LBHurtado\Missive\Models;

use Illuminate\Database\Eloquent\Model;
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
        return $this->belongsTo(config('missive.classes.models.contact', Contact::class), 'from', 'mobile');
    }

    public function destination(): BelongsTo
    {
    	return $this->belongsTo(config('missive.classes.models.relay', Relay::class), 'to', 'mobile');
    }
}
