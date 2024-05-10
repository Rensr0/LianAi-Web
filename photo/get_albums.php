<?php
$albums = glob("img/*", GLOB_ONLYDIR);
foreach ($albums as $album) {
    $albumName = basename($album);
    echo "<option value='$albumName'>$albumName</option>";
}
?>
