<?php
include_once("class_sql.inc.php"); 

function last_3_entries($conn){
    $sql_last_3 = new last_3_entries();
    $sql_last_3->set_select('b.ID, Leistung, Vorname, Name, Jg, Disziplin, Ort, Datum, Lauf');
    $result=$conn->query($sql_last_3->get_sql());
    $array_result = $result->fetch_all(MYSQLI_ASSOC);
    echo "-----";
    echo "</br><b>Letzte 3 Eingaben</b></br>";
    foreach ($array_result as $key => $performance) 
    {
        echo "<b>ID:".$performance['ID'].",</b> ".$performance['Leistung'].", ".$performance['Disziplin'].", ".$performance['Vorname']." ".$performance['Name']." ";
        echo "<a href='#' onclick=delete_entry(".$performance['ID'].")>Löschen</a>";
        echo "</br></br>";
    }
    echo "----- ";
}

function visible_competitions($conn,$checkable_wettkampf_id = 0){
    $sql_wettkampf = new sql_wettkampf();
    $result=$conn->query($sql_wettkampf->get_sql());
    $array_result = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($array_result as $key => $wettkampf) 
    {
        $checked = ($wettkampf['ID']==$checkable_wettkampf_id)? " checked ": "";
        $date = new DateTime($wettkampf['Datum']);
        echo "<input type='radio' name='wettkampf', onclick='post();check_wk_year(0)' value='".$wettkampf['ID']."'".$checked."/>";
        echo $date->format('j.n.y').", <b>".$wettkampf['WKname']."</b>, ". $wettkampf['Ort']."</br>";
    }
}



?>