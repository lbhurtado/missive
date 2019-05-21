<?php

namespace LBHurtado\Missive\Resources;

use LBHurtado\Missive\Facades\Missive;
use LBHurtado\Missive\Models\SMS;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateSMSResource extends JsonResource
{
    public function toArray($request)
    {
        //TODO: fix this, not the real data
        return Missive::getSMS()->toArray();

        return SMS::first()->toArray();
    }
}
