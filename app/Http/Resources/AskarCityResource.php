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
        $region_id = isset($this['region']) ? $this->region->id : null;
        $district_id = isset($this['district']) ? $this->district->id : null;
        $region = isset($this['region']) ? $this->region->name : '';
        $district = isset($this['district']) ?  " (район: {$this->district->name})" : '';

        return [
            "id" => $this->id,
            "order" => $this->order,
            "text" => "{$this->name}, {$region}{$district}",
            "lng" => $this->actual == 'old' ? $this->lng : $this->new_lng,
            "lat" => $this->actual == 'old' ? $this->lat : $this->new_lat,
            "region" => [
                'id'=>$region_id,
                'name'=>$region
                ],
            "district" => [
                'id'=>$district_id,
                'name'=>isset($this['district']) ?  "{$this->district->name}" : ''
                ],
            "group"=>$this->whenLoaded('region',function (){
                return [
                    'id'=>$this->region->group->id,
                    'name'=>$this->region->group->name
                ];
            }),
        ];
    }
}
