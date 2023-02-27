<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property string $name
 */
class geo_districts extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'region_id', 'name'];

    public function cities(){
        return $this->hasMany(geo_cities::class, 'district_id', 'id');
    }

}
