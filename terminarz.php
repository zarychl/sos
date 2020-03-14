<?php
require_once("includes/head.php");

if(!isset($_GET['m']) || !isset($_GET['y']))
{
    $_GET['m'] = date("M");
    $_GET['y'] = date("Y");

    header('Location: terminarz.php?m='. date("n") .'&y=' . date("Y"));
}

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

//KALENDAR

function build_calendar($month,$year) {

    // Create array containing abbreviations of days of week.
    $daysOfWeek = array('Pn','Wt','Śr','Cz','Pt','So','Nd');
    $months = array('','Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień', 'Październik', 'Listopad', 'Grudzień');
    // What is the first day of the month in question?
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

    // How many days does this month contain?
    $numberDays = date('t',$firstDayOfMonth);

    // Retrieve some information about the first day of the
    // month in question.
    $dateComponents = getdate($firstDayOfMonth);

    // What is the name of the month in question?
    $monthName = $months[$month];

    // What is the index value (0-6) of the first day of the
    // month in question.
    if($dateComponents['wday'] == 0)
        $dayOfWeek = 6;
    else 
        $dayOfWeek = $dateComponents['wday']-1;
    
    // Create the table tag opener and day headers

    $prevM = date("Y-m", strtotime("-1 month", strtotime($_GET['y']. "-" . $_GET['m'] . "-01")));
    $nextM = date("Y-m", strtotime("+1 month", strtotime($_GET['y']. "-" . $_GET['m'] . "-01")));

    $calendar = "<table class='calendar table'>";
    $calendar .= "<tr ><th colspan='7'>
    <button data-prev='$prevM' id='prev' type='button' class='btn btn-primary'><i class='fas fa-arrow-left'></i> Poprzedni miesiąc</button>
    <span style='font-size:16pt;'>$monthName $year</span> <button data-next='$nextM' id='next' type='button' class='btn btn-primary'>Następny miesiąc <i class='fas fa-arrow-right'></i></button>
    </th></tr>";
    $calendar .= "<tr class='thead-dark'>";

    // Create the calendar headers

    foreach($daysOfWeek as $day) {
         $calendar .= "<th class='header'>$day</th>";
    } 

    // Create the rest of the calendar

    // Initiate the day counter, starting with the 1st.

    $currentDay = 1;

    $calendar .= "</tr><tr>";

    // The variable $dayOfWeek is used to
    // ensure that the calendar
    // display consists of exactly 7 columns.

    if ($dayOfWeek > 0) { 
         $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>"; 
    }
    
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
 
    while ($currentDay <= $numberDays) {

         // Seventh column (Saturday) reached. Start a new row.

         if ($dayOfWeek == 7) {

              $dayOfWeek = 0;
              $calendar .= "</tr><tr>";

         }
         
         $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
         
         $date = "$year-$month-$currentDayRel";

         $calendar .= "<td data-href='terminarzDay.php?date=$date' class='day clickable-row' rel='$date'>$currentDay";

        $events = getEventsByDay($date);

        if($events)// sprawdzamy czy w tym dniu są w ogóle jakieś eventy
        {
            foreach($events as $key => $value)
            {
                $time1 = explode(":" , $events[$key]['time']);
                $calendar .= "<div style='padding:3px;margin-bottom:2px;' class='bg-primary text-white'>". $events[$key]['nazwa']. " - " . date("H:i", mktime($time1[0],$time1[1])) ."</div>";
            }
        }   
         $calendar .=  "</td>";

         // Increment counters

         $currentDay++;
         $dayOfWeek++;

    }
    
    

    // Complete the row of the last week in month, if necessary

    if ($dayOfWeek != 7) { 
    
         $remainingDays = 7 - $dayOfWeek;
         $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>"; 

    }
    
    $calendar .= "</tr>";

    $calendar .= "</table>";

    return $calendar;

}

//END KALENDAR


require_once("includes/navbar.php");
require_once("includes/sidebar.php");


?>
<style>
    .calendar tr td
    {
        width: 10%;
        height: 13vh;
    }
    .calendar tr td:hover:not([colspan]) 
    {
        background:#e6e6e6;
        cursor:pointer;
    }
</style>
<script>
    $(document).ready(function(){
        $(document).on('click',"#next", function() {
            var month = $(this).data('next').substring(5, 7)*1;
            var year = $(this).data('next').substring(0, 4)*1;
            $.get('terminarz.php', {m:month, y:year}, function (data) {
                $('.calendar').html($(data).find(".calendar"));
            });
        });
        $(document).on('click','#prev', function() {
            var month = $(this).data('prev').substring(5, 7)*1;
            var year = $(this).data('prev').substring(0, 4)*1;
            $.get('terminarz.php', {m:month, y:year}, function (data) {
                $('.calendar').html($(data).find(".calendar"));
            });
        });
    })
</script>
    <div id="content-wrapper">

        <div class="container-fluid">


            <!-- Area Chart Example-->
            <div class="card mb-3">
                <div class="card-header bg-success text-white font-weight-bold">
                    Terminarz
                </div>
                <div class="card-body">
                <?php
                $month = $_GET['m'];
                $year = $_GET['y'];
                echo build_calendar($month,$year);
                ?>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright ©</span>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.content-wrapper -->
    <?php
require_once("includes/footer.php");
?>