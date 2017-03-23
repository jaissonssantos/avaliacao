<?php


$msg = array();

$year = date('Y');
$month = date('m');
$day = date('d');
$hour = date('H');
$minute = date('i');

$msg['year'] = $year;
$msg['month'] = $month;
$msg['day'] = $day;
$msg['hour'] = $hour;
$msg['minute'] = $minute;

echo json_encode($msg);
