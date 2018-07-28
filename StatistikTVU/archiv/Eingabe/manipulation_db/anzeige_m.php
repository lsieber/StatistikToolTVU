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

?>     
    
<!doctype html>

<p id='result_change'></p>
<br/>
<table>
    <thead>
        <th>ID</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Jahrgang</th>
        <th>geschlecht</th>
        <th>aktiv</th>
    </thead>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>alle ändern</td>
        <td><input type="number" id="all_aktiv_input" value="30" oninput="change_aktiv_all()"></td>
    </tr>
    <?php 
    for ($i=0;$i<$anz->mitsize;$i++){
        $check_kat = 0;
        for ($j=0; $j < 8; $j++) { 
            if($anz->mit[$i][5] == $kat[$j] ){
                $check_kat = 1;
            }
        }
        if (($anz->mit[$i][4] == $sex[0] or $anz->mit[$i][4] == $sex[1])
            and ($check_kat == 1)) {
            $id = $anz->mit[$i][0];
            echo "<tr>";
                echo "<td>".$anz->mit[$i][0]."</td>";
                echo "<td>".$anz->mit[$i][2]."</td>";
                echo "<td>".$anz->mit[$i][1]."</td>";
                echo "<td>".$anz->mit[$i][3]."</td>";
                echo "<td>".$anz->mit[$i][4]."</td>";
                echo "<td>"."<input type='number' id='$i' name='aktiv'
                    value=".$anz->mit[$i][6]." oninput='change_aktiv($id,$i)' 
                    >"."</td>"; 
            echo "</tr>";
        }

    }

?>
</table>
