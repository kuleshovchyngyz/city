<?php

namespace App\Http\Controllers;

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
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
