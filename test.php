<?php
foreach ($_POST as $value){
    $q = $value;
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_db.php');
$link = Db::getDbLink();
$s_characters = 'SELECT * FROM characters where id = '.$q[0];
$r_characters = mysqli_query($link, $s_characters);
$row = mysqli_fetch_assoc($r_characters);
foreach ($row as $values){
    echo $values . PHP_EOL;
}

