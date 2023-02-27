@extends('layouts.app')

@section('content')

    <nav class="container navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand"></a>
        <div class="form-inline">
            <a href="{{ route('documentation') }}"  class="btn btn-outline-info ml-3">Api документация</a>
            <button id="refresh" type="button" class="btn btn-outline-info ml-3">Обновить</button>
            <button class="btn btn-outline-success my-2 my-sm-0 ml-3"  id="buttonAddDistrict" >Добавить район</button>
            <button class="btn btn-outline-success my-2 my-sm-0 ml-3"  id="buttonAddCity" >Добавить город</button>

        </div>
    </nav>


    <div class="container  mt-5" >
        <div class="messages"></div>
        <div class="row">
            <div class="col-md-3 ">

                    <input class="form-control " type="search" id="searchgroup" placeholder="Искать группу" aria-label="Search">


            </div>
            <div class="col-md-3">
                <input class="form-control " type="search" id="searchregion" placeholder="Искать регион" aria-label="Search">
            </div>
            <div class="col-md-3  ">
                <input class="form-control " type="search" id="searchdistrict" placeholder="Искать дистрикт" aria-label="Search">
            </div>
            <div class="col-md-3  ">
                <input class="form-control" type="search" id="searchcity" placeholder="Искать город" aria-label="Search">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="item-title">Группы:</div>
                <div class="item text-center  overflow-auto" style="height: 433px">

                    <ul id="groups" class="p-0">
                        @foreach ($groups as $key=>$group)
                            <li class="list-group-item" data-group-id="{{ $group->id }}">{{ $group->name }}</li>
                        @endforeach
                    </ul>

                </div>
            </div>
            <div class="col-md-3">
                <div class="item-title">Регионы:</div>
                <div class="item text-center  overflow-auto" style="height: 433px">

                    <ul id="regions" class="p-0">

                    </ul>

                </div>
            </div>
            <div class="col-md-3">
                <div class="item-title">Дистрикты:</div>
                <div class="item text-center  overflow-auto" style="height: 433px">

                    <ul id="districts" class="p-0">

                    </ul>

                </div>
            </div>
            <div class="col-md-3">



                <div class="item-title">Города:</div>
                <div class="item text-center  overflow-auto" style="height: 433px" id="parentCity" >

                    <ul id="cities" class="p-0">

                    </ul>

                </div>

            </div>
        </div>
        <div class="preload"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div>
    </div>





    <!-- Modal City Edit -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Город</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body city">

                </div>
                <div class="d-flex justify-content-between pr-3 pl-3">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">
                            долгота(lng):</label>
                        <input type="text" class="form-control" id="elng" disabled>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">широта(lat):</label>
                        <input type="text" class="form-control" id="elat" disabled>
                    </div>
                </div>
                <div class="d-flex justify-content-between pr-3 pl-3">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">
                            Новая долгота(lng):</label>
                        <input type="text" class="form-control" id="newlng">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Новая широта(lat):</label>
                        <input type="text" class="form-control" id="newlat">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="deleteCity" class="btn btn-danger">Удалить</button>
                    <button type="button" id="UpdateCity" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="addCity"  role="dialog" aria-labelledby="addCity" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить город</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body addCity">
                    <div class="form-group mk">


                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Район:</label>
                        <select id='selUser' style='width: 200px;'>
                            <option value='0'>-- Выберите район--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Имя города:</label>
                        <input type="text" class="form-control" id="city-name">
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">
                                долгота(lng):</label>
                            <input type="text" class="form-control" id="lng">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">широта(lat):</label>
                            <input type="text" class="form-control" id="lat">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="AddCitybutton" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="addDistrict"  role="dialog" aria-labelledby="addCity" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить район</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body addDistrict">
                    <div class="form-group mk">


                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Район:</label>
                        <input type="text" class="form-control" id="district-name"  >
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="AddDistrictbutton" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal District-->
    <div class="modal fade" id="districtEditModal"  role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Район</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body district">

                </div>
                <div class="d-flex justify-content-between pr-3 pl-3">


                    <div class="modal-footer">
                        <button type="button" id="deleteDistrict" class="btn btn-danger">Удалить</button>
                        <button type="button" id="UpdateDistrict" class="btn btn-success">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
