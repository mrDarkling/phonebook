<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneBookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'number'     => $this->number,
            'name'     => $this->name,
        ];
    }
}
