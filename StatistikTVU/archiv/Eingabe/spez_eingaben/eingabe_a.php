<?php 

include ('connect.inc.php');

$name = $_POST['name'];
$vorname = $_POST['vorname'];
$jg = $_POST['jg'];
$sex = $_POST['sex'];
$activ = $_POST['aktiv'];


/* check ob athlet schon in der datenbank*/
// get all athlet information from DB
$querry_athlet = mysql_query("SELECT ID FROM mitglied WHERE Vorname='$vorname' AND Name='$name' AND Jg=$jg ");
$result = mysql_num_rows($querry_athlet);

if (empty($name)){
	echo "name eingeben";    
}else if (empty($vorname)){
	echo "vorname eingeben";
} else if (empty($jg)){
	echo "jg eingeben";
} else if (empty($sex)){
	echo "geschlecht eingeben";
} else if (empty($activ)){
	echo "aktivität eingeben";
} elseif ($result!= NULL){
	$counter = 0;
	while($row=mysql_fetch_array($querry_athlet,MYSQL_NUM)) 
        {
            $athlet[$counter] = $row; 
            $counter++;
        }
	echo "Athlet schon in der Datenbank. Folgende Ergebnisse wurden gefunden: ID: ";
	echo ($athlet[0][0]);
	echo ", Name: ";
	echo ($vorname);
	echo " ";
	echo ($name);
	echo ', Jahrgang: ';
	echo ($jg);
	mysql_close($conn);       
}else {
   	$sql="INSERT INTO mitglied VALUES ('Null','$name','$vorname','$jg','$sex','$activ')" ;
    $query = mysql_query ($sql);
    header('Location:Eingabe_a_w_d.html');   
}  
echo '</br> <a href="http://localhost:1337/Bestenliste/Eingabe/spez_eingaben/Eingabe_a_w_d.html
"><input type="button" value="Zurück"/></a>';
 


 ?>