<?php
$events = json_decode(file_get_contents('events.json'), true);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="events.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Date', 'Title', 'Description', 'Category', 'Recurrence', 'Attendees', 'Reminder']);

foreach ($events as $date => $eventArray) {
    foreach ($eventArray as $event) {
        fputcsv($output, [
            $date,
            $event['title'],
            $event['description'],
            $event['category'],
            $event['recurrence'],
            $event['attendees'],
            $event['reminder']
        ]);
    }
}

fclose($output);
?>
