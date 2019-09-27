const test_data = '{"uuid": "", "data_type": "alpr_results", "epoch_time": 1569484450487, "processing_time": {"total": 906.9429999999556, "plates": 83.41350555419922, "vehicles": 377.7350000000297}, "img_height": 4032, "img_width": 3024, "results": [{"plate": "511TYP", "confidence": 94.7018814086914, "region_confidence": 99, "vehicle_region": {"y": 451, "x": 0, "height": 2964, "width": 2964}, "region": "au-qld", "plate_index": 0, "processing_time_ms": 20.504215240478516, "candidates": [{"matches_template": 1, "plate": "511TYP", "confidence": 94.7018814086914}, {"matches_template": 0, "plate": "51TYP", "confidence": 79.05220794677734}, {"matches_template": 0, "plate": "51ITYP", "confidence": 78.95147705078125}], "coordinates": [{"y": 2321, "x": 1103}, {"y": 2289, "x": 1783}, {"y": 2557, "x": 1786}, {"y": 2590, "x": 1110}], "vehicle": {"orientation": [{"confidence": 99.7264404296875, "name": "180"}, {"confidence": 0.19897334277629852, "name": "135"}, {"confidence": 0.07324973493814468, "name": "225"}, {"confidence": 0.0007010253029875457, "name": "0"}, {"confidence": 0.0003661384980659932, "name": "45"}, {"confidence": 0.0001416472514392808, "name": "315"}, {"confidence": 7.309066131711006e-05, "name": "missing"}, {"confidence": 6.321164255496114e-05, "name": "270"}, {"confidence": 4.1799631844696705e-07, "name": "90"}], "color": [{"confidence": 88.98002624511719, "name": "silver-gray"}, {"confidence": 5.20803689956665, "name": "gold-beige"}, {"confidence": 2.0217337608337402, "name": "brown"}, {"confidence": 1.9371358156204224, "name": "blue"}, {"confidence": 0.7262545824050903, "name": "green"}, {"confidence": 0.4679982662200928, "name": "white"}, {"confidence": 0.3951472342014313, "name": "black"}, {"confidence": 0.1113591343164444, "name": "yellow"}, {"confidence": 0.09089621156454086, "name": "purple"}, {"confidence": 0.026119928807020187, "name": "red"}], "make": [{"confidence": 99.76302337646484, "name": "toyota"}, {"confidence": 0.15038351714611053, "name": "mazda"}, {"confidence": 0.03628182038664818, "name": "scion"}, {"confidence": 0.02035347744822502, "name": "holden"}, {"confidence": 0.007956107147037983, "name": "daihatsu"}, {"confidence": 0.003190184012055397, "name": "subaru"}, {"confidence": 0.0026486676651984453, "name": "kia"}, {"confidence": 0.0024530114606022835, "name": "nissan"}, {"confidence": 0.0019935141317546368, "name": "mitsubishi"}, {"confidence": 0.001839933218434453, "name": "skoda"}], "body_type": [{"confidence": 78.86978912353516, "name": "sedan-compact"}, {"confidence": 10.995427131652832, "name": "sedan-wagon"}, {"confidence": 4.301846981048584, "name": "suv-crossover"}, {"confidence": 2.993283987045288, "name": "van-mini"}, {"confidence": 2.629274845123291, "name": "sedan-standard"}, {"confidence": 0.1763317883014679, "name": "suv-standard"}, {"confidence": 0.011347471736371517, "name": "sedan-sports"}, {"confidence": 0.010549193248152733, "name": "tractor-trailer"}, {"confidence": 0.0033711171709001064, "name": "truck-standard"}, {"confidence": 0.002983122831210494, "name": "van-full"}], "year": [{"confidence": 70.8482894897461, "name": "2010-2014"}, {"confidence": 28.355443954467773, "name": "2015-2019"}, {"confidence": 0.7822299599647522, "name": "2005-2009"}, {"confidence": 0.012907569296658039, "name": "2000-2004"}, {"confidence": 0.00036957484553568065, "name": "1980-1984"}, {"confidence": 0.00030361919198185205, "name": "1990-1994"}, {"confidence": 0.00028349042986519635, "name": "1995-1999"}, {"confidence": 0.00013700911949854344, "name": "1985-1989"}, {"confidence": 3.2554900826653466e-05, "name": "missing"}], "make_model": [{"confidence": 45.93165969848633, "name": "toyota_corolla"}, {"confidence": 23.367666244506836, "name": "toyota_auris"}, {"confidence": 6.909913539886475, "name": "toyota_rav-4"}, {"confidence": 4.426931381225586, "name": "toyota_yaris"}, {"confidence": 2.840819835662842, "name": "scion_im"}, {"confidence": 1.5942819118499756, "name": "toyota_tarago"}, {"confidence": 1.4002506732940674, "name": "mazda_3"}, {"confidence": 1.1097708940505981, "name": "mazda_mazda"}, {"confidence": 0.8731405138969421, "name": "toyota_vitz"}, {"confidence": 0.7745351791381836, "name": "toyota_etios-cross"}]}, "matches_template": 1, "requested_topn": 10}], "credits_monthly_used": 4, "version": 2, "credits_monthly_total": 2000, "error": false, "regions_of_interest": [{"y": 0, "x": 0, "height": 4032, "width": 3024}], "credit_cost": 1}';

$(document).ready(function ($) {

    const testing = true;

    $('#btn-lpr-submit').click(function () {
        lpr_submit();
    });
    $('#testing-btn').click(function () {
        lpr_test();
    });
    $('#api_call').click(function () {
        var url = 'https://ubuxgyols2.execute-api.ap-southeast-2.amazonaws.com/prod/';
        var jwt = 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjMzZlMDIyZS02ODZkLTQ0NzMtYmRjOS1kODAyOGFjZjkyNmMiLCJuYW1lIjoiVGhlIFVuaXZlcnNpdHkgb2YgUXVlZW5zbGFuZCIsImlhdCI6MTUxNjIzOTAyMn0.k2RscL-28hP2oggWlfjGr7DULk2Hsb6fBMb9V-VuF7s';
        var vin_query = `query {
                            nevdisVINSearch_v2(vin: "XXXXXXXXXXXXXXXXX") {
                                vin
                                plate {
                                    number
                                    state
                                }
                                make
                                model
                                engine_number
                                vehicle_type
                                body_type
                                colour
                            }
                        }`;
        var plate_query = `query {
                                nevdisPlateSearch_v2(plate: "XXXXXXXXXXXXXXXXX", state:NSW) {
                                    vin
                                    plate {
                                      number
                                      state
                                    }
                                    make
                                    model
                                    engine_number
                                    vehicle_type
                                    body_type
                                    colour
                                }
                            }`;
        $.ajax({
            url: url,
            headers: {'Authorization': jwt},
            contentType: "application/json",
            data: JSON.stringify({
                query: query
            }),
            crossOrigin: true,
            type: "post",
            success: function (result) {
                console.log(result);
            },
        });
    });
});

function send_request() {
    $(document).ready(function ($) {

    });
}

function file_submit() {
    $(document).ready(function ($) {
        var file = $('#camera-file')[0].files[0];
        var upload = new Upload(file);
        // execute upload
        upload.doUpload();
    });
}

function file_upload(file) {
    var data = new FormData();
    data.append('file', file, file.name);
    data.append('upload_file', true);
    $.ajax({
        type: 'POST',
        url: 'inc/upload.php',
        dataType: 'json',
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                $('#progress-wrp').slideToggle('fast');
                myXhr.upload.addEventListener('progress', file_upload_handling, false);
            }
        },
        success: function (data) {
            if (data.result === 'success') {
                console.log(data.result);
                console.log(data.path);
                $('img-preview').attr('src', 'uploads/' + data.path);
            } else {
                console.log(data.result);
                $.each(data.errors, function (index, value) {
                    console.log(index + ': ' + value);
                });
            }
        },
        error: function (error) {
            console.log('error: ');
            console.log(error)
        },
        async: true,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
}

function file_upload_handling(event) {
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    var progress_bar_id = '#progress-wrp';
    if (event.lengthComputable) {
        percent = Math.cell(position / total * 100);
    }

    $(progress_bar_id + ' .progress-bar').css('width', +percent + '%');
    $(progress_bar_id + ' .status').text(percent + '%');
}

function lpr_test() {
    const file_input = $('#lpr-camera-file');
    if (file_input.get(0).files.length === 0) {
        console.log('no file selected');
        if (testing) {
            var send_data = JSON.parse(test_data);
        } else {
            alert('Please take a picture or select a file');
            return;
        }
    }

    // only get the first image
    var img = file_input[0].files[0];

    var reader = new FileReader();
    reader.readAsBinaryString(img);
    reader.onload = function () {
        var output = JSON.parse(test_data);

        $.extend(output, {'camera_file': reader.result});
        console.log(output);
        $.ajax({
            type: 'POST',
            url: 'inc/upload.php',
            success: function (data) {
                $('#testing-div').html(data);
            },
            data: output
        });
    };
}

function lpr_submit() {
    $(document).ready(function ($) {
// check if file is chosen
        const file_input = $('#lpr-camera-file');
        if (file_input.get(0).files.length === 0) {
            console.log('no file selected');
            alert('Please take a picture or select a file');
            return;
        }

// Open connection to api.openalpr.com
        const load_div = $('.loading_spinner');
        load_div.show();
        const secret_key = "sk_348399cb8002863d001acb77"; // sk_e553da2e80eb74dccb4e8d81
        const url_test = "https://api.openalpr.com/v2/recognize_bytes?recognize_vehicle=1&country=us&secret_key=" + secret_key;
        var params = {
            secret_key: secret_key,
            country: 'au',
            recognize_vehicle: '1'
        };
        var query = Object.keys(params)
                .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
                .join('&');
        const url = "https://api.openalpr.com/v2/recognize_bytes?" + query;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url);
        // Send POST data and display response
        var file = $('#lpr-camera-file')[0].files[0];
        var reader = new FileReader();
        reader.readAsBinaryString(file);
        reader.onload = function () {
//            console.log(btoa(reader.result));
//            $("#img-preview").attr("src",btoa(reader.result));
            xhr.send(btoa(reader.result));
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    //document.getElementById("response").innerHTML = xhr.responseText;
                    console.log(xhr.responseText);
                    var output = JSON.parse(xhr.responseText);
                    $.extend(output, {'camera_file': reader.result});
                    $.ajax({
                        type: 'POST',
                        url: 'inc/upload.php',
                        dataType: 'json',
                        success: function (data) {
                            $('#testing-div').html(data);
//                            if (data.result === 'success') {
//                                console.log(data.result);
//                                console.log(data.path);
//                                $('img-preview').attr('src', 'uploads/' + data.path);
//                            } else {
//                                console.log(data.result);
//                                $.each(data.errors, function (index, value) {
//                                    console.log(index + ': ' + value);
//                                });
//                            }
                        },
                        error: function (error) {
                            console.log('error: ');
                            console.log(error)
                        },
                        async: true,
                        data: output,
                        cache: false,
                        contentType: false,
                        processData: false,
                        timeout: 60000
                    });
                    if (!output.results || output.results.length === 0) {
                        $('#decode-result').html("No Vehicle found");
                        load_div.hide();
                    } else {
                        for (var key in output.results) {
                            // result here
                            var result = output.results[key];
                            console.log('Plate: ' + result.plate);
                            console.log('Region: ' + result.region);
                            console.log('Make: ' + result.vehicle.make[0].name);
                            console.log('Model: ' + result.vehicle.make_model[0].name);
                            console.log('Body Type: ' + result.vehicle.body_type[0].name);
                            console.log('Colour: ' + result.vehicle.color[0].name);
                            var output_string = '<table class="my-table">';
                            output_string += '<tr><th>Property</th><th>Confidence (%)</th></tr>';
                            output_string += '<tr><td>' + 'Plate: ' + result.plate + '</td>';
                            output_string += '<td>' + result.confidence + '</td></tr>';
                            output_string += '<tr><td>' + 'Region: ' + result.region + '</td>';
                            output_string += '<td>' + result.region_confidence + '</td></tr>';
                            output_string += '<tr><td>' + 'Make: ' + result.vehicle.make[0].name + '</td>';
                            output_string += '<td>' + result.vehicle.make[0].confidence + '</td></tr>';
                            output_string += '<tr><td>' + 'Model: ' + result.vehicle.make_model[0].name + '</td>';
                            output_string += '<td>' + result.vehicle.make_model[0].confidence + '</td></tr>';
                            output_string += '<tr><td>' + 'Body Type: ' + result.vehicle.body_type[0].name + '</td>';
                            output_string += '<td>' + result.vehicle.body_type[0].confidence + '</td></tr>';
                            output_string += '<tr><td>' + 'Colour: ' + result.vehicle.color[0].name + '</td>';
                            output_string += '<td>' + result.vehicle.color[0].confidence + '</td></tr>';
                            output_string += '</table>';
                        }
                        $('#decode-result').html(output_string);
                        load_div.hide();
                    }
                } else {
                    //document.getElementById("response").innerHTML = "Waiting on response...";
                    $('#decode-result').html("Waiting on response...");
                }
            };
        };
        reader.onerror = function () {
            load_div.hide();
            console.log('There were some problems sending');
        };
    });
}