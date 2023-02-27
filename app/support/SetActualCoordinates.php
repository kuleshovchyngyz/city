<?php


namespace App\support;
use App\geo_cities;

class SetActualCoordinates
{
    public $query;
    public $duplicates = array();
    public $dupKeys;
    public  $original;
    private $city;

    public function __construct($var)
    {
        $this->original = $var;
        $this->query = clone $var;
        $this->addMultiple();
        $this->duplicates();
        $this->updateCity();

    }

    public function addMultiple()
    {
        foreach ($this->query as $key => $q) {
            $this->query[$key]->multiple = $q->new_lng * $q->new_lat;
        }
    }
    public function convert(){
        return geo_cities::where('actual',null)->pluck('name')->take(1000)->unique();
    }
    public  function  cycle(){

        while(true){
            $cities = $this->convert();
            if($cities->count()==0){
                break;
            }else{
                foreach ($cities as $city){
                    $this->query = geo_cities::where('name',$city)->get();
                    $this->addMultiple();
                    $this->duplicates();
                    $this->updateCity();

                }
            }
            echo 'working...'.'<br>';

        }
    }
    public function cycleAll(){
        $this->query = geo_cities::all();
        $this->addMultiple();
        $this->duplicates();
        $this->updateCity();
    }
    public function duplicates()
    {
        $this->dupKeys = $this->query->toBase()->duplicates('multiple')->unique();

        foreach ($this->dupKeys as $item) {
            $this->duplicates[] = $this->query->where('multiple', $item);
        }
    }
    //foreach ($d as $item) { geo_cities::where('multiple',$item)->update(['actual'=>'']);  }
    public function updateCity(){

            if($this->query->count()<200){
                foreach ($this->duplicates as $duplicates){
                    foreach ($duplicates as $key=>$duplicate){
                        $this->query->forget($key);
                        $this->original[$key]->actual = 'old';
                        $city = geo_cities::find($duplicate->id);
                        $city->actual = 'old';
                        $city->save();
                    }
                }
                //dd($this->query);
                foreach ($this->query as $key=>$query){
                    if(($query->new_lat*$query->new_lng)==0){
                        $city = geo_cities::find($query->id);
                        $city->actual = 'old';
                        $city->save();
                    }else{
                        $this->original[$key]->actual = 'new';
                        $city = geo_cities::find($query->id);
                        $city->actual = 'new-';
                        $city->save();
                    }

                }
            }

    }
    public function getActualCoordinates()
    {
        return $this->original;
        return [$this->query, $this->duplicates, $this->dupKeys, $this->original];

    }
}
