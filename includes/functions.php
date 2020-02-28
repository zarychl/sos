<?php
function getAllCars()
{   
    GLOBAL $conn;
    $cars = array();
    $result = mysqli_query( $conn , "SELECT * from cars;");
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cars,$row);
    }
    return $cars;
}

function getCar($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * from cars WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        $car = $row;
    }
    return $car;
}

function getStaff($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * from users WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        $staff = $row;
    }
    return $staff;
}

function getPrzejazdyByKarta($karta)
{
    GLOBAL $conn;
    $przejazdy = array();
    $result = mysqli_query($conn, "SELECT * FROM `przejazdy` WHERE `idKarty` = $karta ORDER BY `id`;");
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($przejazdy,$row);
    }
    return $przejazdy;
}

function hasCardUnfinishedPrzejazd($id)
{
    GLOBAL $conn;
    $result = mysqli_query($conn, 'SELECT COUNT(*) FROM `przejazdy` WHERE `idKarty` = '. $id .' AND `przyjazdTime` = "";');
    while ($row = mysqli_fetch_assoc($result)) {
        $has = $row['COUNT(*)'];
    }
    return $has;
}

function getCarIdFromPrzejazd($id)
{
    GLOBAL $conn;
    $result = mysqli_query($conn, "SELECT k.car_id as id FROM karty k INNER JOIN przejazdy p on p.idKarty = k.id WHERE p.id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        $carid = $row['id'];
    }
    return $carid;

}

function getCardUnfinishedPrzejazd($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` WHERE `idKarty` = $id AND `przyjazdTime` = '';");
    while ($row = mysqli_fetch_assoc($result)) {
        $przejazd = $row;
    }
    return $przejazd;
}

function finishPrzejazd($id, $time, $mileage)
{
    GLOBAL $conn;
    $carid = getCarIdFromPrzejazd($id);
    $result = mysqli_query( $conn , "UPDATE `przejazdy` SET `przyjazdTime` = '$time:00', `przyjazdPrzebieg` = '$mileage' WHERE `przejazdy`.`id` = $id;");
    $result = mysqli_query( $conn , "UPDATE `cars` SET `przebieg` = $mileage where `cars`.`id` = $carid;");

    return 1;
}

function initPrzejazd($id,$skad,$dokad, $time, $mileage)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "INSERT INTO `przejazdy` (`idKarty`, `skad`, `dokad`, `wyjazdTime`, `WyjazdPrzebieg`) VALUES ('$id','$skad','$dokad','$time','$mileage')");

    return 1;
}

function getAllStaff()
{   
    GLOBAL $conn;
    $staff = array();
    $result = mysqli_query( $conn , "SELECT `id`,`imie`,`nazwisko` from users;");
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($staff,$row);
    }
    return $staff;
}

function dodajKarte($data, $przychodnia, $lekarz, $car_id, $zaloga_id1, $zaloga_id2)
{
    GLOBAL $conn;
    mysqli_query( $conn , "INSERT INTO `karty` (`data`, `przychodnia`, `lekarz`, `car_id`, `zaloga_id1`, `zaloga_id2`) VALUES ('$data', '$przychodnia', '$lekarz', '$car_id', '$zaloga_id1', '$zaloga_id2');");
    mysqli_query( $conn , "UPDATE `cars` SET `czyWBazie` = '0' WHERE `cars`.`id` = $car_id;");

    $result = mysqli_query($conn, "SELECT id FROM `karty` ORDER BY ID DESC LIMIT 1;");
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
    }

    return $id;
}

function getKarta($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * from karty WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        $karta = $row;
    }
    return $karta;
}

function finilizeCard($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "UPDATE `karty` SET `zakonczony` = '1' WHERE `karty`.`id` = $id;");
    
    $card =  getKarta($id);

    mysqli_query( $conn , "UPDATE `cars` SET `czyWBazie` = '1' WHERE `cars`.`id` = ". $card['car_id'] .";");
    return 1;
}

function getUserName($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT `imie` from users WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['imie'];
    }
    return $name;
}

function isUserLoggedIn()
{
    if(isset($_SESSION['userID']))
        return 1;
    else
        return 0;
}
?>