<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property int $group_id
 */
class geo_regions extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name', 'group_id'];

    public function cities()
    {
        return $this->hasMany(geo_cities::class, 'region_id', 'id');
    }

}
