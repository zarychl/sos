<?php
define("OIL_ALERT_DAYS", 7);
define("OIL_ALERT_KM", 1000);
define("UBEZ_ALERT_DAYS", 7);
define("PRZEGLAD_ALERT_DAYS", 7);

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
function getCarsKonczaceSie()
{
    GLOBAL $conn;
    $rzeczy = array();
    $result = mysqli_query( $conn , "SELECT count(*) as 'count' FROM `cars` where `olejDoKm` - `przebieg` < ". OIL_ALERT_KM .";");
    while ($row = mysqli_fetch_assoc($result)) {
        $rzeczy['o_km'] = $row['count'];
    }

    $result = mysqli_query( $conn , "SELECT count(*) as 'count' from cars where DATEDIFF(olejDo,CURDATE()) < ". OIL_ALERT_DAYS .";");
    while ($row = mysqli_fetch_assoc($result)) {
        $rzeczy['o'] = $row['count'];
    }

    $result = mysqli_query( $conn , "SELECT count(*) as 'count' from cars where DATEDIFF(przegladDo,CURDATE()) < ". PRZEGLAD_ALERT_DAYS .";");
    while ($row = mysqli_fetch_assoc($result)) {
        $rzeczy['p'] = $row['count'];
    }

    $result = mysqli_query( $conn , "SELECT count(*) as 'count' from cars where DATEDIFF(ubezDo,CURDATE()) < ". UBEZ_ALERT_DAYS .";");
    while ($row = mysqli_fetch_assoc($result)) {
        $rzeczy['u'] = $row['count'];
    }
    return $rzeczy;
}
function getOilMileageLeft($carid)
{
    $car = getCar($carid);
    if(isset($car['olejDoKm']))
        return $car['olejDoKm'] - $car['przebieg'];
    else
        return "err";
}

function getUsedCarsAnytime()
{
    GLOBAL $conn;
    $cars = array();
    $result = mysqli_query( $conn , "SELECT DISTINCT c.nazwa,c.tablica,c.id FROM karty k INNER JOIN cars c ON c.id = k.car_id;");
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cars,$row);
    }
    return $cars;
}

function getCarUsedDates($id)
{
    $query = "SELECT DISTINCT SUBSTRING(data, 1, 7) AS data FROM karty WHERE car_id = $id;";
    GLOBAL $conn;
    $dates = array();
    $result = mysqli_query( $conn , $query);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($dates,$row);
    }
    return $dates;
}

function getCardLastLocation($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` where idKarty = $id order by id desc limit 1;");
    if(mysqli_num_rows($result) == 0)
    {
        return "-";
    }
    while ($row = mysqli_fetch_assoc($result)) {
        if($row['przyjazdTime'] != 0)
        {
            $loc = $row['dokad'];
        }
        else $loc = $row['skad'];
    }
    return $loc;
}
function getStaff($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * from users WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
}

function countUserWyjazdy($id)
{
    $query = "SELECT count(*) as ilosc FROM `karty` where zaloga_id1 = $id or zaloga_id2 = $id";
    GLOBAL $conn;
    $result = mysqli_query( $conn , $query);
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['ilosc'];
    }
}

function getCardFueling($cardid)
{
    GLOBAL $conn;
    $fueling = array();
    $result = mysqli_query( $conn , "SELECT * from tankowania where idKarty = $cardid;");
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($fueling,$row);
    }
    return $fueling;
}
function hasCardFueling($cardid)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * from tankowania where idKarty = $cardid;");
    if(mysqli_num_rows($result) > 0)
        return 1;
    else
        return 0;
}
function getUserAdminLvl($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT admin_lvl from users WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['admin_lvl'];
    }
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
    $result = mysqli_query($conn, 'SELECT COUNT(*) FROM `przejazdy` WHERE `idKarty` = '. $id .' AND `przyjazdTime` IS NULL;');
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
function displayTimeDiff($time1H, $time1M, $time2H, $time2M)
{
    $time1 = mktime($time1H,$time1M);//wyjazd
    $time2 = mktime($time2H,$time2M);//przyjazd

    if($time1 > $time2)// przejście przeez północ
    {
        $midnight = mktime(24,00);
        $time_1_m = $midnight - $time1;
        return date("H:i",$time2+$time_1_m);
    }
    else
    {
        return date("H:i",$time2-$time1-3600);
    }
}
function getCardUnfinishedPrzejazd($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` WHERE `idKarty` = $id AND `przyjazdTime` IS NULL;");
    while ($row = mysqli_fetch_assoc($result)) {
        $przejazd = $row;
    }
    return $przejazd;
}

function hadCardDysponent($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT id FROM `karty` WHERE `id` = $id AND (`przychodnia` = '' OR `lekarz` = '')");
    while ($row = mysqli_fetch_assoc($result)) {
        return mysqli_num_rows($result);
    }
}

function updateCardDysponent($id, $p, $l)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "UPDATE `karty` SET `przychodnia` = '$p', `lekarz` = '$l'");
    return 1;
}

function getPacjentByKarta($id)
{
    GLOBAL $conn;
    $pacjenty = array();
    $result = mysqli_query( $conn , "SELECT DISTINCT pacjent FROM przejazdy where pacjent != '' AND `idKarty` = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($pacjenty,$row);
    }
    return $pacjenty;
}
function getKartaLastPacjent($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT `pacjent` FROM `przejazdy` WHERE idKarty = $id ORDER BY id desc LIMIT 1;");
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['pacjent'];
    }

}

function hadKartaPacjent($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT `pacjent` FROM `przejazdy` WHERE idKarty = $id ORDER BY id desc LIMIT 1;");
    while ($row = mysqli_fetch_assoc($result)) {
        return mysqli_num_rows($result);
    }
}

function getTrasa($id)
{
    GLOBAL $conn;
    $punkty = array();
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` WHERE idKarty = $id;");
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        if($i==0)
        {
            array_push($punkty,$row['skad']);
            array_push($punkty,$row['dokad']);
            $i++;
            continue;
        }
        $i++;
        array_push($punkty,$row['dokad']);
    }
    return $punkty;
}
function firstPrzejazdOnCard($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` WHERE idKarty = $id ORDER BY id LIMIT 1 ");
    while ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
}
function editPrzejazd($id,$skad,$dokad,$wyjazdTime,$przyjazdTime,$wyjazdPrzebieg,$przyjazdPrzebieg,$pacjent)
{
    GLOBAL $conn;
    if($przyjazdTime == "" && $przyjazdPrzebieg == "")
    {
        $query = "
        UPDATE `przejazdy`
        SET 
        `skad` = '$skad', 
        `dokad` = '$dokad',
        `wyjazdTime` = '$wyjazdTime',
        `przyjazdTime` = NULL,
        `wyjazdPrzebieg` = '$wyjazdPrzebieg',
        `przyjazdPrzebieg` = NULL,
        `pacjent` = '$pacjent'
        WHERE `przejazdy`.`id` = $id; 
        ";
    }
    else
    {
    $query = "
    UPDATE `przejazdy`
     SET 
     `skad` = '$skad', 
     `dokad` = '$dokad',
     `wyjazdTime` = '$wyjazdTime',
     `przyjazdTime` = '$przyjazdTime',
     `wyjazdPrzebieg` = '$wyjazdPrzebieg',
     `przyjazdPrzebieg` = '$przyjazdPrzebieg',
     `pacjent` = '$pacjent'
      WHERE `przejazdy`.`id` = $id; 
    ";
    }
    $result = mysqli_query( $conn , $query);
}
function getPrzejazd($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` WHERE id = $id;");
    while ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
}
function lastPrzejazdOnCard($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "SELECT * FROM `przejazdy` WHERE idKarty = $id ORDER BY id desc LIMIT 1 ");
    while ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
}
function finishPrzejazd($id, $time, $mileage)
{
    GLOBAL $conn;
    $carid = getCarIdFromPrzejazd($id);
    $result = mysqli_query( $conn , "UPDATE `przejazdy` SET `przyjazdTime` = '$time:00', `przyjazdPrzebieg` = '$mileage' WHERE `przejazdy`.`id` = $id;");
    $result = mysqli_query( $conn , "UPDATE `cars` SET `przebieg` = $mileage where `cars`.`id` = $carid;");

    return 1;
}

function initPrzejazd($id,$skad,$dokad, $time, $mileage, $pacjent = 0)
{
    GLOBAL $conn;
    if(!$pacjent)
        $result = mysqli_query( $conn , "INSERT INTO `przejazdy` (`idKarty`, `skad`, `dokad`, `wyjazdTime`, `WyjazdPrzebieg`) VALUES ('$id','$skad','$dokad','$time','$mileage')");
    else
        $result = mysqli_query( $conn , "INSERT INTO `przejazdy` (`idKarty`, `skad`, `dokad`, `wyjazdTime`, `WyjazdPrzebieg`, `pacjent`) VALUES ('$id','$skad','$dokad','$time','$mileage', '$pacjent')");

    return 1;
}
function daysToOilReplace($carid)
{
    $car = getCar($carid);
    if(!empty($car['olejDo']))
    {
        $date1 = strtotime($car['olejDo']);
        $now = strtotime("now");
        return ceil(($date1 - $now)/(3600*24));
    }
    else return "err";

}
function getTodayEvents()
{
    GLOBAL $conn;
    $today = date('Y-m-d');
    $events = array();
    $result = mysqli_query( $conn , "SELECT * FROM `events` where date = '$today';");
    
    if(mysqli_num_rows($result) == 0) return 0;

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($events,$row);
    }

    return $events;
}

function getAllStaff()
{   
    GLOBAL $conn;
    $staff = array();
    $result = mysqli_query( $conn , "SELECT `id`,`imie`,`nazwisko`,`ratownik`, `mail`, `admin_lvl` from users;");
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

function getAllCards()
{
    GLOBAL $conn;
    $cards = array();
    $query = '
    SELECT k.zakonczony, k.id ,k.data, k.przychodnia, k.lekarz, c.nazwa, c.tablica, k.zaloga_id1, k.zaloga_id2 
    FROM karty k 
    INNER JOIN cars c ON c.id = k.car_id
    ORDER BY k.id DESC
    ';
    $result = mysqli_query( $conn , $query);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cards,$row);
    }
    return $cards;
}

function finilizeCard($id)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "UPDATE `karty` SET `zakonczony` = '1' WHERE `karty`.`id` = $id;");
    
    $card =  getKarta($id);

    mysqli_query( $conn , "UPDATE `cars` SET `czyWBazie` = '1' WHERE `cars`.`id` = ". $card['car_id'] .";");
    return 1;
}

function addFueling($cardid, $fuel, $money, $fv)
{
    GLOBAL $conn;
    $result = mysqli_query( $conn , "INSERT INTO `tankowania` (`idKarty`, `litry`, `koszt`, `faktura`) VALUES ('$cardid', '$fuel', '$money', '$fv');");
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

function getEventsByDay($date)
{
    GLOBAL $conn;
    $events = array();
    $query = "SELECT * FROM `events` WHERE date = '$date' ORDER BY `time`";
    $result = mysqli_query( $conn , $query);
    if(mysqli_num_rows($result) == 0) return 0;
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($events,$row);
    }
    return $events;
}

function addEvent($date, $name, $desc, $time)
{
    GLOBAL $conn;
    $query = "
    INSERT INTO `events`(`nazwa`, `opis`, `time`, `date`) VALUES ('$name','$desc','$time','$date');
    ";
    $result = mysqli_query( $conn , $query);

    if($result)
        mysqli_query( $conn , "INSERT INTO `log`(`name`, `who`) VALUES ('Dodano event: $name, $date $time',". $_SESSION['userID'] .")");
}

function deleteEvent($id)
{
    GLOBAL $conn;
    $query = "
    DELETE FROM `events` WHERE `events`.`id` = $id
    ";
    $result = mysqli_query( $conn , $query);

    if($result)
        mysqli_query( $conn , "INSERT INTO `log`(`name`, `who`) VALUES ('Usunięto event o id: $id',". $_SESSION['userID'] .")");
}
?>