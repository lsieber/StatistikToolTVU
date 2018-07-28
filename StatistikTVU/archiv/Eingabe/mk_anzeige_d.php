<?php

// Dieses File stellt sicher, dass für die Eingabe der Leistungen 
// alle Disziplinen zur Auswahl stehen, die in der jeweiligen 
// Kategorie Sinn machen. Daher wird mittels Post methode die
// Kategorie aus dem file index.php übernommen und dann 
// ausgewertet welche disziplinen angezeigt werden. dies ist in
// der Datenbank disziplin gespeichert

include "anzeige.php";

//------------------ Kategorie aus Eingabe speichern ----------------

           
    $kat=$_POST['postkategorie'];




?>     
    
<!doctype html>
    <?php  for ($i=0;$i<$mdisziplinsize;$i++){ //Iteration über alle disziplinen
        if($kat==10){        
            if ($mdisziplin[$i][3]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }    
        }  
        if($kat==12){
            if ($mdisziplin[$i][4]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==14){
            if ($mdisziplin[$i][5]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==16){
            if ($mdisziplin[$i][6]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==201){
            if ($mdisziplin[$i][7]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==202){
            if ($mdisziplin[$i][8]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }

        }
        if($kat==1000){
            if ($mdisziplin[$i][3]==1 or $mdisziplin[$i][4]==1 or
                $mdisziplin[$i][5]==1 or $mdisziplin[$i][6]==1 or
                $mdisziplin[$i][7]==1 or $mdisziplin[$i][8]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> 
                <?php
            }
        }      
    }
?>
