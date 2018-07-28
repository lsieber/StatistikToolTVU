<?php

include ('../inc/connect.inc.php');
$type = $_POST['posttype'];
$ryears = mysql_query( 
            "SELECT DISTINCT w.Jahr
            FROM bestenliste b
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            ORDER BY w.Jahr DESC
        ");

if (! $ryears){
    echo"query mistake" . mysql_error();        
    } 
    $counter = 0;
    while($row=mysql_fetch_array($ryears,MYSQL_NUM)) 
    {
        $years[$counter] = $row; 
        $counter++;
    }    
?>
<!doctype html>
      
    <input
        type="<?php echo $type ?>" 
        name="year[]" 
        id="<?php echo $years[0][0] ?>" 
        value="<?php echo $years[0][0] ?>" 
        checked="no" 
        onclick="no_all_year()"
        /> <?php echo $years[0][0]; ?> <br />  
    <?php
    for ($i=1;$i<sizeof($years);$i++){ 
    ?>  <input
        type="<?php echo $type ?>" 
        name="year[]" 
        id="<?php echo $years[$i][0] ?>" 
        value="<?php echo $years[$i][0] ?>" 
        onclick="no_all_year()"
        /> <?php echo $years[$i][0]; ?> <br />
    <?php 
    }         
?>
