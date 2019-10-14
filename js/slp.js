$(document).ready(function ($) {

    $('.lpr-check').change(function () {
        if ($('.lpr-check:checked').length == $('.lpr-check').length) {
            $('#rego-submit').attr('disabled', false);
            $('#rego-submit').parent().removeClass('ui-state-disabled');
        } else {
            $('#rego-submit').attr('disabled', true);
            $('#rego-submit').parent().addClass('ui-state-disabled');
        }
    });

    $('.loading-on').click(function () {
        $.mobile.loading("show", {
            text: "Loading please wait...",
            textVisible: true,
            theme: "a",
            html: ""
        });
    });

    $('#gps-btn').click(function (e) {
        e.preventDefault();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(find_closest_zone);
        }
    });
});

$(document).on('submit', '.details-form', function (e) {
    e.preventDefault();

    var form = $(this);

    var func = form.find('input[name="func"]').val();
    var data = form.serialize();
    $.post('inc/ajax.php', data, function (output, status) {
        switch (func) {
            case 'save':
                console.log(output);
                // redirect
                window.location.replace('index.php');
                break;
            default:
                console.log('Unknown function: ' + func);
        }
    });
});

function find_closest_zone(position) {
    var x = position.coords.longitude;
    var y = position.coords.latitude;

    var data = {
        func: 'gps',
        x: x,
        y: y
    }

    $.post('inc/ajax.php', data, function (output, status) {
        $('#select-zones').html(output);
    });
}