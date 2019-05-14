<?php

namespace LBHurtado\Missive\Resources;

use LBHurtado\Missive\Facades\Missive;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateSMSResource extends JsonResource
{
    public function toArray($request)
    {
        return Missive::getSMS()->toArray();
    }
}
