<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $time = Carbon::now()->timestamp;
        DB::table("geo_districts")
            ->insert([
                'name' => $request['district'],
                'region_id'=> $request['regionId'],
                'country_id'=> 1,
                'timestamp' => $time
            ]);
        $district =  DB::table("geo_districts")
            ->where('timestamp',$time)
            ->get();

        return response()->json($district);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $districtData = DB::table('geo_districts')
            ->select("id","name")
            ->where("id",$id)
            ->get();
        //dump($regionData);
        return response()->json($districtData);
    }
    public function group($id)
    {
/*      $districtData = DB::table('geo_districts')
            ->select("id","name","region_id")
            ->where("id",$id)
            ->get();*/
        //dump($regionData);
        $data = ['group_id' => '', 'region' => []];
        $region_id = DB::table('geo_districts')
            ->where("id",$id)
            ->value("region_id");

        $regionData = DB::table('geo_regions')
            ->where("id",$region_id);

         $groupData = DB::table('geo_groups')
            ->where("id",$regionData ->value("group_id"))->value("id");
        $data["group_id"] = $groupData;
        $data["region"] = $regionData->get();


        return $data;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function bytimestamp($id)
    {
        $data = ['status' => '', 'district' => []];
        $count = DB::table('geo_districts')
            ->where("timestamp",">",$id)
            ->count();
        if(strlen($id)!=10||$count>1000){
            $data['status'] = 'error';
            return response()->json($data);
        }
        $districtData = DB::table('geo_districts')
            ->select("*")
            ->where("timestamp",">",$id)
            ->get()->toArray();
        $data['status'] = 'success';
        $data['district'] = $districtData;
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::table('geo_districts')->where('id',$id)
            ->update(['name' => $request["name"],
                'timestamp' => Carbon::now()->timestamp
            ]);
        return response()->json("Успешно обновлено");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('geo_districts')->delete($id);
        return response()->json("Успешно удалено");
    }
}
