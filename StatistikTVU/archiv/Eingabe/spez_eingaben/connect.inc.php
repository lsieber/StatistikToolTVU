<?php 

    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "bestenliste";
    
    $conn = mysql_connect($host,$user,$pw) or die (mysql_error());
    mysql_select_db($db) or die (mysql_error());
    mysql_set_charset('utf8');

 ?>