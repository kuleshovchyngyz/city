<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $region_id
 * @property int $district_id
 * @property string $name
 * @property string $lat
 * @property string $lng
 */
class geo_cities extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;
    /**
     * @var array
     */
    protected $with = ['region'];
    protected $fillable = ['region_id', 'district_id', 'name', 'lat', 'lng','timestamp','actual','multiple'];
    public function region(){
        return $this->belongsTo(geo_regions::class,'region_id','id');
    }
    public function district(){
        return $this->belongsTo(geo_districts::class,'district_id','id');
    }
    public function group()
    {
        return $this->belongsToThrough(geo_groups::class, geo_regions::class,  'region_id',
            'geo_',[geo_regions::class =>'group_id'  ]);
    }
}
