$(document).ready(function () {
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
                    ;
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

function lpr_submit() {
    $(document).ready(function ($) {
        // Open connection to api.openalpr.com
        var load_div = $('.loading_spinner');
        load_div.show();
        var secret_key = "sk_348399cb8002863d001acb77";
        var url_test = "https://api.openalpr.com/v2/recognize_bytes?recognize_vehicle=1&country=us&secret_key=" + secret_key;

        var params = {
            secret_key: 'sk_e553da2e80eb74dccb4e8d81',
            country: 'au',
            recognize_vehicle: '1'
        };
        var query = Object.keys(params)
                .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
                .join('&');
        var url = "https://api.openalpr.com/v2/recognize_bytes?" + query;
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
                    var output = JSON.parse(xhr.responseText);
                    if (!output.results || output.results.length === 0) {
                        $('#decode-result').html("No Vehicle found");
                        load_div.hide();
                    } else {
                        for (var key in output.results) {
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
            }
        };
        reader.onerror = function () {
            load_div.hide();
            console.log('There were some problems sending');
        };
    });
}