<?php

namespace LBHurtado\Missive\Traits;

trait HasAContact
{
    public function contact(): BelongsTo
    {
        return $this->belongsTo(get_class(app('missive.contact')));
    }
}