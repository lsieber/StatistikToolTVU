<?php


include "inc/anzeige_class.php";


$anz = new Anzeige();
//------------------ Kategorie aus Eingabe speichern ----------------
$year = $_POST['postyear'];
$anz->set_year($year);
$anz->set_mitglied();

$kathelp=$_POST['postkategorie'];
$kathelp[sizeof($kathelp)] = 'null';
$id=0;
for ($i=0; $i < 8; $i++) { // 0:10 1:12 2:14 3:16 4:18 5:20 6:201 7:202
    if ($kathelp[$id] == 2*$i+10 or $kathelp[$id] == 195+$i) {
        $kat[$i] = $kathelp[$id];
        $id++;
    }else{
        $kat[$i] = 0;
    }
}

$sex = $_POST['postgeschlecht'];
if ($sex[0] == "no_sex") {
    $sex[0] = 1;
    $sex[1] = 2;
}
if (sizeof($sex) == 1) {
    $sex[1] = 'only one sex';
}

$anz->get_bestenliste();
?>     
    
<!doctype html>

<p id='result_bestenliste'></p>
<br/>
<table>
    <thead>
        <th>ID</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Jahrgang</th>
        <th>Disziplin</th>
        <th>Wettkampf</th>
        <th>Jahr</th>
        <th>Leistung</th>
    </thead>
    <?php 
    for ($i=0;$i<$anz->bestsize;$i++){
        echo "<tr>";
            echo "<td>".$anz->best[$i][0]."</td>";
            echo "<td>".$anz->best[$i][4]."</td>";
            echo "<td>".$anz->best[$i][5]."</td>";
            echo "<td>".$anz->best[$i][6]."</td>";
            echo "<td>".$anz->best[$i][9]."</td>";
            echo "<td>".$anz->best[$i][7]."</td>";
            echo "<td>".$anz->best[$i][8]."</td>";
            echo "<td>"."<input type='text' id='$i' name='aktiv'
                value=".$anz->best[$i][1]." oninput='change_leistung($id,$i)' 
                >"."</td>"; 
        echo "</tr>";
    }

?>
</table>

