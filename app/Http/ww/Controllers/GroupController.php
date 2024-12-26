<?php

namespace App\Http\Controllers;

use App\geo_cities;
use App\Http\Resources\AskarCityResource;
use App\Http\Resources\CityResource;
use App\support\SetActualCoordinates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $groups = DB::table('geo_groups')
            ->select("id", "name")
            ->get();
        return view('welcome', [
            'groups' => $groups,

        ]);
    }

    public function updateCity(Request $request)
    {
        return response()->json($request);
    }

    public function dataAjaxGroup(Request $request)
    {
        //return $request;
        $search = $request["value"];
        $cities = DB::table('geo_cities')
            ->where('name', 'LIKE', "%$search%")
            ->get();
    }

    public function dataAjaxRegion(Request $request)
    {
        $search = $request["value"];
        $group_id = $request["groupId"];
        if ($group_id > 0) {
            $regions = DB::table('geo_regions')
                ->where('name', 'LIKE', "%$search%")
                ->where('group_id', $group_id)
                ->get();
        } else {
            $regions = DB::table('geo_regions')
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return $regions;
    }

    public function dataAjaxDistrict(Request $request)
    {
        $search = $request["value"];
        $group_id = $request["groupId"];
        $region_id = $request["regionId"];

        if ($group_id > 0 && !$region_id > 0) {
            $districts = DB::table('geo_districts')
                ->where('name', 'LIKE', "%$search%")
                ->where('group_id', $group_id)
                ->get();
        } else if ($region_id > 0 && !$group_id > 0) {
            $districts = DB::table('geo_districts')
                ->where('name', 'LIKE', "%$search%")
                ->where('region_id', $region_id)
                ->get();
        } else if ($region_id > 0 && $group_id > 0) {
            $districts = DB::table('geo_districts')
                ->where('name', 'LIKE', "%$search%")
                ->where('region_id', $region_id)
                ->where('group_id', $group_id)
                ->get();
        } else {

            $districts = DB::table('geo_districts')
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }


        return $districts;
    }

    public function dataAjaxCity(Request $request)
    {
//        return [$request->get('regions') == 1  ? "zero" : "not zero",
//            $request->all()
//        ];
//        $search = $request["value"];
//        $cities = DB::table('geo_cities')
//            ->select("geo_cities.*",
//                "geo_regions.name as region_name",
//                "geo_districts.name as district_name"
//            )
//            ->join("geo_regions", "geo_regions.id", "=", "geo_cities.region_id")
//            ->join("geo_districts", "geo_districts.id", "=", "geo_cities.district_id")
//            ->where('geo_cities.name', 'LIKE', "%$search%");
//
//
//        if ($request['region'] != 0) {
//            $cities = $cities->where("geo_cities.region_id", $request['region']);
//        }
//        if ($request['district'] != 0 && $request['table'] != "regions") {
//            $cities = $cities->where("geo_cities.district_id", $request['district']);
////            $cities = DB::table('geo_cities')
////                ->where('name','LIKE',"%$search%")
////                ->where("region_id",$request['region'])
////                ->where("district_id",$request['district']);
//        }
//        return response()->json($cities->get());

        $cities = geo_cities::when($request->get('value'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->value . '%');
        })
            ->with(['region', 'district'])
            ->when($request->has('region') && $request->get('region') != 0, function ($query) {
                $query->whereHas('region', function ($query) {
                    $query->where('id', 'like', '%' . request('region') . '%');
                });
            })
            ->when($request->has('district') && $request->get('district') != 0, function ($query) {
                $query->whereHas('district', function ($query) {
                    $query->where('id', 'like', '%' . request('district') . '%');
                });
            })
            ->get()
            ->map(function ($item) {
                $region = [];
                if (isset($item['region'])) {
                    $region['region_name'] = $item['region']['name'];
                    $region['region_id'] = $item['region']['id'];
                }
                $district = [];
                if (isset($item['district'])) {
                    $district['district_name'] = $item['district']['name'];
                    $district['district_id'] = $item['district']['id'];
                }
                return array_merge($item->toArray(), $region, $district);
            });
        return $cities;
        return json_encode($cities);
        $s = new SetActualCoordinates($cities);


        return response()->json($s->getActualCoordinates());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function geo()
    {

        //dump(999999999);
//        $ch = curl_init("https://nominatim.openstreetmap.org/search?city=Гавриловский&state=Республика%20Адыгея&country=Украина&format=json");
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//       $server_output = curl_exec($ch);
//        curl_close($ch);
        //    dump(json_decode($server_output));
        // dump(json_decode($server_output)[0]->lon);
        $regions = DB::table('geo_regions')
            ->select("id", "name")
            ->get();
        $cities = DB::table('geo_cities')
            ->select("id", "name", "region_id", "timestamp")
            ->get();


//wget -qO /dev/null http://local.parking/
        for ($i = 0; $i < count($cities); $i++) {
            for ($j = 0; $j < count($regions); $j++) {
                if ($regions[$j]->id == $cities[$i]->region_id && $cities[$i]->timestamp == "0") {
                    // dump ($cities[$i]->name);
                    // dump ($cities[$i]->id);
                    // dump ($regions[$j]->name);
                    $ch = curl_init("https://nominatim.openstreetmap.org/search?city=" . urlencode($cities[$i]->name) . "&state=" . urlencode($regions[$j]->name) . "&country=Украина&format=json");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close($ch);
                    if (!empty(json_decode($server_output))) {
                        DB::table('geo_cities')->where('id', $cities[$i]->id)
                            ->update(['new_lat' => json_decode($server_output)[0]->lat, 'new_lng' => json_decode($server_output)[0]->lon, 'timestamp' => Carbon::now()->timestamp]);
                        dump($server_output);
                        sleep(1);
                    } else {
                        DB::table('geo_cities')->where('id', $cities[$i]->id)
                            ->update(['timestamp' => Carbon::now()->timestamp]);
                    }


                }
            }
        }
        // dump($regions[0]->id);
        //dump($cities[0]    );
    }

    public function count()
    {

//      $n = DB::table('geo_cities')
//             ->update(['timestamp' => '1616039457']);
//        dump($n);
//        $count = DB::table('geo_cities')
//            ->where("new_lng","=","0")
//            ->count();
//        $target = "dsfsd/";
//        dump( preg_match("/^\//",$target));

//        DB::table('geo_cities')->where('region_id',"81")
//            ->update(['timestamp' => "0"]);
        for ($i = 0; $i < 100; $i++) {
            Storage::append('responses.txt', ' text ' . $i);
            dump($i);
            //Storage::append('responses.txt', ' squred '.($i*$i));
        }

    }

    public function search(Request $request)
    {
        $stack = [];
        $regions = DB::table('geo_regions')
            ->where("group_id", $request['group'])
            ->get();
        $regions_name = DB::table('geo_regions')
            ->where("id", $request['region'])
            ->value('name');

        //array_push($regions,$regions_name);
        $districts = DB::table('geo_districts')
            ->where("region_id", $request['region'])
            ->get();//\local.parking\public
        $districts_name = DB::table('geo_districts')
            ->where("id", $request['district'])
            ->value('name');//\local.parking\public
        $cities = DB::table('geo_cities')
            ->where("region_id", $request['region'])
            ->get();
        $cities [] = $regions_name;
        $cities [] = "";
        if ($request['district'] != 0 && $request['table'] != "regions") {
            $cities = DB::table('geo_cities')
                ->where("region_id", $request['region'])
                ->where("district_id", $request['district'])
                ->get();
            $cities [] = $regions_name;
            $cities [] = $districts_name;
        }

        $fullcities = DB::table('geo_cities')
            ->select("geo_cities.*",
                "geo_regions.name as region_name",
                "geo_districts.name as district_name"
            )
            ->join("geo_regions", "geo_regions.id", "=", "geo_cities.region_id")
            ->join("geo_districts", "geo_districts.id", "=", "geo_cities.district_id");
        if (isset($request['region'])) {
            $fullcities = $fullcities->where('geo_regions.id', '=', $request['region']);
        }
        if (isset($request['district']) && $request['district'] > 0) {
            $fullcities = $fullcities->where('geo_districts.id', '=', $request['district']);
        }
        $fullcities = $fullcities
            ->get();


        array_push($stack, $regions, $districts, $cities, $fullcities);

//        $regionData['data'] = DB::table('geo_regions')
//            ->select("id","name")
//            ->where("group_id")
//            ->get();
        //dump($regionData);

        return response()->json($stack);
    }

    public function show()
    {
        $groups = DB::table('geo_groups')
            ->select("id", "name")
            ->get();
        return view('welcome', [
            'groups' => $groups,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchCity(Request $request)
    {
        $result = CityResource::collection(
            geo_cities::when($request->get('city'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->city . '%');
            })
                ->with(['region', 'district'])
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
                ->get());
        return json_decode(json_encode($result), true);
    }
    public function searchAskar(Request $request)
    {
        // Predefined absolute matches
        $absoluteMatches = [
            'Москва' => 45470,
            'Санкт-Петербург' => 43754,
            'Новосибирск' => 58528,
            'Екатеринбург' => 81259,
            'Нижний Новгород' => 52350,
            'Казань' => 8346,
            'Челябинск' => 100951,
            'Омск' => 59684,
            'Самара' => 77561,
            'Ростов-на-Дону' => 73179,
            'Уфа' => 1473,
            'Красноярск' => 13107,
            'Воронеж' => 27979,
            'Пермь' => 66088,
            'Волгоград' => 24775,
            'Краснодар' => 12529,
            'Саратов' => 79913,
            'Тюмень' => 99758,
            'Тольятти' => 78287,
            'Курск' => 39750,
        ];

        // Fetch filtered cities from the database
        $filteredCities = geo_cities::when($request->get('city'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->city . '%');
        })
            ->with(['region', 'district'])
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
            ->get();

        // Transform filtered cities into desired structure
        $filteredResources = $filteredCities->map(function ($city) {
            return [
                "id" => $city->id,
                "text" => $city->name,
                "lng" => $city->lng ?? null,
                "lat" => $city->lat ?? null,
                "region" => $city->region ? [
                    "id" => $city->region->id,
                    "name" => $city->region->name
                ] : null,
                "district" => $city->district ? [
                    "id" => $city->district->id,
                    "name" => $city->district->name
                ] : null,
                "group" => null
            ];
        });

        // Check if the query is 1 to 3 characters
        $cityQuery = $request->get('city');
        $absoluteResources = [];

        if ($cityQuery && strlen($cityQuery) <= 3) {
            // Include absolute matches, filtering for partial matches
            $absoluteResources = collect($absoluteMatches)
                ->map(function ($id, $name) use ($cityQuery) {
                    // Check if the city name contains the query
                    if (stripos($name, $cityQuery) !== false) {
                        return [
                            "id" => $id,
                            "text" => $name,
                            "lng" => null,
                            "lat" => null,
                            "region" => null,
                            "district" => null,
                            "group" => null
                        ];
                    }
                    return null; // Exclude non-matching entries
                })
                ->filter() // Remove null entries
                ->values() // Reindex the collection
                ->toArray();
        }

        // Combine absolute matches with filtered results
        $result = array_merge($absoluteResources, $filteredResources->toArray());

        return response()->json($result, 200);
    }



    public function searchAskar222(Request $request)
    {
        return 7777777777;
        return AskarCityResource::collection(
            geo_cities::when($request->get('city'), function ($q) use ($request) {
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
                ->get());

        response()->json($result, 200);
    }

    public function showCity(Request $request, geo_cities $city)
    {
        return new AskarCityResource($city);
    }

    public function getcities(Request $request)
    {
        if (is_array($request->get('city_ids'))){
            return AskarCityResource::collection(
                geo_cities::whereIn('id', $request->get('city_ids'))->get()
            );
        }
        return [];
    }


}
