<?php
/**
* 
*/
class best_list
{
	private $raw;
	private $list;
	private $best_list_title;
	private $title = [];	
	private $record = FALSE;
	function __construct($array)
	{
		$this->raw = $array;
		if ($array != NULL) {
			foreach (array_keys($array[0]) as $key => $value) {
				$this->title[$value] = $value;
			}

			$disziplin_bins = [];
			$disziplin_names = [];
			$last_dis_id = "_";
			foreach ($array as $key => $value) {
			    if($last_dis_id != $value['DisziplinID']){
			        array_push($disziplin_bins, $key);
			        array_push($disziplin_names, $value['Disziplin']);
			        $last_dis_id = $value['DisziplinID'];
			    }
			}
			array_push($disziplin_bins, sizeof($array));

			foreach ($disziplin_names as $key => $value) {
			    $this->list[$value] = array_slice($array, $disziplin_bins[$key],$disziplin_bins[$key+1]-$disziplin_bins[$key]);
			}

		}
	}
	private function unique_multidim_array($array, $key) 
	{
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();
	   
	    foreach($array as $val) {
	        if (!in_array($val[$key], $key_array)) {
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
	} 

	function one_per_mit_and_dis($arg = 'Mitglied')
	{
		foreach ($this->list as $key => $value) {
			$this->list[$key] = $this->unique_multidim_array($value, $arg);
		} 
	}
	function top($arg)
	{
		$this->record = ($arg == 'record')? TRUE : FALSE;
		$top = ($arg == 'record')? 1 : $arg;
		foreach ($this->list as $key => $value) {
			$this->list[$key] = array_slice($this->list[$key], 0, $top);
		}
	}
	private function two_digits_end($perf)
	{
		$splited = explode(".", $perf);
		(sizeof($splited)!=1)? :$splited[1]= "00";
		if(strlen($splited[1])<2){
			$splited[1]=$splited[1]."0";
			return $this->two_digits_end(implode(".", $splited));
		}else{
			return implode(".", $splited);
		}
	}

	private function second_2_normal($seconds)
	{
		$hours = floor($seconds / 3600);
		$mins = floor($seconds / 60 % 60);
		$secs = floor($seconds % 60);
		$hund = explode(".", $seconds);
		(sizeof($hund)!=1)? :$hund[1]= "00";
		if ($seconds<10) {
			return sprintf('%01d.%02d', $secs, $hund[1]);
		} elseif ($seconds<60) {
			return sprintf('%02d.%02d', $secs, $hund[1]);
		} elseif ($seconds<600) {
			return sprintf('%01d:%02d.%02d', $mins, $secs, $hund[1]);
		} elseif ($seconds<3600) {
			return sprintf('%02d:%02d.%02d', $mins, $secs, $hund[1]);
		} elseif ($seconds<36000) {
			return sprintf('%01d:%02d:%02d.%02d', $hours, $mins, $secs, $hund[1]);
		} else{
			return "CONVERSION ERROR: more than 10 hours...";
		}	
	}
	function format_values_two_digits()
	{
		foreach ($this->list as $dis_name => $dis) {
			foreach ($dis as $key => $value) {
				$this->list[$dis_name][$key]['Leistung'] = $this->two_digits_end($value['Leistung']);
			}
		}
	}
	function format_values_time()
	{
		foreach ($this->list as $dis_name => $dis) {
			foreach ($dis as $key => $value) {		
				$this->list[$dis_name][$key]['Leistung'] = $this->second_2_normal($value['Leistung']);
			}
		}
	}
	private function date_format($arg, $format = 'j.n.Y') 
	/* $Format:
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	d 	Tag des Monats, zweistellig 	04, 15
	j 	Tag des Monats, einstellig 	5, 23
	m 	Nummer des Monats, zweistellig 	05, 12
	n 	Nummer des Monats, einstellig 	4, 11
	y 	Jahr, zweistellig 	98, 05
	Y 	Jahr, vierstellig 	1998, 2005
	*/
	{
		$date = new DateTime($arg);
		if ($date->format('j.n') == '1.1' OR $this->record) // all performances from an old best list or if a recor is asked
		{
			return $date->format('Y');	
		} else {
			return $date->format($format);
		}
	}
	function date($format = 'j.n.Y'){
		foreach ($this->list as $dis_name => $dis) {
			foreach ($dis as $key => $value) {		
				$this->list[$dis_name][$key]['Datum'] = $this->date_format($value['Datum'], $format);
			}
		}
	}
	function add_list($best_list_2)
	{
		$this->raw = array_merge($this->raw, $best_list_2->get_raw());
		$this->list = array_merge($this->list, $best_list_2->get_list());
	}
	
	function create_best_list_title ($sex_in, $kat_array, $year_array=0, $top_in="all"){
		$core = ($this->record)? "Rekord ":"Bestenliste ";
		switch ($sex_in) {
			case 'all':
				$sex = " W & M ";
				break;
			case 1:
				$sex = " Weiblich ";
				break;
			case 2:
				$sex = " Männlich "; 
				break;
			default:
				echo "Kein Geschlecht gew$hlt";
		}
		$kat = "";
		foreach ($kat_array as $key => $one_kat) {
			$kat .= ($key==0)? "":", ";
			if($one_kat =="aktiv"){
				switch ($sex_in) {
					case 'all':
						$kat = "Frauen und Männer ";
						break;
					case 1:
						$kat = "Frauen ";
						break;
					case 2:
						$kat = "Männer "; 
						break;
					default:
						echo "Keine Kategorie gew$hlt";
				}
				$sex = "";
			}else{
				$kat .= "U".$one_kat;
			}
		}
		if($kat_array[0]=="all"){
			$kat = "Alle Kategorien ";
		}

		

		$year = ($this->record)? "":$year_array[0];
		switch ($top_in) {
			case '1001':
				$top = "";
				break;
			case 'record':
				$top = "";
				break;
			default:
				$top = " Top ".$top_in;
				break;
		}
		$this->best_list_title = $core.$kat.$sex.$year.$top;
		return $this->best_list_title;
	}

	function create_html_list ($columns, $columns_records){
		if ($this->record) {
			$out = $this->create_html_list_record($columns_records);
		}else{
			$out = $this->create_html_list_best_list($columns);
		}
		return $out;
	}

	function create_html_list_best_list($columns) //if a column is not existing in the list array then the sql code has to be changed such that more elements are given out from the database.
	{
		//check if the $colums are valid arguments
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------

		//TO DOOOOOOOOOOO


		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------




	    $out = "";
	    $out .= "<table>";
	    $out .= "<thead><tr>";
	    foreach($columns as $col_key => $column)
	    {
	    $out  .= "<th>".$this->title[$column]."</th>";
	    }
	    $out .= "</tr></thead><tbody>";
	    // print the best list for each disziplin 
	    foreach($this->list as $key => $dis)
	    {
	    	// Prints the Disziplin on a seperate Line above the best list
	    	$out .= "<tr><td colspan=".sizeof($columns)."><b>".$key."</b></td></tr>";
	    	// print out best list in one disziplin. Key is the number (similar to the rank and the element is an array containing all information about the dataset (Leistung, Name, Ort,...))
	    	foreach ($dis as $key => $element) {
	    		$out .= "<tr>";
	    		foreach($columns as $col_key => $column)
		    	{
		    		$out .= "<td>".$element[$column]."</td>";
		    	}
		    	$out .= "</tr>";
	    	}
	    }
	    $out .="</tbody></table>";
	    return $out;
	}
	function create_html_list_record($columns) //if a column is not existing in the list array then the sql code has to be changed such that more elements are given out from the database.
	{
		//check if the $colums are valid arguments
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------

		//TO DOOOOOOOOOOO


		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------
		//-----------------------------------




	    $out = "";
	    $out .= "<table>";
	    $out .= "<tr><th></th>";
	    foreach($columns as $col_key => $column)
	    {
	    $out  .= "<th>".$this->title[$column]."</th>";
	    }
	    $out .= "</tr>";
	    // print the best list for each disziplin 
	    foreach($this->list as $key => $dis)
	    {
	    	// Prints the Disziplin on a seperate Line above the best list
	    	$out .= "<tr><td><b>".$key."</td></b>";
	    	// print out best list in one disziplin. Key is the number (similar to the rank and the element is an array containing all information about the dataset (Leistung, Name, Ort,...))
	    	foreach ($dis as $key => $element) {
	    		foreach($columns as $col_key => $column)
		    	{
		    		$out .= "<td>".$element[$column]."</td>";
		    	}
		    	$out .= "</tr>";
	    	}
	    }
	    $out .="<table>";

	    return $out;
	}

	function create_plain_txt($path)
	{
		$myfile = fopen($path, "w") or die("Unable to open file!");
		fwrite($myfile, "\r\n");
	    fwrite($myfile,$this->best_list_title."\t\t"."Erstellt am: ".date("d.m.Y")."\r\n");
	    $list_array = $this->get_list();
	    foreach ($list_array as $DisziplinName => $list) {
	        fwrite($myfile,"\n");
	        fwrite($myfile,utf8_decode($DisziplinName."\n"));
	        foreach ($list as $key => $line) {
	        	// Change Information in Best List .TXT HERE!!!
	            $a = implode("\t ", array($line["Vorname"]." ".$line["Name"], $line["Leistung"]));
	            $b = implode("\t ", array($line["Leistung"], $line["Vorname"], $line["Name"], $line["Jg"], $line["Datum"], $line["Ort"]));

	            fwrite($myfile,utf8_decode($a."\n"));

	        }
	    }
	    fclose($myfile);
	    return "Success: Text File Created!";
	}
	function get_raw()
	{
		return $this->raw;
	}
	function get_list()
	{
		return $this->list;
	}
}



?>