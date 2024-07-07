<?php
function build_calendar($month, $year) {
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $todayDate = date('Y-m-d');

    $events = json_decode(file_get_contents('events.json'), true);

    $calendar = "<table>";
    $calendar .= "<tr>";

    foreach ($daysOfWeek as $day) {
        $calendar .= "<th>$day</th>";
    }

    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td></td>";
        }
    }

    $currentDay = 1;

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDate = "$year-$month-" . str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $class = ($currentDate == $todayDate) ? 'today' : '';

        $eventDetails = '';
        foreach ($events as $eventDate => $event) {
            if ($eventDate == $currentDate || isRecurringEvent($eventDate, $event['recurrence'], $currentDate)) {
                $class .= ' ' . strtolower($event['category']);
                $eventDetails = ' onclick="showModal(\'' . $event['title'] . '\', \'' . $event['description'] . '\', \'' . $event['category'] . '\', \'' . $event['recurrence'] . '\', \'' . $currentDate . '\')"';
            }
        }

        $calendar .= "<td class='$class' $eventDetails>$currentDay</td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    return $calendar;
}

function isRecurringEvent($eventDate, $recurrence, $currentDate) {
    $eventDate = new DateTime($eventDate);
    $currentDate = new DateTime($currentDate);

    if ($recurrence == 'Daily') {
        return $eventDate <= $currentDate;
    } elseif ($recurrence == 'Weekly') {
        return $eventDate <= $currentDate && $eventDate->format('w') == $currentDate->format('w');
    } elseif ($recurrence == 'Monthly') {
        return $eventDate <= $currentDate && $eventDate->format('d') == $currentDate->format('d');
    } elseif ($recurrence == 'Yearly') {
        return $eventDate <= $currentDate && $eventDate->format('m-d') == $currentDate->format('m-d');
    }
    return false;
}

echo build_calendar($month, $year);
?>
