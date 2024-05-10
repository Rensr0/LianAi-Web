$(document).ready(function() {
    // 加载现有相册列表
    loadAlbums();

    // 异步创建相册
    function createAlbum() {
        var albumName = $("#album_name").val();
        $.post("create_album.php", { album_name: albumName }, function(data) {
            alert(data); // 显示相册创建结果
            loadAlbums(); // 重新加载相册列表
        });
    }

    // 异步加载现有相册列表
    function loadAlbums() {
        $.get("get_albums.php", function(data) {
            $("#album").html(data); // 填充相册下拉列表
        });
    }

    // 异步上传图片
    function uploadFiles() {
        var albumName = $("#album").val();
        var formData = new FormData();
        var files = $("#fileInput")[0].files;

        for (var i = 0; i < files.length; i++) {
            formData.append("files[]", files[i]);
        }
        formData.append("album", albumName);

        $.ajax({
            url: "upload.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                alert(data); // 显示上传结果
            }
        });
    }
});
