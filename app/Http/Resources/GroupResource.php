<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "lng" => $this->actual == 'old' ? $this->lng : $this->new_lng,
            "lat" => $this->actual == 'old' ? $this->lat : $this->new_lat,

            "region"=>$this->whenLoaded('region'),

        ];
    }
}
