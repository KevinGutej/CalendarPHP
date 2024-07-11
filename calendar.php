<?php
$daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

function buildCalendar($month, $year, $view, $events, $search, $filterCategory, $filterRecurrence) {
    global $daysOfWeek;

    if ($view == 'month') {
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $monthName = $dateComponents['month'];
        $dayOfWeek = $dateComponents['wday'];

        $today = date('Y-m-d');
        $calendar = '<table>';
        $calendar .= '<tr>';

        foreach ($daysOfWeek as $day) {
            $calendar .= "<th>$day</th>";
        }

        $calendar .= '</tr><tr>';

        if ($dayOfWeek > 0) {
            for ($k = 0; $k < $dayOfWeek; $k++) {
                $calendar .= '<td></td>';
            }
        }

        $currentDay = 1;

        while ($currentDay <= $numberDays) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= '</tr><tr>';
            }

            $currentDate = "$year-$month-" . str_pad($currentDay, 2, '0', STR_PAD_LEFT);

            $class = '';
            if ($currentDate == $today) {
                $class = 'today';
            }

            $calendar .= "<td data-date='$currentDate' class='$class'>";
            $calendar .= "<div>$currentDay</div>";

            foreach ($events as $eventDate => $eventArray) {
                if ($eventDate == $currentDate || checkRecurrence($eventArray['recurrence'], $eventDate, $currentDate)) {
                    foreach ($eventArray as $event) {
                        if (($search && stripos($event['title'], $search) === false && stripos($event['description'], $search) === false) ||
                            ($filterCategory && $event['category'] !== $filterCategory) ||
                            ($filterRecurrence && $event['recurrence'] !== $filterRecurrence)) {
                            continue;
                        }
                        $tooltip = "<div class='tooltip'>{$event['description']}</div>";
                        $calendar .= "<div class='event {$event['category']}' id='event-$currentDate' draggable='true' ondragstart='handleDragStart(event)' onclick='showModal(\"{$event['title']}\", \"{$event['description']}\", \"{$event['category']}\", \"{$event['recurrence']}\", \"$currentDate\", \"{$event['attendees']}\", \"{$event['reminder']}\")'>{$event['title']} $tooltip</div>";
                    }
                }
            }

            $calendar .= '</td>';

            $currentDay++;
            $dayOfWeek++;
        }

        if ($dayOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for ($l = 0; $l < $remainingDays; $l++) {
                $calendar .= '<td></td>';
            }
        }

        $calendar .= '</tr>';
        $calendar .= '</table>';
    } elseif ($view == 'week') {
    } elseif ($view == 'day') {
    }

    return $calendar;
}

function checkRecurrence($recurrence, $eventDate, $currentDate) {
    if ($recurrence == 'None') return false;

    $eventTimestamp = strtotime($eventDate);
    $currentTimestamp = strtotime($currentDate);

    switch ($recurrence) {
        case 'Daily':
            return ($currentTimestamp > $eventTimestamp);
        case 'Weekly':
            return (($currentTimestamp - $eventTimestamp) % (7 * 24 * 60 * 60) == 0);
        case 'Monthly':
            return (date('d', $eventTimestamp) == date('d', $currentTimestamp));
        case 'Yearly':
            return (date('m-d', $eventTimestamp) == date('m-d', $currentTimestamp));
    }
    return false;
}

$events = json_decode(file_get_contents('events.json'), true);
$calendarHtml = buildCalendar($month, $year, $view, $events, $search, $filterCategory, $filterRecurrence);

echo $calendarHtml;
?>
