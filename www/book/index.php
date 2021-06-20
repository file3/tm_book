<?php
require('src/GetData.php');

$gd = new GetData();

if (is_cli()) {
    $gd->load();
} else {
    $gd->view();
}
?>
