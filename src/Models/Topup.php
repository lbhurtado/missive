<?php

namespace LBHurtado\Missive\Models;

use Illuminate\Database\Eloquent\Model;
use LBHurtado\Missive\Traits\HasAContact;

class Topup extends Model
{
//    use HasAContact;

    protected $fillable = [
        'amount',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('missive.table_names.topups'));
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
