<?php

include "anzeige.php";

//------------------ Kategorie aus Eingabe speichern ----------------

           
$kat = $_POST['postkategorie'];
$wet = $_POST['postwettkampf'];

$wet_id = 0;
while ($mwettkampf[$wet_id][0] != $wet) {
    $wet_id++;
}
$Jahr = $mwettkampf[$wet_id][4];

// Einteilen in Kategorien
for ($i=0;$i<$mmitgliedsize;$i++){
    if ($mmitglied[$i][3]>$Jahr-10 and $mmitglied[$i][3]<$Jahr-5){
        $mmitglied[$i][5]="10";    
    }
    elseif ($mmitglied[$i][3]>$Jahr-12){
        $mmitglied[$i][5]="12"; 
    }
    elseif ($mmitglied[$i][3]>$Jahr-14){
        $mmitglied[$i][5]="14";
    }
    elseif ($mmitglied[$i][3]>$Jahr-16){
        $mmitglied[$i][5]="16";
    }
    else{
        if($mmitglied[$i][4]==1){
            $mmitglied[$i][5]="201";
        } elseif ($mmitglied[$i][4]==2)
        {
            $mmitglied[$i][5]="202";
        } else {
            $mmitglied[$i][5]="203";
        } 
    }
} 


?>     
    
<!doctype html>
    <?php  
    if($kat==1000){
        for ($i=0;$i<$mmitgliedsize;$i++){
            if ($mmitglied[$i][3]+$mmitglied[$i][6]+16 > $Jahr) {
                ?>  <input
                type="checkbox"
                name="mitglied[]"
                value="<?php echo $mmitglied[$i][0] ?>" />
                <?php echo $mmitglied[$i][2]; ?> &nbsp;
                <?php echo $mmitglied[$i][1]; ?> 
                <br /> <?php 
            }         
        }

    }
    else{

        for ($i=0;$i<$mmitgliedsize;$i++){
            if ($mmitglied[$i][3]+$mmitglied[$i][6]+16 > $Jahr) {
                if($mmitglied[$i][5]==$kat){
                    ?>  <input
                    type="checkbox"
                    name="mitglied[]"
                    value="<?php echo $mmitglied[$i][0] ?>" />
                    <?php echo $mmitglied[$i][2]; ?> &nbsp;
                    <?php echo $mmitglied[$i][1]; ?> 
                    <br /> <?php
                }
            }           
        }
    }
?>
