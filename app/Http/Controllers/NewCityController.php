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
        Log::info('Search Request:', $request->all());

        // Fetch and sort cities
        return $query = geo_cities::when($request->get('city'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->city . '%');
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
            ->orderBy('order', 'asc') // Sort by `order` column
            ->toSql();

        // Log the query to verify `orderBy` is applied
        Log::info('Generated Query:', ['query' => $query]);

        // Execute and return results
        $results = geo_cities::when($request->get('city'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->city . '%');
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
            ->orderBy('order', 'asc')
            ->get();

        return AskarCityResource::collection($results);
    }

}
