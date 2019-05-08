<?php

namespace LBHurtado\Missive\Traits;

trait HasAContact
{
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}