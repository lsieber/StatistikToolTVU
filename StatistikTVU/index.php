<!Doctype html>
<?php
include_once("inc/class_links.inc.php");
$link = new link($_SERVER['SERVER_NAME'],$_SERVER['REQUEST_URI']);
?>
<head>
<title>Backend Statistik Tool TVU</title>


</head>

<body>

<h1>Willkommen im Statistiktool des Turnvereins Unterseen</h1>

<?php
$link->link_phpmyadmin();
$link->link_output();
$link->link_input();
$link->link_team_input();

//$link->get_server();
//$link->get_attributes();
//echo  $link->create_abs_path("test/index_test.php");
?>

</body>