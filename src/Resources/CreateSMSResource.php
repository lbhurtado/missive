<?php
/**
 * Created by PhpStorm.
 * User: aph
 * Date: 2019-05-09
 * Time: 00:34
 */

namespace LBHurtado\Missive\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateSMSResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'message' => $this->message,
        ];
    }
}
