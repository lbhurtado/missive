<?php

namespace LBHurtado\Missive\Resources;

use LBHurtado\Missive\Models\SMS;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateSMSResource extends JsonResource
{
    public function toArray($request)
    {
        return SMS::first()->toArray();
    }
}
