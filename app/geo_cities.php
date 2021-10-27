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
    /**
     * @var array
     */
    protected $fillable = ['region_id', 'district_id', 'name', 'lat', 'lng'];

}
