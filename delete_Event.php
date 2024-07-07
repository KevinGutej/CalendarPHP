<?php
session_start();
if (isset($_SESSION['username']) && isset($_GET['date'])) {
    $date = $_GET['date'];
    
    $events = json_decode(file_get_contents('events.json'), true);

    if (isset($events[$date])) {
        unset($events[$date]);
        file_put_contents('events.json', json_encode($events));
    }

    header('Location: index.php');
}
?>
