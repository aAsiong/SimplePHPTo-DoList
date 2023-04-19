<?php

$sampleArray = [];
$q = $_REQUEST['q'];

if (isset($_COOKIE['activities'])) {
    $sampleArray = json_decode($_COOKIE['activities'], true);
    unset($sampleArray[$q]);
    $sampleArray = array_values($sampleArray);
    setcookie('activities', json_encode($sampleArray), time()+3600);
} else {
    echo "NO COOKIES DETECTED!";
}

header('Location: /SimplePHPTo-DoList/index.php');

?>