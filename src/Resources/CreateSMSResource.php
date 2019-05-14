<?php

namespace LBHurtado\Missive\Resources;

use LBHurtado\Missive\Missive;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateSMSResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => Missive::getSMS()->id,
            'from' => $this->from,
            'to' => $this->to,
            'message' => $this->message,
        ];
    }
}
