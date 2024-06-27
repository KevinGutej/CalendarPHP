<?php
function build_calendar($month, $year) {
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $calendar = "<table>";
    $calendar .= "<caption>$monthName $year</caption>";
    $calendar .= "<tr>";

    foreach($daysOfWeek as $day) {
        $calendar .= "<th>$day</th>";
    }

    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) {
        for($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td></td>";
        }
    }

    $currentDay = 1;
    $todayDate = date('Y-m-d');
    $events = array(
        '2024-06-14' => 'Event 1',
        '2024-06-18' => 'Event 2'
    );

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDate = "$year-$month-" . str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $class = ($currentDate == $todayDate) ? 'today' : '';
        $class .= isset($events[$currentDate]) ? ' event' : '';

        $calendar .= "<td class='$class'>$currentDay";

        if (isset($events[$currentDate])) {
            $calendar .= "<br>" . $events[$currentDate];
        }

        $calendar .= "</td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        for($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    return $calendar;
}

echo build_calendar($month, $year);
?>
