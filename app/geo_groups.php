<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property int $posi
 */
class geo_groups extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name', 'posi'];

}
