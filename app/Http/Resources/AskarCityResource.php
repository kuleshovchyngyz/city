<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AskarCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $region = isset($this['region']) ? $this->region->name : '';
        $district = isset($this['district']) ?  " (район: {$this->district->name})" : '';
        return [
            "id" => $this->id,
            "text" => "{$this->name}, {$region}{$district}",
            "lng" => $this->actual == 'old' ? $this->lng : $this->new_lng,
            "lat" => $this->actual == 'old' ? $this->lat : $this->new_lat,
        ];
    }
}
