<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/_db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_functions.php');


$newCharacterCreated = array_merge(autoNewCharacterCreate(), special());

$first_name = $newCharacterCreated[0];
$second_name = $newCharacterCreated[1];
$age = $newCharacterCreated[2];
$gender = $newCharacterCreated[3];
$s = $newCharacterCreated['S'];
$p = $newCharacterCreated['P'];
$e = $newCharacterCreated['E'];
$c = $newCharacterCreated['C'];
$i = $newCharacterCreated['I'];
$a = $newCharacterCreated['A'];
$l = $newCharacterCreated['L'];

if ($age >=1) {
    $link = Db::getDbLink();

    $query = 'INSERT INTO characters (firstName, secondName, gender, age, strong, power, e, c, i ,a, lucky) VALUES ("' . add_slashes($first_name) . '", "' . add_slashes($second_name) . '", "' . add_slashes($gender) . '", ' . add_slashes($age) . ', ' . add_slashes($s) . ', ' . add_slashes($p) . ', ' . add_slashes($e) . ', ' . add_slashes($c) . ', ' . add_slashes($i) . ', ' . add_slashes($a) . ', ' . add_slashes($l) . ')';
    $result = mysqli_query($link, $query);
    if ($result) {
        $_SESSION['success'] = 'Character successfully created!';
        Header('Location: /list.php');
        exit;
    } else {
        die('Fail ');
    }
}