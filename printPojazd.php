<?php
require_once("includes/db.php");
require_once("includes/functions.php");
session_start();
mysqli_set_charset($conn,"utf8");

if(!isUserLoggedIn())
{
    header("Location: /login.php");
}
if(!isset($_GET['m']) || !isset($_GET['y']) || !isset($_GET['car']))
{
    header("Location: /index.php");
}
else
{
    if($_GET['m'] < 10)
        $miesiacString = "0" . $_GET['m'];
    else
        $miesiacString = $_GET['m'];
}

$query = 'SELECT * FROM `karty` WHERE `car_id` = '. $_GET['car'] .' AND data LIKE "' . $_GET['y'] . '-' . $miesiacString . '-__";';

GLOBAL $conn;
$result = mysqli_query($conn, $query);

$firstCard = mysqli_fetch_assoc($result);
mysqli_data_seek($result, 0);

    $p = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($p,$row);
}

$lastCard = end($p);

$months = array('','Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień', 'Październik', 'Listopad', 'Grudzień');

$wyjazdyCount = mysqli_num_rows($result);
$totalFueling = 0;

$query = 'SELECT litry FROM tankowania t INNER JOIN karty k ON t.idKarty = k.id WHERE k.car_id = '. $_GET['car'] .';';

GLOBAL $conn;
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $totalFueling += $row['litry'];
}

$c = getCar($_GET['car']);

if($totalFueling == 0)
    $totalFueling = "-";
?>
<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
<head>
    <script
			  src="https://code.jquery.com/jquery-3.4.1.min.js"
			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			  crossorigin="anonymous">
              </script>
    <script src="includes/html2pdf.js"></script>
    <script>
    $(document).ready(function($) {
        const element = document.getElementById("page2");
        // Choose the element and save the PDF for our user.
        html2pdf()
            .set({ html2canvas: { scale: 4 }, filename: 'kartaDrogowa.pdf', image: { type: 'jpeg', quality: 1 }, jsPDF: { unit: 'cm', format: 'A4', orientation: 'landscape' }})
          .from(element)
          .save();
    });
    </script>
    <style>
        /* http://meyerweb.com/eric/tools/css/reset/ 
    v2.0 | 20110126
    License: none (public domain)
    */

    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed, 
    figure, figcaption, footer, header, hgroup, 
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }
    /* HTML5 display-role reset for older browsers */
    article, aside, details, figcaption, figure, 
    footer, header, hgroup, menu, nav, section {
        display: block;
    }
    body {
        line-height: 1;
        font-family:Calibri;
    }
    ol, ul {
        list-style: none;
    }
    blockquote, q {
        quotes: none;
    }
    blockquote:before, blockquote:after,
    q:before, q:after {
        content: '';
        content: none;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    @print{
    @page :footer {color: #fff }
    @page :header {color: #fff}
    }  
    @page { margin: 0; }
    page 
    {
        display: block;
        size: auto;   /* auto is the initial value */
        margin: 0mm;
    }
    page[size="A4"] 
    {  
        width: 29.7cm;
        height: 41cm;
        /* border:1px solid gray; */
    }
    table{
        text-align:left;
    }
    h1
    {
        font-weight:bold;
        text-align:center;
        font-size:22pt;
        margin-top: 20px;
    }
    h2
    {
        font-size: 16pt;
        font-weight: bold;
        text-align: center;
        width: 100%;
        margin-top: 40px;
    }
    b
    {
        font-weight:bold;
    }
    .tg tr th
    {
        background:#bbb;
    }
    .tg tr th, .tg tr td
    {
        padding:5px;
        border:1px solid black;
    }
    .tg
    {
        margin:0 auto;
        margin-top:20px;
        max-width: 85%;
    }
    </style>
</head>
<body>
    <page id="page2" size="A4" orientation="landscape">
    <div id="page" style="">
        <h2 style="top:10px">KARTA DROGOWA <?php echo $miesiacString . "/" . $_GET['y']; ?></h2>
        <table style="font-size:14pt;width: 80%;margin:0 auto;margin-top:50px;">
            <tr>
                <td style="padding:5px;"><b>Pojazd: </b><?php echo $c['nazwa']; ?></td><td style="padding:5px;"><b>Miesiąc: </b><?php echo $months[$_GET['m']] . " " . $_GET['y']; ?></td>
            </tr>
            <tr>
                <td style="padding:5px;"><b>Nr rejestracyjny: </b><?php echo $c['tablica']; ?></td>
            </tr>
        </table>

        <table class="tg">
            <tr>
                <th class="tg-cly1">Data</th>
                <th class="tg-cly1">Załoga</th>
                <th class="tg-cly1">Trasa</th>
                <th class="tg-cly1">Godzina<br>wyjazdu</th>
                <th class="tg-0lax">Godzina<br>powrotu</th>
                <th class="tg-0lax">Łączny<br>czas</th>
                <th class="tg-0lax">Stan licznika<br>wyjazd</th>
                <th class="tg-0lax">Stan licznika<br>powrót</th>
                <th class="tg-0lax">Dystans</th>
                <th class="tg-0lax">Tankowanie</th>
                <th class="tg-0lax">Dysponent</th>
            </tr>
            
            <?php
                foreach($p as $key => $value)
                {

                    $query = 'SELECT litry FROM tankowania t INNER JOIN karty k ON t.idKarty = k.id WHERE k.car_id = '. $_GET['car'] .' AND k.id = '. $p[$key]['id'] .';';

                    GLOBAL $conn;
                    $fueling = 0;
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $fueling += $row['litry'];
                    }
                    if($fueling == 0)
                        $fueling = "-";

                    $query = 'SELECT * FROM przejazdy WHERE idKarty = '. $p[$key]['id'] .';';

                    $result = mysqli_query($conn, $query);
                    $firstPrzejazd = mysqli_fetch_assoc($result);
                    mysqli_data_seek($result, 0);

                    $k = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        array_push($k,$row);
                    }
                    $lastPrzejazd = end($k);
                
                    $time1 = explode(":" , $lastPrzejazd['przyjazdTime']);
                    $time2 = explode(":" , $firstPrzejazd['wyjazdTime']);
                    $zaloga1 = getStaff($p[$key]['zaloga_id1']);
                    $trasa = getTrasa($p[$key]['id']);
                    $przebieg1 = intval($lastPrzejazd['przyjazdPrzebieg']);
                    $przebieg2 = intval($firstPrzejazd['wyjazdPrzebieg']);
                    if($p[$key]['lekarz'] == "")
                    {
                        $lekarz = "";
                    }
                    else
                    {
                        $lekarz = ", ". $p[$key]['lekarz'];
                    }
                    $przebieg_roznica = $przebieg1 - $przebieg2;
                    if($p[$key]['zaloga_id2'] == "")
                        $zaloga2 = "-";
                    else
                        $zaloga2 = getStaff($p[$key]['zaloga_id2']);
                    echo '
                    <tr>
                    <td class="tg-0lax" rowspan="2">'. date("d.m.Y",strtotime($p[$key]['data'])) .'</td>
                    <td class="tg-0lax">'. $zaloga1['nazwisko'] . " " . $zaloga1['imie'] .'</td>';

                    echo '<td class="tg-0lax" rowspan="2">';
                    $numItems = count($trasa);
                        $i = 0;
                        while($i < $numItems)
                        {
                            echo $trasa[$i];
                            if(++$i != $numItems) {
                                echo ' -> ';
                            }
                        }
                    echo '</td>';

                    echo '<td class="tg-0lax" rowspan="2">'. date("H:i", mktime($time2[0],$time2[1])) .'</td>
                    <td class="tg-0lax" rowspan="2">'. date("H:i", mktime($time1[0],$time1[1])) .'</td>
                    <td class="tg-0lax" rowspan="2">'. displayTimeDiff($time2[0],$time2[1],$time1[0],$time1[1]) .'</td>
                    <td class="tg-0lax" rowspan="2">'. $przebieg2 .'</td>
                    <td class="tg-0lax" rowspan="2">'. $przebieg1 .'</td>
                    <td class="tg-0lax" rowspan="2">'. $przebieg_roznica .'</td>
                    <td class="tg-0lax" rowspan="2">'. $fueling .' L</td>
                    <td class="tg-0lax" rowspan="2">'. $p[$key]['przychodnia'] . $lekarz .'</td>
                    </tr>
                    <tr>
                    ';
                    if($zaloga2['nazwisko'] == "")
                    {
                        echo '<td class="tg-0lax">&nbsp;</td>';
                    }
                    else
                    echo '<td class="tg-0lax">'. $zaloga2['nazwisko'] . " " . $zaloga2['imie'] .'</td></tr>';
                }
                ?>
        </table>


        <table style="width:85%;margin:0 auto;margin-top:30px;padding:5px;">
        <?php
        $query = 'SELECT * FROM przejazdy p INNER JOIN karty k ON k.id = p.idKarty where k.car_id = ' . $_GET['car'] . ';';

        $result = mysqli_query($conn, $query);
        $fm = mysqli_fetch_assoc($result);

        mysqli_data_seek($result, 0);
        
        $temp = array();
        while($row = mysqli_fetch_assoc($result))
        {
            array_push($temp, $row);
        }
        $lm = end($temp);
        ?>
            <tr>
                <td><b>Łączna liczba wyjazdów:</b> <?php echo $wyjazdyCount; ?></td>
            </tr>
            <tr>
                <td><b>Stan licznika początkowy:</b> <?php echo $fm['wyjazdPrzebieg']; ?></td><td><b>Zatankowane paliwo:</b> <?php echo $totalFueling; ?> L</td>
            </tr>
            <tr>
                <td><b>Stan licznika końcowy:</b> <?php echo $lm['przyjazdPrzebieg'] ?></td>
            </tr>
            <tr>
                <td><b>Przejechany dystans:</b> <?php echo $lm['przyjazdPrzebieg'] - $fm['wyjazdPrzebieg']; ?></td>
            </tr>
        </table>
        </div>
        <p style="page-break-before: always">
        <div id="page" style="border-top: 1px dotted grey;">
        Super partia
        </div>
    </page>

</div>

</body>
</html>