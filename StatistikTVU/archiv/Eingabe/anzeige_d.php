<?php

include "anzeige.php";

//------------------ Kategorie aus Eingabe speichern ----------------         
$kat=$_POST['postkategorie'];

?>     
    
<!doctype html>
    <?php  for ($i=0;$i<$mdisziplinsize;$i++){ //Iteration über alle disziplinen
        if($kat==10){        
            if ($mdisziplin[$i][3]==1){
                ?>  <input
                type="radio"
                name="disziplin"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }    
        }  
        if($kat==12){
            if ($mdisziplin[$i][4]==1){
                ?>  <input
                type="radio"
                name="disziplin"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==14){
            if ($mdisziplin[$i][5]==1){
                ?>  <input
                type="radio"
                name="disziplin"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==16){
            if ($mdisziplin[$i][6]==1){
                ?>  <input
                type="radio"
                name="disziplin"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==201){
            if ($mdisziplin[$i][9]==1){
                ?>  <input
                type="radio"
                name="disziplin"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
        if($kat==202){
            if ($mdisziplin[$i][10]==1){
                ?>  <input
                type="radio"
                name="disziplin"
                value="<?php echo $mdisziplin[$i][0] ?>" />
                <?php echo $mdisziplin[$i][1]; ?>
                <br /> <?php
            }
        }
               
    }
?>
