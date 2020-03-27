<?php
require_once("includes/db.php");
require_once("includes/functions.php");
session_start();
mysqli_set_charset($conn,"utf8");

if(!isUserLoggedIn())
{
    header("Location: login.php");
}
if(isset($_GET['idkarty']))
{
    $k = getKarta($_GET['idkarty']);
    $p = getPrzejazdyByKarta($_GET['idkarty']);
    $f = getCardFueling($_GET['idkarty']);
    $c = getCar($k['car_id']);
    $staff1 = getStaff($k['zaloga_id1']);
    if($k['zaloga_id2'] != -1)
        $staff2 = getStaff($k['zaloga_id2']);
    if(hadKartaPacjent($k['id']))
    {
        $pac = getPacjentByKarta($k['id']);
    }
    $trasa = getTrasa($k['id']);
    echo "<script>var idkarty = ". $k['id'] ."</script>";
}
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
        const element = document.getElementById("page");
        // Choose the element and save the PDF for our user.
        html2pdf()
            .set({ html2canvas: { scale: 4 }, filename: 'karta_'+idkarty+'.pdf', image: { type: 'jpeg', quality: 1 }})
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
        background: white;
        display: block;
        size: auto;   /* auto is the initial value */
        margin: 0mm;
        
    }
    page[size="A4"] 
    {  
        width: 21cm;
        height: 29.7cm;
        border:1px solid gray;
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
    .tg-0pky, .tg-cly1{
        padding:5px;
    }
    .tg, .tg-0pky, .tg-cly1
    {
        border:1px solid black;
    }
    </style>
</head>
<body>
    <page id="page" size="A4">
        <h1>KARTA WYJAZDU<br>ZESPOŁU TRANSPORTU MEDYCZNEGO</h1>
        <div style="float:left;width: 40%;display:block;">
            <table style="display: inline-block;font-size:14pt;margin-top:50px;margin-left:50px;">
                <tr>
                    <th style="font-weight:bold">
                    Data wyjazdu:
                    </th>
                </tr>
                <tr>
                    <td style="font-size:16pt;">
                    <?php echo date("d.m.Y",strtotime($k['data'])); ?>
                    </td>
                </tr>
            </table>
            <br>
            <table style="display: inline-block;font-size:14pt;margin-top:50px;margin-left:50px;">
                <tr>
                    <th style="font-weight:bold">
                    Pojazd:
                    </th>
                </tr>
                <tr>
                    <td style="font-size:16pt;">
                    <?php echo $c['nazwa']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13pt;">
                    <?php echo $c['tablica']; ?>
                    </td>
                </tr>
            </table>
<br>
            <table style="display: inline-block;font-size:14pt;margin-top:50px;margin-left:50px;">
                <tr>
                    <th style="font-weight:bold">
                    Załoga:
                    </th>
                </tr>
                <tr>
                    <td style="font-size:14pt;">
                    <?php if(!empty($staff1)) echo $staff1['nazwisko'] . " " . $staff1['imie']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14pt;">
                    <?php 
                    if(isset($staff2))
                        echo $staff2['nazwisko'] . " " . $staff2['imie'];
                    ?>
                    </td>
                </tr>
            </table>
        </div>
        <div style="float:right;width: 40%;display:block;">
            <table style="display: inline-block;font-size:14pt;margin-top:50px;margin-left:50px;width:220px;">
                <tr>
                    <th style="font-weight:bold">
                    Dysponent:
                    </th>
                </tr>
                <tr>
                    <td style="font-size:16pt;">
                    <?php echo $k['przychodnia']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:11pt;">
                    <?php 
                    if($k['lekarz'] != "")
                        echo $k['lekarz'];
                    ?>
                    </td>
                </tr>
            </table>
            <table style="display: inline-block;font-size:14pt;margin-top:50px;margin-left:50px;">
                <tr>
                    <th style="font-weight:bold">
                    Pacjent:
                    </th>
                </tr>
                    <?php
                    foreach($pac as $key => $value)
                    {
                        echo '<tr><td style="font-size:14pt;">'. $pac[$key]['pacjent'] .'</td></tr>';
                    }
                    ?>
            </table>
        </div>
        <div style="margin-top:50px;display: inline-block;width: 100%;">
            <table style="margin-left:50px;float:left;" class="tg">
                <tr>
                    <th bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">Trasa<br></th>
                    <th bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">Stan<br>licznika<br>wyjazd<br></th>
                    <th bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">Stan<br>licznika<br>powót</th>
                    <th bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">dystans</th>
                </tr>
                <tr>
                    <td style="width:150px" class="tg-0pky" rowspan="3">
                    <?php
                        $numItems = count($trasa);
                        $i = 0;
                        while($i < $numItems)
                        {
                            echo $trasa[$i];
                            if(++$i != $numItems) {
                                echo ' -> ';
                            }
                        }
                        $first = firstPrzejazdOnCard($k['id']);
                        $last = lastPrzejazdOnCard($k['id']);
                    ?>
                    </td>
                    <td class="tg-0pky"><?php echo number_format($first['wyjazdPrzebieg'], 0, "", " "); ?></td>
                    <td class="tg-0pky"><?php echo number_format($last['przyjazdPrzebieg'], 0, "", " "); ?></td>
                    <td class="tg-0pky"><?php echo number_format($last['przyjazdPrzebieg'] - $first['wyjazdPrzebieg'], 0, "", " "); ?></td>
                </tr>
                <tr>
                    <td bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">godzina<br>wyjazdu</td>
                    <td bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">godzina<br>powrotu</td>
                    <td bgcolor="#d2d2d2" style="background:#d2d2d2;" class="tg-0pky">czas<br>zlecenia</td>
                </tr>
                <tr>
                    <td class="tg-0pky"><?php echo $first['wyjazdTime']; ?></td>
                    <td class="tg-0pky"><?php echo $last['przyjazdTime']; ?></td>
                    <td class="tg-0pky">
                    <?php
                    $time1 = explode(":" , $last['przyjazdTime']);
                    $time2 = explode(":" , $first['wyjazdTime']);
                    echo displayTimeDiff($time2[0],$time2[1],$time1[0],$time1[1]);
                    ?>
                    </td>
                </tr>
            </table>

            <table style="width:275px;margin-right:50px;float:right;" class="tg">
                <tr>
                    <th style="background:#d2d2d2;" style="text-align:center;" class="tg-cly1" colspan="4">TANKOWANIE</th>
                </tr>
                <tr>
                    <td style="background:#d2d2d2;" class="tg-cly1">Lp.</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">ilość paliwa<br>[litry]<br></td>
                    <td style="background:#d2d2d2;" class="tg-cly1">koszt<br>[złotych]</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">nr faktury</td>
                </tr>
                <?php
                    foreach($f as $key => $value)
                    {
                        echo '<tr><td class="tg-cly1">'. $i .'</td>';
                        echo '<td class="tg-cly1">'. $f[$key]['litry'] .'</td>';
                        echo '<td class="tg-cly1">'. $f[$key]['koszt'] .'</td>';
                        echo '<td class="tg-cly1">'. $f[$key]['faktura'] .'</td></tr>';
                        $i++;
                    }
                ?>
            </table>
        </div>

        <div style="margin-top:50px;display: inline-block;width: 100%;">
            <table style="margin-left:50px;float:left;width:88%;" class="tg">
                <tr>
                    <td style="background:#d2d2d2;" class="tg-cly1">Nr</td>
                    <td colspan="2" style="background:#d2d2d2;" class="tg-cly1">Skąd - dokąd</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">Wyjazd<br>godz.<br>min.</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">Wyjazd<br>stan<br>licznika</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">Przyjazd<br>godz.<br>min.</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">Przyjazd<br>stan<br>licznika</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">Przebieg<br>km</td>
                    <td style="background:#d2d2d2;" class="tg-cly1">Czas</td>
                </tr>
                <?php
                $i=1;
                foreach ($p as $key => $value)
                {
                    $time1 = explode(":" , $p[$key]['przyjazdTime']);
                    $time2 = explode(":" , $p[$key]['wyjazdTime']);
                    $id = $p[$key]['id'];
                    $przebieg1 = intval($p[$key]['przyjazdPrzebieg']);
                    $przebieg2 = intval($p[$key]['wyjazdPrzebieg']);
                    $przebieg_roznica = $przebieg1 - $przebieg2;

                    echo "<tr>";
                    echo "<th class='tg-cly1' scope='row'>$i</th>";// #
                    echo "<td style='max-width: 140px;' class='tg-cly1'>". $p[$key]['skad']. "</td><td style='max-width: 140px;' class='tg-cly1'>". $p[$key]['dokad'] ."</td>";// skąd dokąd
                    echo "<td class='tg-cly1'>". date("H:i", mktime($time2[0],$time2[1])) ."</td>";// Wyjazd godz
                    echo "<td class='tg-cly1'>". $przebieg2 ."</td>";// Wyjazd Stan licznika
                    echo "<td class='tg-cly1'>". date("H:i", mktime($time1[0],$time1[1])) ."</td>";// przyjazd godz
                    echo "<td class='tg-cly1'>". $przebieg1 ."</td>";// przyjazd godz
                    echo "<td class='tg-cly1'>". $przebieg_roznica ."</td>";// przyjazd godz
                    echo "<td class='tg-cly1'>". displayTimeDiff($time2[0],$time2[1],$time1[0],$time1[1]) ."</td>";// przyjazd godz

                    echo "</tr>";
                    $i++;
                }
                ?>
            </table>
        </div>
            <h2 style="margin-top:0px;padding:35px 0px;width:200px;border-bottom:2px dotted black;float:right;margin-right:50px;">Podpis:</h2>


    </page>
</body>
</html>