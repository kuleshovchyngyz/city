<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


    }

    public function dataAjaxRegion(Request $request)
    {
        $search = $request->search;
        $region_id = $request->regionId;
        $district_id = $request->districtId;

        if($search == ''){
            //$employees = Employees::orderby('name','asc')->select('id','name')->limit(5)->get();
            $employees = DB::table('geo_districts')
                ->select('id','name')
                ->where('region_id',$region_id)
                ->get();


            if($region_id>0){
                $employees = DB::table('geo_districts')
                            ->select('id','name')
                            ->where('region_id',$region_id)->get();
            }
            if($district_id>0){
                $employees =  DB::table('geo_districts')
                            ->select('id','name')
                            ->where('id',$district_id)->get();
            }
            if($district_id>0&&$region_id){
                $employees =  DB::table('geo_districts')
                    ->select('id','name')
                    ->where('region_id',$region_id)
                    ->where('id',$district_id)->get();
            }
        }else{
             //= Employees::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(5)->get();

            $employees = DB::table('geo_districts')->select("id","name")->where('name','LIKE',"%$search%")
                    ->get();
            if($region_id>0){
                $employees = DB::table('geo_districts')
                    ->select('id','name')
                    ->where('region_id',$region_id)->get();
            }
            if($district_id>0){
                $employees =  DB::table('geo_districts')
                    ->select('id','name')
                    ->where('id',$district_id)->get();
            }
            if($district_id>0&&$region_id){
                $employees =  DB::table('geo_districts')
                    ->select('id','name')
                    ->where('region_id',$region_id)
                    ->where('id',$district_id)->get();
            }
        }

        $response = array();
        foreach($employees as $employee){
            $response[] = array(
                "id"=>$employee->id,
                "text"=>$employee->name
            );
        }

        echo json_encode($response);
        exit;
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
    public function bytimestamp($id)
    {
        $regionData = DB::table('geo_regions')
            ->select("*")
            ->where("timestamp",">",$id)
            ->get()->toJson(JSON_UNESCAPED_UNICODE);
        return $regionData;
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
    public function group($id){
        //dd($id);
        $group_id = DB::table('geo_regions')
            ->where("id",$id)
            ->value('group_id');


        return $group_id;
    }
    public function show($id)
    {
        $regionData = DB::table('geo_regions')
            ->select("id","name","group_id")
            ->where("id",$id)
            ->get();
        //dump($regionData);
        return response()->json($regionData);
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