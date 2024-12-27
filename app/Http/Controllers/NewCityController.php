<?php

namespace App\Http\Controllers;

use App\geo_cities;
use App\Http\Resources\AskarCityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewCityController extends Controller
{
    public function searchAskar(Request $request)
    {
        // Log the incoming request for debugging


        // Get the city query
        $cityQuery = $request->get('city');

        // Ensure at least two characters are entered for the city
        if (mb_strlen($cityQuery) > 0 && mb_strlen($cityQuery) < 4) {

            $results = geo_cities::when($cityQuery, function ($q) use ($cityQuery) {
                $q->whereNotNull('order')
                    ->where('name', 'like', '%' . $cityQuery . '%');
            })
                ->with(['region', 'district'])
                ->when($request->has('city_id'), function ($query) {
                    $query->where('id', request('city_id'));
                })
                ->when($request->has('region'), function ($query) {
                    $query->whereHas('region', function ($query) {
                        $query->where('name', 'like', '%' . request('region') . '%');
                    });
                })
                ->when($request->has('district'), function ($query) {
                    $query->whereHas('district', function ($query) {
                        $query->where('name', 'like', '%' . request('district') . '%');
                    });
                })
                ->when($request->has('region_id'), function ($query) {
                    $query->whereHas('region', function ($query) {
                        $query->where('id', request('region_id'));
                    });
                })
                ->when($request->has('district_id'), function ($query) {
                    $query->whereHas('district', function ($query) {
                        $query->where('id', request('district_id'));
                    });
                })
                ->orderBy('order', 'desc') // Sort by the `order` column in descending order
                ->get();
        } else {

            $results = geo_cities::when($cityQuery, function ($q) use ($cityQuery) {
                $q->where('name', 'like', '%' . $cityQuery . '%');
            })
                ->with(['region', 'district'])
                ->when($request->has('city_id'), function ($query) {
                    $query->where('id', request('city_id'));
                })
                ->when($request->has('region'), function ($query) {
                    $query->whereHas('region', function ($query) {
                        $query->where('name', 'like', '%' . request('region') . '%');
                    });
                })
                ->when($request->has('district'), function ($query) {
                    $query->whereHas('district', function ($query) {
                        $query->where('name', 'like', '%' . request('district') . '%');
                    });
                })
                ->when($request->has('region_id'), function ($query) {
                    $query->whereHas('region', function ($query) {
                        $query->where('id', request('region_id'));
                    });
                })
                ->when($request->has('district_id'), function ($query) {
                    $query->whereHas('district', function ($query) {
                        $query->where('id', request('district_id'));
                    });
                })
                ->orderBy('order', 'desc') // Sort by the `order` column in descending order
                ->get();
        }
        // Return the results wrapped in a resource collection
        return AskarCityResource::collection($results);
    }


}
