$(document).ready(function () {
    $('#api_call').click(function () {
        $.ajax({
            url: 'https://ubuxgyols2.execute-api.ap-southeast-2.amazonaws.com/prod/',
            headers: {'Authorization' : 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjMzZlMDIyZS02ODZkLTQ0NzMtYmRjOS1kODAyOGFjZjkyNmMiLCJuYW1lIjoiVGhlIFVuaXZlcnNpdHkgb2YgUXVlZW5zbGFuZCIsImlhdCI6MTUxNjIzOTAyMn0.k2RscL-28hP2oggWlfjGr7DULk2Hsb6fBMb9V-VuF7s'},
            data: {"query": "query {\n  nevdisVINSearch_v2(vin: \"XXXXXXXXXXXXXXXXX\") {\n    vin\n    plate {\n      number\n      state\n    }\n    make\n    model\n  }\n}"},
            success: function (result) {
                console.log(result);
            },
        });
//        var url = 'https://ubuxgyols2.execute-api.ap-southeast-2.amazonaws.com/prod/';
//        var jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjMzZlMDIyZS02ODZkLTQ0NzMtYmRjOS1kODAyOGFjZjkyNmMiLCJuYW1lIjoiVGhlIFVuaXZlcnNpdHkgb2YgUXVlZW5zbGFuZCIsImlhdCI6MTUxNjIzOTAyMn0.k2RscL-28hP2oggWlfjGr7DULk2Hsb6fBMb9V-VuF7s';
//        var xhr = new XMLHttpRequest();
//        if ('withCredentials' in xhr) {
//            xhr.open("POST", url, true);
//        } else {
//            xhr = null;
//            // CORES not supported
//            console.log("Browser not supported");
//        }
//        
//        if (xhr) {
//            xhr.onload = function() {
//                var response = xzhr.responseText;
//                
//            };
//            
//            xhr.onerror = function() {
//                console.log("something went wrong");
//            }
//        }
        
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