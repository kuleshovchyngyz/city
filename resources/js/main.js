

import ymaps from "ymaps";
var curr_lat;
var curr_lng;

jQuery('body')
    .on('click', '.dropdown-menu.emp a', function() {
        $(this).parents('.dropdown').find('.dropdown-toggle').text(jQuery(this).text());
    })
let list;
$('.item ul').on('click', 'li', function(e) {
    let name = $(this).parent('ul').attr('id');
    $(this).parent('ul').children('li').removeClass('active');
    list = $(this).parent().attr('id');
    $(this).addClass('active');
    filterQuery(name);
});




var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

//select2 district search


$( "#selUser" ).select2({

    ajax: {
        url: "select2-autocomplete-ajax",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {

            var districtId = $('#districtIdofCity').val();
            var  regionId = $('#regionIdofCity').val();
            regionId = (regionId) ? regionId * 1 : 0;
            districtId = (districtId) ? districtId * 1 : 0;
            return {
                _token: CSRF_TOKEN,
                search: params.term, // search term
                regionId:regionId,
                districtId:districtId

            };
        },
        processResults: function (response){


            return {
                results: response
            };
        },
        cache: true
    }

});







//
// import ymaps from 'ymaps';
// const app = document.getElementById("mapYandex");
// const button = document.createElement("button");
// button.innerText = "Load map";
// app.appendChild(button);
//
// button.addEventListener("click", async function() {
//
//
//     ymaps
//         .load()
//         .then(maps => {
//             const map = new maps.Map('mapGoogle', {
//                 center: [ 55.7504461 , 37.6174943],
//                 zoom: 7
//             });
//         })
//         .catch(error => console.log('Failed to load Yandex Maps', error));
//
//
//
//
// });


function filterQuery(name) {

    let groupId = $('#groups > li.active').data('group-id');
    let regionId = $('#regions > li.active').data('region-id');
    let districtId = $('#districts > li.active').data('district-id');
    let cityId = $('#cities > li.active').data('city-id');

    groupId = (groupId) ? groupId * 1 : 0;
    regionId = (regionId) ? regionId * 1 : 0;
    districtId = (districtId) ? districtId * 1 : 0;
    cityId = (cityId) ? cityId * 1 : 0;
    let _token = $('meta[name="csrf-token"]').attr('content');
    let dataForm = {
        group: groupId,
        region: regionId,
        district: districtId,
        city: cityId,
        table: list,
        _token: _token
    };




    $.ajax({
        url: 'regions/search',
        type: 'post',
        data: dataForm
    })

        .done(function(data) {

            // show the response

            let dataRegions = data[0];
            let dataDistricts = data[1];
            let dataCities = data[2];
            let region_name = dataCities[dataCities.length-2];
            let district_name = dataCities[dataCities.length-1];
            dataCities.pop();
            dataCities.pop();

            // for(let key in dataRegions){
            //      //$("#regions").append(`<li class="list-group-item" data-regions-id="${key['id']}">${key['name']}</li>`);
            //
            // }
            if (list === "groups") {

                $('#regions').children().remove();
                $('#cities').children().remove();
                $('#districts').children().remove();
                dataRegions.forEach(function(key) {
                    $("#regions").append(`<li class="list-group-item" data-region-id="${key['id']}">${key['name']}</li>`);
                    //
                });

            } else if (list === "regions") {

                $('#cities').children().remove();
                $('#districts').children().remove();

                dataDistricts.forEach(function(key) {
                    $("#districts").append(`<li class="list-group-item" data-district-id="${key['id']}">${key['name']}</li>`);
                    //
                });
                //$('#cities').children().remove();
                dataCities.forEach(function(key) {
                    //8888
                    $("#cities").append(`<li class="city list-group-item" data-city-id="${key['id']}" data-actual="${key['actual']}" data-city-lng="${key['lng']}" data-city-lat="${key['lat']}" data-city-new_lng="${key['new_lng']}" data-city-new_lat="${key['new_lat']}"><div class="city">${key['name']}</div><div class="belong"></div></li>`);
                    //
                });
            }
            if (list === "districts") {
                $('#cities').children().remove();
                dataCities.forEach(function(key) {
                    $("#cities").append(`<li class="city list-group-item" data-city-id="${key['id']}" data-actual="${key['actual']}" data-city-lng="${key['lng']}" data-city-lat="${key['lat']}" data-city-new_lng="${key['new_lng']}" data-city-new_lat="${key['new_lat']}"><div class="city">${key['name']}</div><div class="belong"></div></li>`);
                    //
                });
            }




        })
        .fail(function(data) {

        });


}
$("ul").on("click", "li.region.list-group-item.searched", function() {

    region_group($(this).data("region-id"),function(output){

        $("ul#groups").children().removeClass("active");
        $(`ul#groups li[data-group-id="${output}"]`).addClass("active");
    });


});
$("ul").on("click", "li.district.list-group-item.searched", function() {


    distict_group($(this).data("district-id"),function(output){

        $('#regions').children().remove();
        $("#regions").append(`<li class="list-group-item active" data-region-id="${output["region"][0]["id"]}">${output["region"][0]["name"]}</li>`);

        let group=output["group_id"];
        $("ul#groups").children().removeClass("active");
        $(`ul#groups li[data-group-id="${group}"]`).addClass("active");


    });


});
var new_lng;
var new_lat;
var lng;
var lat;
var clicked ;
$("ul").on("click", "li.city.list-group-item", function() {

    let val = $(this).children().first().text();
    let id = $(this).data('city-id');
    let actual =$(this).data('actual');
    actual == 'old' ? (
        $("#radio1").prop("checked", true)
        ) : (
        $("#radio2").prop("checked", true)
    );



    lng = $(this).data('city-lng');
    lat = $(this).data('city-lat');


    // new_lng = $(this).data('city-'+actual+'lng');
    // new_lat = $(this).data('city-'+actual+'lat');
    new_lng = $(this).data('city-new_lng');
    new_lat = $(this).data('city-new_lat');



    curr_lat = (actual=='old') ? lat : new_lat;
    curr_lng = (actual=='old') ? lng : new_lng;

    var searchcity = $('#searchcity').val();


    if(searchcity!=""){
        city_region_distric($(this).data("city-id"),function(output){

            region(output[0]["region_id"],function (region) {
                $('#regions').children().remove();
                $("#regions").append(`<li class="list-group-item active" data-region-id="${region[0]["id"]}">${region[0]["name"]}</li>`);

                let group=region[0]["group_id"];
                $("ul#groups").children().removeClass("active");
                $(`ul#groups li[data-group-id="${group}"]`).addClass("active");

            });
            district(output[0]["district_id"],function (district) {

                $('#districts').children().remove();
                $("#districts").append(`<li class="list-group-item active" data-district-id="${district[0]["id"]}">${district[0]["name"]}</li>`);
            });

        });
    }


    $('#city_content').children().remove();
    $('#mapYandex').children().remove();
    $('.newlat').children().remove();
    $('.newlng').children().remove();
    $('.somediv').children().remove();
    $("#mapGoogle").children().remove();
    $("#city_content").append(`<h1>${val}</h1>`);
    $(".newlat").append(`<span>Lat: ${ curr_lat }</span>`);
    $(".newlng").append(`<span>Lng: ${ curr_lng }</span>`);

//https://www.google.com/maps/@42.8731421,74.6283312,15z
    const app = document.getElementById("mapYandex");
    const button = document.createElement("button");
    button.innerText = "Load map";
    //app.appendChild(button);
    $("#mapGoogle").append(`<a href="https://www.google.com/maps/@${curr_lat},${curr_lng},15z" target="_blank">GoogleMap</a>`);
    $('.somediv').append(`<a  href="https://yandex.ru/maps/?from=api-maps&ll=${curr_lng}%2C${curr_lat}&origin=jsapi_2_1_78&z=12,4" target="_blank">YandexMap</a>`);

    clicked = true;
    if(clicked){
        showMap();
    }



    //region($(this).data("city-id"));


});
function showMap(){


    button.addEventListener("click", async function() {

        if(clicked){
            clicked = false;

            $('#mapYandex').children().remove();
            try {
                //$('#mapYandex').children().remove();
                const lat = curr_lat;
                const lng = curr_lng;
                const maps = await ymaps.load('https://api-maps.yandex.ru/2.1/?lang=ru_RU');

                const mapContainer = document.getElementById("mapYandex");

                mapContainer.style.height = "512px";
                mapContainer.style.width = "512px";
                //app.removeChild(button);


                new maps.Map(mapContainer, {
                    center: [ lat , lng],
                    zoom: 12
                });
            } catch (error) {
                console.error("Something went wrong", error);
            }

        }
    });
    $(button).trigger("click");
}


$('input[type=radio][name=geodata]').change(function() {
    $('.somediv').children().remove();
    $("#mapGoogle").children().remove();
    clicked = true;
    $('.newlat').children().remove();
    $('.newlng').children().remove();

    $('#mapYandex').children().remove();
    if (this.value == 'old') {
        const app = document.getElementById("mapYandex");
        const button = document.createElement("button");
        curr_lat = lat;
        curr_lng = lng;
        console.log('old')

        $("#mapGoogle").append(`<a href="https://www.google.com/maps/@${curr_lat},${curr_lng},15z" target="_blank">GoogleMap</a>`);
        $('.somediv').append(`<a  href="https://yandex.ru/maps/?from=api-maps&ll=${curr_lng}%2C${curr_lat}&origin=jsapi_2_1_78&z=12,4" target="_blank">YandexMap</a>`);
        showMap();


    }
    else if (this.value == 'new') {
        curr_lat = new_lat;
        curr_lng = new_lng;
        console.log('new')
        $('#mapYandex').children().remove();
        const app = document.getElementById("mapYandex");
        const button = document.createElement("button");

        $("#mapGoogle").append(`<a href="https://www.google.com/maps/@${curr_lat},${curr_lng},15z" target="_blank">GoogleMap</a>`);
        $('.somediv').append(`<a  href="https://yandex.ru/maps/?from=api-maps&ll=${curr_lng}%2C${curr_lat}&origin=jsapi_2_1_78&z=12,4" target="_blank">YandexMap</a>`);
        showMap();
    }
    $(".newlat").append(`<span>Lat: ${curr_lat}</span>`);
    $(".newlng").append(`<span>Lng: ${curr_lng}</span>`);

});




function  region_group(id,handleData) {
    $.ajax({
        url: `region/group/${id}`,
        type: 'get'

    })
        .done(function (data) {
            //
            handleData(data);

        })
        .fail(function () {

        });
}
function  distict_group(id,handleData) {
    $.ajax({
        url: `district/group/${id}`,
        type: 'get'

    })
        .done(function (data) {

            handleData(data);

        })
        .fail(function (data) {

        });
}

function  city_region_distric(id,handleData) {
    $.ajax({
        url: `city/region/${id}`,
        type: 'get'

    })
        .done(function (data) {

            handleData(data);

        })
        .fail(function (data) {

        });

}
function region(id,handleData) {
    $.ajax({
        url: `region/${id}`,
        type: 'get',

    })
        .done(function (data) {
            handleData(data);
        })
        .fail(function () {

        });
}
function district(id,handleData) {
    $.ajax({
        url: `district/${id}`,
        type: 'get',

    })
        .done(function (data) {
            handleData(data);
        })
        .fail(function () {

        });
}

$(document).ready(function() {
    $("ul").on("dblclick", "li.city.list-group-item.active", function() {

        let val = $(this).children().first().text();

        let id = $(this).data('city-id');
        let lng = $(this).data('city-lng');
        let lat = $(this).data('city-lat');
        let new_lng = $(this).data('city-new_lng');
        let new_lat = $(this).data('city-new_lat');
        $('.modal-body.city').children().remove();
        $(".modal-body.city").append(`<input id="inputCity" class="form-control mr-sm-2 w-100" value="${val}" data-id="${id}" type="text"/>`);
        $("#elng").val(`${lng}`);
        $("#elat").val(`${lat}`);
        $("#newlng").val(`${new_lng}`);
        $("#newlat").val(`${new_lat}`);

        $('#exampleModal').modal('toggle');
    });


});
$(document).ready(function() {
    $("ul#districts").on("dblclick", "li.list-group-item.active", function() {

        let val = $('#districts > li.active').text();
        let id = $('#districts > li.active').data('district-id');

        $('.modal-body.district').children().remove();
        $(".modal-body.district").append(`<input id="inputDistrict" class="form-control mr-sm-2 w-100" value="${val}" data-id="${id}" type="text"/>`);
        $('#districtEditModal').modal('show');
    });


});
$("#refresh").on("click", function() {
    window.location.reload();
});
$("#buttonAddDistrict").on("click", function() {

    let regionId = $('#regions > li.active').data('region-id');
    regionId = (regionId) ? regionId * 1 : 0;
    if(regionId==0){
        alert('Пожалуйста выберите регион');
        return;
    }
    $('#addDistrict').modal('toggle');
    $(".modal-body.addDistrict").children().first().append(`<input name="regionIdofRegion" id="regionIdofRegion" type="hidden" value="${regionId}">`);
});



$("#buttonAddCity").on("click", function() {


    let regionId = $('#regions > li.active').data('region-id');
    let districtId = $('#districts > li.active').data('district-id');
    regionId = (regionId) ? regionId * 1 : 0;
    districtId = (districtId) ? districtId * 1 : 0;


    if(regionId==0){
        alert('Пожалуйста выберите регион');
        return;
    }
    $('#addCity').modal('toggle');
    let dataForm = {
        region: regionId,
        district: districtId
    };

    $.ajax({
        url: `region/${regionId}`,
        type: 'get',

    })
        .done(function (data) {


            $('.modal-body.addCity').find(".form-group.mk").children().remove();
            $(".modal-body.addCity").children().first().append(`<span data-region-id="${data[0]["id"]}" id="region-name">${data[0]["name"]}</span>`);
            $(".modal-body.addCity").children().first().append(`<input id="districtIdofCity" type="hidden" value="${districtId}">`);
            $(".modal-body.addCity").children().first().append(`<input id="regionIdofCity" type="hidden" value="${regionId}">`);

        })
        .fail(function () {

        });

});
//region/{id}

$("#AddDistrictbutton").on("click", function() {
    let _token = $('meta[name="csrf-token"]').attr('content');
    let district = $("#district-name").val();
//==========
    let districtIdofCity = $("#districtIdofCity").val();
    let regionId = $('#regions > li.active').data('region-id');
    regionId = (regionId) ? regionId * 1 : 0;

    let formValue1 = {
        regionId: regionId,
        district:district,
        _token:_token

    }
    $.ajax({
        url: 'district/store',
        type: 'post',
        data:formValue1


    })
        .done(function(data) {

            //

            $('.messages').children().remove();
            $(".messages").append(`<div class="alert alert-success" role="alert">Успешно Добавлено</div>`);
            $('#districts > li').removeClass('active');

            $("#districts").prepend(`<li class="district list-group-item active" data-district-id="${data[0]['id']}">${data[0]['name']}</li>`);
        })
        .fail(function(data) {

        });
    $('#addDistrict').modal('toggle');


});

$("#AddCitybutton").on("click", function() {
    let _token = $('meta[name="csrf-token"]').attr('content');
    let city = $("#city-name").val();
    let lng = $("#lng").val();
    let lat = $("#lat").val();
    let districtIdofCity = $("#districtIdofCity").val();
    let region = $("#region-name").data('region-id');
    let formValue1 = {
        city: city,
        lng: lng,
        lat: lat,
        region: region,
        district: districtIdofCity,
        _token: _token
    };
    $.ajax({
        url: 'city/store',
        type: 'post',
        data: formValue1

    })
        .done(function(data) {

            //

            $('.messages').children().remove();
            $(".messages").append(`<div class="alert alert-success" role="alert">${data}</div>`);

        })
        .fail(function() {

        });
    $('#addCity').modal('toggle');

});

$("#deleteCity").on("click", function() {
    if (confirm('Удалить ??')) {
        let id = $("#inputCity").data('id');
        let _token = $('meta[name="csrf-token"]').attr('content');
        let formValue1 = {
            name: $("#inputCity").val(),
            _token: _token
        };
        $("li.city.list-group-item.active").html($("#inputCity").val());

        $.ajax({
            url: `city/delete/${id}`,
            type: 'get',

        })
            .done(function (data) {

                //
                $('#cities').children().remove();
                $('.messages').children().remove();
                $(".messages").append(`<div class="alert alert-success" role="alert">${data}</div>`);


            })
            .fail(function () {

            });
        $('#exampleModal').modal('toggle');
    }

});


$("#deleteDistrict").on("click", function() {
    if (confirm('Удалить ??')) {
        let id = $("#inputDistrict").data('id');

        $.ajax({
            url: `district/delete/${id}`,
            type: 'get',

        })
            .done(function (data) {

                $('#districts').children().remove();
                $('.messages').children().remove();
                $(".messages").append(`<div class="alert alert-success" role="alert">${data}</div>`);


            })
            .fail(function () {

            });
        $('#districtEditModal').modal('toggle');
    }

});

$("#UpdateDistrict").on("click", function() {
    // var list = wrapper.find('input').map(function() {
    //     return $(this).val();
    // }).get();
    if (confirm('Редактировать ?')) {
        let id = $("#inputDistrict").data('id');
        let _token = $('meta[name="csrf-token"]').attr('content');
        let formValue1 = {
            name: $("#inputDistrict").val(),
            _token: _token
        };


        $("ul#districts > li.list-group-item.active").html($("#inputDistrict").val());



        $.ajax({
            url: `district/update/${id}`,
            type: 'post',
            data: formValue1
        })


            .done(function (data) {

                //
                $('.messages').children().remove();
                $(".messages").append(`<div class="alert alert-success" role="alert">${data}</div>`);
            })
            .fail(function () {

            });
        $('#districtEditModal').modal('toggle');
    }
});
$("#UpdateCity").on("click", function() {
    // var list = wrapper.find('input').map(function() {
    //     return $(this).val();
    // }).get();
    if (confirm('Редактировать ?')) {
        let id = $("#inputCity").data('id');
        let _token = $('meta[name="csrf-token"]').attr('content');
        let formValue1 = {
            name: $("#inputCity").val(),
            lng: $("#elng").val(),
            lat: $("#elat").val(),
            _token: _token
        };
        $("li.city.list-group-item.active").html($("#inputCity").val());
        $("li.city.list-group-item.active").data("city-lat", $("#elat").val());
        $("li.city.list-group-item.active").data("city-lng", $("#elng").val());


        $.ajax({
            url: `city/update/${id}`,
            type: 'post',
            data: formValue1
        })


            .done(function (data) {

                //
                $('.messages').children().remove();
                $(".messages").append(`<div class="alert alert-success" role="alert">${data}</div>`);
            })
            .fail(function () {

            });
        $('#exampleModal').modal('toggle');
    }
});

//Searching region
$('#searchregion').on('keyup', function() {

    let groupId = $('#groups > li.active').data('group-id');
    groupId = (groupId) ? groupId * 1 : 0;
    let value = $(this).val();
    if (value.length > 2) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let formValue = {
            _token: _token,
            value: value,
            groupId:groupId

        }
        $.ajax({
            url: 'autocomplete-ajax-region',
            type: 'post',
            data: formValue
        })
            .done(function(data) {
                //

                if (data.length == 0) {

                    $('#regions').children().remove();
                    $("#regions").append(`<li class="list-district-item">Ничего не найдено...</li>`);
                } else {
                    if(data.length==1){
                        $('#regions').children().remove();
                        data.forEach(function(key) {
                            $("#regions").append(`<li class="region list-group-item searched active" data-region-id="${key['id']}">${key['name']}</li>`);
                            //
                        });
                    }else{
                        $('#regions').children().remove();
                        data.forEach(function(key) {
                            $("#regions").append(`<li class="region list-group-item searched" data-region-id="${key['id']}">${key['name']}</li>`);
                            //
                        });
                    }
                }



            })
            .fail(function(data) {

            });
    }else if (value.length == 0) {
        $('ul#regions').children().remove();
    }

});
//Searching district
$('#searchdistrict').on('keyup', function() {


    let groupId = $('#groups > li.active').data('group-id');
    let regionId = $('#regions > li.active').data('region-id');


    groupId = (groupId) ? groupId * 1 : 0;
    regionId = (regionId) ? regionId * 1 : 0;
    let _token = $('meta[name="csrf-token"]').attr('content');



    let value = $(this).val();
    if (value.length > 2) {

        let formValue = {
            _token: _token,
            value: value,
            groupId:groupId,
            regionId:regionId

        }
        $.ajax({
            url: 'autocomplete-ajax-district',
            type: 'post',
            data: formValue
        })
            .done(function(data) {

                if (data.length == 0) {

                    $('#districts').children().remove();
                    $("#districts").append(`<li class="list-group-item">Ничего не найдено...</li>`);
                } else {
                    $('#districts').children().remove();
                    data.forEach(function(key) {
                        $("#districts").append(`<li class="district list-group-item searched" data-district-id="${key['id']}">${key['name']}</li>`);
                        //
                    });
                }

            })
            .fail(function(data) {

            });
    }else if (value.length == 0) {
        $('ul#districts').children().remove();
    }

});
$('#searchgroup').on('keyup', function() {

    let value = $(this).val();
    if (value.length > 2) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let formValue = {
            _token: _token,
            value: value

        }
        $.ajax({
            url: 'autocomplete-ajax-group',
            type: 'post',
            data: formValue
        })
            .done(function(data) {
                //




            })
            .fail(function(data) {

            });
    }

});
$('#searchcity').on('keyup', function() {

    let value = $(this).val();
    if (value.length > 2) {

        let groupId = $('#groups > li.active').data('group-id');
        let regionId = $('#regions > li.active').data('region-id');
        let districtId = $('#districts > li.active').data('district-id');
        let cityId = $('#cities > li.active').data('city-id');

        groupId = (groupId) ? groupId * 1 : 0;
        regionId = (regionId) ? regionId * 1 : 0;
        districtId = (districtId) ? districtId * 1 : 0;
        cityId = (cityId) ? cityId * 1 : 0;
        let _token = $('meta[name="csrf-token"]').attr('content');
        let formValue = {
            group: groupId,
            region: regionId,
            district: districtId,
            city: cityId,
            value: value,
            _token: _token

        };

        //
        $.ajax({
            url: 'autocomplete-ajax-city',
            type: 'post',
            data: formValue
        })
            .done(function(data) {
                //
               //
                if (data.length == 0) {

                    $('#cities').children().remove();
                    $("#cities").append(`<li class="list-group-item">Ничего не найдено...</li>`);
                } else {
                    $('#cities').children().remove();
                    data.forEach(function(key) {

                        let region_name = key['region_name'];
                        let district_name = key['district_name'];
                        //$("#cities").append(`<li class="city list-group-item searched" data-city-id="${key['id']}" data-city-lng="${key['lng']}" data-city-lat="${key['lat']}" data-city-new_lng="${key['new_lng']}" data-city-new_lat="${key['new_lat']}">${key['name']}</li>`);
                        $("#cities").append(`<li class="city list-group-item" data-city-id="${key['id']}" data-actual="${key['actual']}" data-city-lng="${key['lng']}" data-city-lat="${key['lat']}" data-city-new_lng="${key['new_lng']}" data-city-new_lat="${key['new_lat']}"><div class="city">${key['name']}</div><div class="belong">${region_name}, ${district_name}</div></li>`);
                        //
                    });
                }


            })
            .fail(function(data) {

            });

    } else if (value.length == 0) {
        $('#cities').children().remove();
    }

})



//$_post['dataForm']['group']
function  ajaxQuery(id,handleData,address) {
    $.ajax({
        url: `${address}${id}`,
        type: 'get'

    })
        .done(function (data) {
            //
            handleData(data);

        })
        .fail(function () {

        });
}

