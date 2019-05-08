<?php

namespace LBHurtado\Missive\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface Contactable
{
	function contact(): BelongsTo;
}