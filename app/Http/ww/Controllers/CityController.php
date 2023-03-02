<?php

namespace App\Http\Controllers;

use App\geo_cities;
use App\support\SetActualCoordinates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("welcome");
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

        $city = DB::table("geo_cities")
            ->insert([
                'name' => $request['city'],
                'lng' => $request['lng'],
                'lat' => $request['lat'],
                'new_lng' => $request['lng'],
                'new_lat' => $request['lat'],
                'actual' => 'new',
                'multiple' => $request['lng'] * $request['lat'],
                'region_id' => $request['region'],
                'district_id' => $request['district'],
                'timestamp' => Carbon::now()->timestamp
            ]);
        return response()->json("Успешно Добавлено");
        //return response()->json($city);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DB::table('geo_cities')
            ->where("id", $id)
            ->get()//            ->first()
            ;
    }

    public function bytimestamp($id)
    {
        $data = ['status' => '', 'city' => []];
        $count = DB::table('geo_cities')
            ->where("timestamp", ">", $id)
            ->count();
        if (strlen($id) != 10 || $count > 1000) {
            $data['status'] = 'error';
            return response()->json($data);
        }
        $cityData = DB::table('geo_cities')
            ->select("*")
            ->where("timestamp", ">", $id)
            ->get()->toArray();
        $data['status'] = 'success';
        $data['city'] = $cityData;
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);

    }

    public function region($id)
    {
        $regionData = DB::table('geo_cities')
            ->select("region_id", "name", "district_id")
            ->where("id", $id)
            ->get();

        //dump($regionData);
        return response()->json($regionData);
//        $data = ['status' => '', 'region' => []];
//        if(strlen($id)!=10){
//            $data['status'] = 'error';
//            return response()->json($data);
//        }
//        $cityData = DB::table('geo_regions')
//            ->select("*")
//            ->where("timestamp",">",$id)
//            ->get()->toArray();
//              $data['status'] = 'success';
//            $data['region'] = $cityData;
//        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
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

        DB::table('geo_cities')->where('id', $id)
            ->update(['name' => $request["name"],
//                'lng' => $request["lng"],
//                'lat' => $request["lat"],
                'new_lng' => $request["new_lng"],
                'new_lat' => $request["new_lat"],
                'actual' => 'new',
                'timestamp' => Carbon::now()->timestamp
            ]);
        return response()->json($request->all());
    }

    public function setActual($id)
    {
        $city = geo_cities::find($id);
        $city->actual = 'new_';
        if (!($city->new_lat && $city->new_lng)) {
            $city->actual = '';
        }
        $city->save();
        if ($city->actual == '') return 'empty';
        return $city->actual;
    }

    public function set()
    {
        $s = new SetActualCoordinates();
        $s->cycleAll();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('geo_cities')->delete($id);
        return response()->json("Успешно удалено");
    }
}
