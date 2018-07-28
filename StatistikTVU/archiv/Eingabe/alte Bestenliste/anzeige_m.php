<?php


include "inc/anzeige_class.php";


$anz = new Anzeige();
//------------------ Kategorie aus Eingabe speichern ----------------
$year = $_POST['postyear'];
$anz->set_year($year);
$anz->set_mitglied();

$kat = $_POST['postkategorie'];
if ($kat[0] == "no_kat") {
    $kat[0] = 10;
    $kat[1] = 12;
    $kat[2] = 14;
    $kat[3] = 16;
    $kat[4] = 18;
    $kat[5] = 20;
    $kat[6] = 201;
    $kat[7] = 202;
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

<b>Mitglieder</b>
<br/>
    <?php  
    for ($i=0;$i<$anz->mitsize;$i++){
        for ($j=0; $j < sizeof($kat); $j++) { 
            if($anz->mit[$i][5] == $kat[$j]
                and ($anz->mit[$i][4] == $sex[0] or $anz->mit[$i][4] == $sex[1]))

                {
                ?>  <input
                type="checkbox"
                name="mitglied[]"
                value="<?php echo $anz->mit[$i][0] ?>" />
                <?php echo $anz->mit[$i][2]; ?> &nbsp;
                <?php echo $anz->mit[$i][1]; ?> &nbsp;
                <?php echo $anz->mit[$i][3]; ?> 
                <br /> <?php
            }
            if($anz->mit[$i][5]==203){  //gemischte Teams Staffeln anzeigen
                if($kat[$j]==201 or $kat[$j]==202){           // bei Männern und Frauen anzeigen
                   ?>  <input
                    type="checkbox"
                    name="mitglied[]"
                    value="<?php echo $anz->mit[$i][0] ?>" />
                    <?php echo $anz->mit[$i][2]; ?> &nbsp;
                    <?php echo $anz->mit[$i][1]; ?>
                    <br /> <?php     
                }
            }
        }
        
        
    }
?>
