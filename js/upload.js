var Upload = function (file) {
    this.file = file;
};

Upload.prototype.getType = function () {
    return this.file.type;
};
Upload.prototype.getSize = function () {
    return this.file.size;
};
Upload.prototype.getName = function () {
    return this.file.name;
};
Upload.prototype.doUpload = function () {
    var that = this;
    var formData = new FormData();

    // add assoc key values, this will be posts values
    formData.append("file", this.file, this.getName());
    formData.append("upload_file", true);

    $.ajax({
        type: "POST",
        url: "inc/upload.php",
        dataType: 'json',
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                $("#progress-wrp").slideToggle("fast");
                myXhr.upload.addEventListener('progress', that.progressHandling, false);
            }
            return myXhr;
        },
        success: function (data) {
            if (data.result === "success") {
                console.log(data.result);
                console.log(data.path);
                $("#img-preview").attr("src", "uploads/" + data.path);
            } else {
                console.log(data.result);
                $.each(data.errors, function (index, value) {
                    console.log(index + ": " + value);
                });
            }
        },
        error: function (error) {
            // handle error
            console.log('error: ');
            console.log(error);
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
};

Upload.prototype.progressHandling = function (event) {
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    var progress_bar_id = "#progress-wrp";
    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }
    // update progressbars classes so it fits your code
    $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
    $(progress_bar_id + " .status").text(percent + "%");
};