 //htmlcode in anderes file setzetn und klicken
<form method="POST" action='eingabe_w.php'>
	<input type='number' value='1950' name='poststart' id="poststart">
	<input type="submit">
</form>
<?php 

include ('connect.inc.php');

$start = $_POST['poststart'];

for ($i=$start; $i < 2014; $i++) { 

	$txt=implode([$i,"-01-01"]);
	$sql="UPDATE wettkampf SET Datum='$txt' WHERE ID='$i'";
	$query = mysql_query ($sql);
}

  
echo "old set";

 ?>