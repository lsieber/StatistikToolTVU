<?php
// Klasse für die Ausgbe einer Bestenliste
class Ausgabe 
{
    public $leistung = array(); //array for the raw data without improvement after import
    public $AnzLeiDis = array();    // twodimensional Array containing number of Leistungen per disciplin entry: 0: Disziplin ID, 1: number of leistunge, 2: Lauf, 3: Laufsort
    public $bestenliste = array();  // two dimensonal arry containing the bestenliste:  
    public $Uberschrift;        // contains Header of the Bestenliste
    public $top;                // contains nuber of entrys per disciplin
    public  $disziplin;         // variable for the disziplins of the bestenliste
    public  $minmaxfullfilled = array();  // variable for check if the min and max value are fullfilled, 0=too large or too small value, 1= all ok.

    private $Kategorieo;        // upper limit for the age of people in the asked Bestenliste
    private $Kategorieu;        // lower limit for the age of the people in the asked Bestenliste
    private $Geschlecht;        // variable for the sex of the Bestenliste
    private $Geschlechthelp;    // variable for the case that both sex are choosen
    private $Jahr;              // variable for the year of the Bestenliste, if  value=0, all years are asked
    private $Jahrhelp;          // variable for the case that all years are asked -> value = 0; else value =10000
    private $Kategorie;         // variable for the string in the heading of the Bestenliste
    private $AnzKat;            // variable contains number of kategories in the Bestenliste
  
    public function set_kategorie($kategorie){
        $this->AnzKat = sizeof($kategorie);
        $this->Kategorieo = $kategorie[$this->AnzKat-1];
        if($this->Kategorieo == 200){
            if ($this->AnzKat > 1) {
                $this->Kategorieu = $kategorie[0]-3;
            }else{
                $this->Kategorieu = 17;
            }
        }elseif ($this->Kategorieo == 1000) {
            $this->Kategorieu = 0;
        }else{
            $this->Kategorieu = $kategorie[0]-3;
        }  
        if($this->Kategorieo == 10){
            $this->Kategorieu = 0;
        }
    }
    public function set_disziplin($disziplin){
        $this->disziplin = $disziplin;
    }
    public function set_jahr($year){
        $num_years = sizeof($year);
        if ($num_years == 1) {
            $this->Jahr = $year[0];
        } else {
            $this->Jahr = implode(",",$year);
        }
        $this->Jahrhelp = 10000;
        if ($this->Jahr == 0) {
            $this->Jahrhelp = 0;
        }
    }
    public function set_geschlecht($sex){   // sets Geschlecht to the chosen value.
        $this->Geschlecht = $sex;  
        $this->Geschlechthelp = $this->Geschlecht;
        if ($this->Geschlecht == 3) {
            $this->Geschlecht = 1;
            $this->Geschlechthelp = 2; 
        }
    }
    public function set_top($top,$topf){    // sets value of top to the value in the text field, 
        $this->top = $topf;                 // if field is zero, the chosen radio is taken.
        if ($this->top == "") {
            $this->top = $top; 
        }
    }

    public function set_leistung($host, $user, $password){  //import all leistungen out of database. IMPORTANT: before the use execute set_kategorie(), set_jahr() and set_geschlecht()
        $conn= mysql_connect($host, $user, $password);
        mysql_set_charset('utf8');
        if (!$conn)
        {
            echo "Unable to connect to DB: " . mysql_error();
            exit;
        }
        if (!mysql_select_db("bestenliste")) 
        {
            echo "Unable to select bestenliste: " . mysql_error();
            exit;
        }



        $rraw=mysql_query("SELECT 
            b.Leistung,b.Mitglied, m.Vorname, m.Name, m.Jg, w.WKname, w.Ort, w.Datum,
            b.DisziplinID, d.Disziplin, d.Lauf, d.Laufsort, d.ID, w.Jahr, d.MaxVal, d.MinVal
            FROM bestenliste b
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            WHERE   (
                        (
                            Jg>(w.Jahr-$this->Kategorieo)
                            AND Jg<(w.Jahr-$this->Kategorieu)
                            AND Jahr IN ($this->Jahr)
                        ) 
                    OR 
                        (
                            Jahr>$this->Jahrhelp 
                            AND Jg>(w.Jahr-$this->Kategorieo)
                            AND Jg<(w.Jahr-$this->Kategorieu)
                        )
                    ) 
                AND 
                    ( 
                        Geschlecht=$this->Geschlecht
                        OR Geschlecht=$this->Geschlechthelp
                        OR Geschlecht=3
                    )  
            ORDER BY Lauf, Laufsort, Disziplin, Leistung, ID
        ");
        // ***************Import der Daten in $leitung Matrix*******************3
        if (! $rraw)
        {
            echo"query mistake" . mysql_error();        
        } 
        $counter = 0;
        while($row=mysql_fetch_array($rraw,MYSQL_NUM)) 
        {
            $this->leistung[$counter] = $row; 
            $counter++;
        }
        mysql_close($conn);       
    }

    /* public function set_AnzLeiDis($array){
        $id=0;
        echo('hallllllooooo');
        echo(sizeof($array));
        for ($i=0; $i < sizeof($array) ; $i++)
        {
            if($i==0)
            {
                $this->AnzLeiDis[$id][0]=$array[0][8];
                $this->AnzLeiDis[$id][1]=0;               
                $this->AnzLeiDis[$id][2]=$array[0][10];
                $dis=$array[0][8];
            }
            if ($array[$i][8]==$dis) 
            {
                $this->AnzLeiDis[$id][1]++;
            }else
            {
                $dis=$array[$i][8];
                $id++;
                $this->AnzLeiDis[$id][0]=$dis;
                $this->AnzLeiDis[$id][1]=1;  
                $this->AnzLeiDis[$id][2]=$array[$i][10];   
            }
        }
    }*/

    public function set_AnzLeiDis(){
        $id=0;
        for ($i=0; $i < sizeof($this->leistung) ; $i++)
        {
            if($i==0)
            {
                $this->AnzLeiDis[$id][0]=$this->leistung[0][8];
                $this->AnzLeiDis[$id][1]=0;               
                $this->AnzLeiDis[$id][2]=$this->leistung[0][10];
                $dis=$this->leistung[0][8];
            }
            if ($this->leistung[$i][8]==$dis) 
            {
                $this->AnzLeiDis[$id][1]++;
            }else
            {
                $dis=$this->leistung[$i][8];
                $id++;
                $this->AnzLeiDis[$id][0]=$dis;
                $this->AnzLeiDis[$id][1]=1;  
                $this->AnzLeiDis[$id][2]=$this->leistung[$i][10];   
            }
        }
    } 
    public function check_minmaxvalue(){
        $this->minmaxfullfilled[0] = 0;
        $id=1;
        for ($i=0; $i < sizeof($this->leistung) ; $i++)
        {
            if ($this->leistung[$i][0]>$this->leistung[$i][14] or
                $this->leistung[$i][0]<$this->leistung[$i][15]) 
            {

                $this->minmaxfullfilled[0] = 1;
                $this->minmaxfullfilled[$id] = $this->leistung[$i][1]; 
                $this->minmaxfullfilled[$id+1] = $this->leistung[$i][8]; 
                $id = $id+2;       
            }  
        }
    }

    public function order(){
        $id=0; 
        for ($i=0; $i < sizeof($this->AnzLeiDis); $i++) 
        {        
            if ($this->AnzLeiDis[$i][2]==2 or
                $this->AnzLeiDis[$i][2]==3 or
                $this->AnzLeiDis[$i][2]==4 or
                $this->AnzLeiDis[$i][2]==6 ) 
            {
                for ($j=0; $j < $this->AnzLeiDis[$i][1] ; $j++) 
                { 
                    $temp[$j]=$this->leistung[$id+$j];
                }
                for ($j=0; $j < $this->AnzLeiDis[$i][1] ; $j++) 
                { 
                    $this->leistung[$id+$j]=$temp[$this->AnzLeiDis[$i][1]-$j-1];
                }
            }
            $id = $id+$this->AnzLeiDis[$i][1];
        }
    }

    public function all_second2normal(){
        $id=0; 
        for ($i=0; $i < sizeof($this->AnzLeiDis); $i++) 
        {         
            if ($this->AnzLeiDis[$i][2]==1 or
                $this->AnzLeiDis[$i][2]==5 ) 
            { 
                for ($j=0; $j < $this->AnzLeiDis[$i][1] ; $j++) 
                { 
                    $this->leistung[$id+$j][0] = $this->second2normal($this->leistung[$id+$j][0]);
                }
            }
            $id=$id+$this->AnzLeiDis[$i][1];
        }
    }

    public function all_two_decimal(){
        $id=0; 
        for ($i=0; $i < sizeof($this->AnzLeiDis); $i++) 
        {      
            for ($j=0; $j < $this->AnzLeiDis[$i][1] ; $j++) 
            {  
                $this->leistung[$id+$j][0] = $this->two_decimal($this->AnzLeiDis[$i][2],$this->leistung[$id+$j][0]);
            }
            $id=$id+$this->AnzLeiDis[$i][1];
        }
    }
    
    public function date($format){
        for ($i=0; $i < sizeof($this->leistung); $i++) 
        { 
        // Datum in Format day.month. umwandeln 
            if ($format == 1) 
            {
                $date = explode("-", $this->leistung[$i][7]);
                $this->leistung[$i][7] = implode([$date[2],".",$date[1],"."]); 
            }  // Datum nur Jahr
            elseif ($format > 1) {
                $date = explode("-", $this->leistung[$i][7]);
                $this->leistung[$i][7] = $date[0]; 
            } 
        }
    }

    public function jahrgang_twonumber(){
        for ($i=0; $i < sizeof($this->leistung); $i++) 
        { 
            $jg = $this->leistung[$i][4];
            if ($jg < 2000) 
            {
                $this->leistung[$i][4] = $jg - 1900;
            } else 
            {
                $jg = $jg - 2000;
                if ($jg == 0) 
                {
                    $this->leistung[$i][4] = "00";
                } elseif ($jg > 0 and $jg < 10) 
                {
                    $this->leistung[$i][4] = implode(["0",$jg]);
                } else
                {
                    $this->leistung[$i][4] = $jg;
                }
            }   
        }
    }

    public function one_leistung(){
        $ZeileBestenliste = 0;    // Position in der bestenliste matrix
        $id = 0;                  // Position in der leistung matrix
        for ($k=0; $k < sizeof($this->AnzLeiDis) ; $k++) 
        {   
            $Anzahlgelöscht=0;
            for ($i=0; $i < $this->AnzLeiDis[$k][1]; $i++) 
            {
                if ($i==0) 
                {
                    $this->bestenliste[$ZeileBestenliste] = $this->leistung[$id];
                    $startvergleich = $ZeileBestenliste;
                    $ZeileBestenliste++;
                }else
                {
                    $copy=true;
                    for ($j=$startvergleich; $j < $ZeileBestenliste; $j++) 
                    { 
                        if($this->leistung[$id][1]==$this->bestenliste[$j][1] and $this->leistung[$id][10]!=6)
                        {
                            $copy=false;
                            $Anzahlgelöscht++;
                        }
                    }
                    if ($copy) 
                    {
                        $this->bestenliste[$ZeileBestenliste]=$this->leistung[$id];
                        $ZeileBestenliste++;
                    }
                }
                $id++; 
            }
            $this->AnzLeiDis[$k][3] = $this->AnzLeiDis[$k][1] - $Anzahlgelöscht;      
        }
    }

    public function top(){
        $id = 0;
        if ($this->top < 1000) 
        {     
            $ZeileBestenliste = 0;
            for ($i=0; $i < sizeof($this->AnzLeiDis) ; $i++) 
            { 
                if ($this->AnzLeiDis[$i][3] < $this->top) 
                {
                    $shift = $this->AnzLeiDis[$i][3];
                } else
                {
                    $shift = $this->top;
                }
                $handle = array_slice($this->bestenliste, $ZeileBestenliste , $shift);
                for ($j=0; $j < $shift; $j++) 
                {
                    $this->bestenliste[$id] = $handle[$j];
                    $id++;          
                }     
                $ZeileBestenliste = $ZeileBestenliste + $this->AnzLeiDis[$i][3];
                $this->AnzLeiDis[$i][3] = $shift;
            }   
        $this->bestenliste = array_slice($this->bestenliste, 0 , $id);    
        }
    }    

    public function disziplin(){
        $ZeileBestenliste = 0;  
        $id = 0;                // Zeile in reduzierter neuer Matrix
        $numberdisziplin = sizeof($this->AnzLeiDis);
        for ($i=0; $i < $numberdisziplin ; $i++) { //für jede Disziplin
            //Herausfinden ob disziplin gelöscht werden muss
            $deletedisziplin = true;
            for ($j=0; $j < sizeof($this->disziplin); $j++) { 
                if ($deletedisziplin and $this->bestenliste[$ZeileBestenliste][8] == $this->disziplin[$j]) {
                    $deletedisziplin = false;
                }
            }
            $numleistungen = $this->AnzLeiDis[$i][3];
            if ($deletedisziplin) {
                $this->AnzLeiDis[$i][3] = 0;
            }else{
                $handle = array_slice($this->bestenliste, $ZeileBestenliste , $numleistungen);
                for ($j=0; $j < $numleistungen; $j++) 
                {
                    $this->bestenliste[$id] = $handle[$j];
                    $id++;          
                } 
            }
            $ZeileBestenliste = $ZeileBestenliste + $numleistungen;
        }
        $this->bestenliste = array_slice($this->bestenliste, 0 , $id);  


        //verändere AnzLeiDis für ausgabe, löschen aller zeilen wo in spalte 3 eine null steht
        $ZeileAnzLeiDis = 0; //Zeile in der alten AnzLeiDis Matrix
        $id = 0; //Zeile in der neuen AnzLeiDis Matrix
        for ($j=0; $j < sizeof($this->AnzLeiDis); $j++) { 

            if ($this->AnzLeiDis[$j][3] > 0) {
                $handle = array_slice($this->AnzLeiDis,$ZeileAnzLeiDis ,1 );
                $this->AnzLeiDis[$id] = $handle[0];
                $id++;
            }
            $ZeileAnzLeiDis++;
        }
        $this->AnzLeiDis = array_slice($this->AnzLeiDis, 0 , $id);       
    }   

    public function set_Uberschrift(){
        $texbest = "Bestenliste ";
        if ($this->top==1) {
            $texbest = "Rekorde ";}
        $U="U";
        if ($this->Geschlecht!=$this->Geschlechthelp) {
            $mw=" W/M";
        }elseif($this->Geschlecht==1){
            $mw=" W";
        }else{
            $mw=" M";
        }
        $this->Kategorie=$this->Kategorieo;
        if($this->Kategorieo==200){
            $mw="";
            $U="";
            if($this->Geschlecht==1 and $this->Geschlecht==$this->Geschlechthelp){
                $this->Kategorie=" Frauen";    
            }elseif ($this->Geschlecht!=$this->Geschlechthelp) {
                $this->Kategorie=" Frauen und M&auml;nner";
            }else{
                $this->Kategorie=" M&auml;nner";
            }   
        }
        if ($this->Kategorieo==1000) {
            $U="";
            $this->Kategorie=" alle Alter";
        }
        if ($this->Jahr==0) {
            $this->Jahr="alle Jahre";
        }
        if ($this->top==1000 or $this->top==1 ) {    $textop="";  }
        else{   $textop=implode([" Top",$this->top]);    }
        $arrayUberschrift=array($texbest,$U,$this->Kategorie,$mw,$textop," ",$this->Jahr);
        $this->Uberschrift=implode($arrayUberschrift);
    }

    public function write_textfile(){
        $stufe[0] = "Bestenlisten";
        $stufe[1] = implode("/",[$stufe[0],$this->Jahr]);
        $stufe[2] = implode("/",[$stufe[1],$this->Kategorie]);
        if(!is_dir($stufe[2])){
            if(!is_dir($stufe[1])){
                if(!is_dir($stufe[0])){
                    mkdir($stufe[0]);
                }
                mkdir($stufe[1]);
            }
            mkdir($stufe[2]);
        }
        if ($this->AnzKat > 1) {
            $pfad = implode([$stufe[0],"/","mehrere Kategorien",".txt"]);
        }else{
            $pfad = implode([$stufe[2],"/",$this->Uberschrift,".txt"]);
        }
        $myfile = fopen($pfad, "w") or die("Unable to open file!");
        $ZeileBestenliste = 0;    // Position in der bestenliste matrix
        fwrite($myfile, $this->Uberschrift."\r\n");
        for ($k=0; $k < sizeof($this->AnzLeiDis) ; $k++) 
        {   
            fwrite($myfile,"\r\n");
            fwrite($myfile,$this->bestenliste[$ZeileBestenliste][9]."\r\n");
            for ($i=0; $i < $this->AnzLeiDis[$k][3]; $i++) 
            {   
                $Zeile_best_list = array($this->bestenliste[$ZeileBestenliste][2],$this->bestenliste[$ZeileBestenliste][3],"\t",$this->bestenliste[$ZeileBestenliste][0]);
                $txt=implode(" ",$Zeile_best_list);
                #$txt=implode(" ",$this->bestenliste[$ZeileBestenliste]);
                fwrite($myfile,$txt."\r\n");
                $ZeileBestenliste++;
            }
        }
        fclose($myfile);

    }    

	private function second2normal($time_s){
       //settype($time_s, "double");
		$stunden = $time_s/3600;
        settype($stunden, "integer");
        $minuten = $time_s/60-($stunden*60);
        settype($minuten, "integer");
        $sekunden = $time_s - $stunden*3600 - $minuten*60;
        settype($sekunden, "integer");
        $hundertstel = round (100*($time_s - $stunden*6000 - $minuten*60 - $sekunden) , 0);
        settype($hundertstel, "integer");

        if($hundertstel==0)  // Falls keine hunderstel Voranden,
        {                     // füge hinten zwei nullen an
            $hundertstel = "00";
        }
        if($hundertstel==1 or $hundertstel==2 or $hundertstel==3 or
           $hundertstel==4 or $hundertstel==5 or $hundertstel==6 or
           $hundertstel==7 or $hundertstel==8 or $hundertstel==9)
        {    
          	$hundertstel = implode(["0",$hundertstel]);
        }
        
        if ($time_s > 60) 
        { 
            if($sekunden==0)  
            {   
                $sekunden = "00";
            }
            if($sekunden==1 or $sekunden==2 or $sekunden==3 or
               $sekunden==4 or $sekunden==5 or $sekunden==6 or
               $sekunden==7 or $sekunden==8 or $sekunden==9)
            {   
                $sekunden = implode(["0",$sekunden]);
            }  
            if ($time_s > 3600) 
            {
                if($minuten==0)  {   $sekunden = "00";}
                if($minuten==1 or $minuten==2 or $minuten==3 or
                   $minuten==4 or $minuten==5 or $minuten==6 or
                   $minuten==7 or $minuten==8 or $minuten==9)
                {       
                    $minuten = implode(["0",$minuten]);
                } 
                return implode([$stunden,":",$minuten,":",$sekunden,".",$hundertstel]);
            }else{      // 60s < $time_s < 3600s
                return implode([$minuten,":",$sekunden,".",$hundertstel]);
            }
        }else{          // $time_s  < 60s
            return implode([$sekunden,".",$hundertstel]);
        }
	}

	private function two_decimal($disziplin_typ, $leistung){
		if ($disziplin_typ==2 or
            $disziplin_typ==3) 
        {
            $rundleistung = $leistung;
            settype($rundleistung, "integer");
            $hundertstel = $leistung-$rundleistung;
            $hundertstel = round($hundertstel,2);

            if($hundertstel==0.1 or $hundertstel==0.2 or $hundertstel==0.3 or
               $hundertstel==0.4 or $hundertstel==0.5 or $hundertstel==0.6 or
               $hundertstel==0.7 or $hundertstel==0.8 or $hundertstel==0.9 )  
            {
                $arrayleistung = array($leistung,"0");
                $leistung = implode("",$arrayleistung);
            }
            if($hundertstel == 0)  
            {                     
                $arraysekunde = array($rundleistung,".00");
                $leistung = implode("",$arraysekunde);
            }    
        }
        return $leistung;   
	}
}

?>