<?php
class Anzeige{
    public $mit = array();
    public $dis = array();
    public $wet = array();
    public $mitsize;
    private $year;

    public function set_year($jahr){
        $this->year = $jahr;
    }
    public function connect(){

    }
    public function set_mitglied(){
        include ('../../inc/connect.inc.php');

        $rmitglied=mysql_query(      //r steht für result
                    "SELECT * FROM mitglied
                    ORDER BY Geschlecht, Vorname
                    ");
        if (! $rmitglied){         // Check ob Abfrage gelungen ist
        echo"query mistake" . mysql_error();
        }

        //Eintragen der Mitglieder in Matrix $this->mit
        $counter=0;
        $this->mit=array();   //m steht für matrix

        while($row=mysql_fetch_assoc($rmitglied)) {

            $this->mit[$counter][0]= $row['ID'];
            $this->mit[$counter][1]= $row['Name'];
            $this->mit[$counter][2]= $row['Vorname'];
            $this->mit[$counter][3]= $row['Jg'];
            $this->mit[$counter][4]= $row['Geschlecht'];
            $this->mit[$counter][5]= "null";        // Platz für Kategorie
            $this->mit[$counter][6]= $row['aktiv'];
            $counter++;
        }
        $this->mitsize=$counter; // Anzahl der Mitglieder=Grösse Matrix
        
        // Einteilen in Kategorien
        for ($i=0;$i<$this->mitsize;$i++){
            if ($this->mit[$i][3] > $this->year-10
                and $this->mit[$i][3] < $this->year-4){
                $this->mit[$i][5]="10";    
            }elseif ($this->mit[$i][3] > $this->year-10) {
                $this->mit[$i][5]="4";
            }
            elseif ($this->mit[$i][3]>$this->year-12){
                $this->mit[$i][5]="12"; 
            }
            elseif ($this->mit[$i][3]>$this->year-14){
                $this->mit[$i][5]="14";
            }
            elseif ($this->mit[$i][3]>$this->year-16){
                $this->mit[$i][5]="16";
            }
            elseif ($this->mit[$i][3]>$this->year-18){
                $this->mit[$i][5]="18";
            }
            elseif ($this->mit[$i][3]>$this->year-20){
                $this->mit[$i][5]="20";
            }
            elseif ($this->mit[$i][3]+$this->mit[$i][6] > $this->year-16){

                if($this->mit[$i][4]==1){
                    $this->mit[$i][5]="201";
                } elseif ($this->mit[$i][4]==2)
                {
                    $this->mit[$i][5]="202";
                } else {
                    $this->mit[$i][5]="203";
                }
            }
        } 
    }
    public function set_wettkampf(){
        include ('../../inc/connect.inc.php');
        $rwettkampf=mysql_query(      //r steht für result
            "SELECT * FROM wettkampf
            WHERE sichtbar=1
            ORDER BY Datum
            ");
        if (! $rwettkampf){         // Check ob Abfrage gelungen ist
        echo"query mistake" . mysql_error();
        }

        //Eintragen der Wettkämpfe in Matrix $this->wet
        $counter=0;
        $this->wet=array();   //m steht für matrix

        while($row=mysql_fetch_assoc($rwettkampf)) {

            $this->wet[$counter][0]= $row['ID'];
            $this->wet[$counter][1]= $row['WKname'];
            $this->wet[$counter][2]= $row['Ort'];
            $this->wet[$counter][3]= $row['Datum'];

            $counter++;
        }
        $this->wetsize=$counter; // Anzahl der Wettkämpfe=Grösse Matrix

    }
    public function set_disziplin(){
        include ('../../inc/connect.inc.php');

        $rdisziplin=mysql_query(
                "SELECT * FROM disziplin ORDER BY Lauf, Laufsort, Disziplin ");
        if (! $rdisziplin){
        echo"query mistake" . mysql_error();
        }

        //Eintragen der Disziplinen in Matrix $this->dis
        $counter=0;
        $this->dis=array();   //m steht für matrix

        while($row=mysql_fetch_assoc($rdisziplin)) {

            $this->dis[$counter][0]= $row['ID'];
            $this->dis[$counter][1]= $row['Disziplin'];
            $this->dis[$counter][2]= $row['Lauf'];
            $this->dis[$counter][3]= $row['U10'];
            $this->dis[$counter][4]= $row['U12'];
            $this->dis[$counter][5]= $row['U14'];
            $this->dis[$counter][6]= $row['U16'];
            $this->dis[$counter][7]= $row['Frauen'];
            $this->dis[$counter][8]= $row['Manner'];

            $counter++;
        }
        $this->dissize=$counter; // Anzahl der Disziplinen=Grösse Matrix

    }
}
  
 ?>