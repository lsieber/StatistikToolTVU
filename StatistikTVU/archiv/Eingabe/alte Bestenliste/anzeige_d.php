<?php

include "inc/anzeige_class.php";
$anz = new Anzeige();
$anz->set_disziplin();

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
if ($sex == 'no_sex') {
    $sex[0] = 1;
    $sex[2] = 2;
}
if (sizeof($sex) == 1) {
    if ($sex[0] == 1) {
        $sex[1] = 0;
    }else{
        $sex[0] = 0;
        $sex[1] = 2;
    }
}
?>       
<!doctype html>
    <?php  
    echo "<b>Disziplinen</b><br/>";
    for ($i=0;$i<sizeof($anz->dis);$i++){ //Iteration über alle disziplinen 
            if(($kat[0]==10)
                    and $anz->dis[$i][3]==1){        
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $anz->dis[$i][0] ?>" />
                <?php echo $anz->dis[$i][1]; ?>
                <br /> <?php
                
            }  
            elseif(($kat[1]==12)
                    and $anz->dis[$i][4]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $anz->dis[$i][0] ?>" />
                <?php echo $anz->dis[$i][1]; ?>
                <br /> <?php
            }
            elseif(($kat[2]==14)
                    and $anz->dis[$i][5]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $anz->dis[$i][0] ?>" />
                <?php echo $anz->dis[$i][1]; ?>
                <br /> <?php
            }
            elseif(($kat[3]==16)
                    and $anz->dis[$i][6]==1){
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $anz->dis[$i][0] ?>" />
                <?php echo $anz->dis[$i][1]; ?>
                <br /> <?php
            }
            elseif(($kat[6]==201 or
                    (($kat[4]==18 or $kat[5]==20) and $sex[0]==1))
                    and $anz->dis[$i][7]==1){           
                ?>  <input
                type="checkbox"
                name="disziplin[]"
                value="<?php echo $anz->dis[$i][0] ?>" />
                <?php echo $anz->dis[$i][1]; ?>
                <br /> <?php   
            }
            elseif(($kat[7]==202 or
                    (($kat[4]==18 or $kat[5]==20) and $sex[1]==2))
                    and $anz->dis[$i][8]==1){
                    ?>  <input
                    type="checkbox"
                    name="disziplin[]"
                    value="<?php echo $anz->dis[$i][0] ?>" />
                    <?php echo $anz->dis[$i][1]; ?>
                    <br /> <?php
            }              
    }
?>
