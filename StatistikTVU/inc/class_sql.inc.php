<?php
class last_3_entries extends sql_generator
{
    function __construct( $select= "*",$table = "bestenliste b", $order = 'b.ID DESC LIMIT 7' )
    {

        $this->set_order($order);
        $this->set_inner_join("");
		$this->add_statement_INNER_JOIN("mitglied m", "b.Mitglied = m.ID");
		$this->add_statement_INNER_JOIN("wettkampf w","b.Wettkampf = w.ID");
		$this->add_statement_INNER_JOIN("disziplin d","b.DisziplinID = d.ID");        
		$this->set_select($select);
        $this->set_from($table);

        $this->combine_where("WHERE");
    }
}

class sql_list_performance_wettkampf extends sql_generator
{
    function __construct( $id_wk="", $select= "*",$table = "bestenliste b", $order = 'b.ID' )
    {
        $this->set_order($order);
//		$this->add_statement_INNER_JOIN("mitglied m", "b.Mitglied = m.ID");
//		$this->add_statement_INNER_JOIN("wettkampf w","b.Wettkampf = w.ID");
//		$this->add_statement_INNER_JOIN("disziplin d","b.DisziplinID = d.ID");        
		$this->set_select($select);
        $this->set_from($table);

        $this->set_where_2($this->statement_equal_value($id_wk,'Wettkampf'));

        $this->combine_where("WHERE");
    }
}
/**
* 
*/
class sql_disziplin extends sql_generator
{
    private $kat_dic =array("10"=>"U10", "12"=>"U12", "14"=>"U14","16"=>"U16","18"=> "U18", "20"=>"U20", "wom"=>"Frauen", "men"=>"Manner");
    function __construct($kategorie="", $select= "*",$table = "disziplin", $order = 'Lauf, Laufsort, Disziplin' )
    {
        $this->set_order($order);
        $this->set_inner_join("");
        $this->set_select($select);
        $this->set_from($table);

        $kat_db_array=array();
        if (gettype($kategorie)=="array") {
            foreach ($kategorie as $key => $value) {
                $kat_array = $this->kat_form_to_db($value);
                $kat_db_array = array_merge($kat_db_array,$kat_array);
            }
            $kat_db_array = array_unique($kat_db_array);
        } else {
            $kat_db_array = $this->kat_form_to_db($kategorie);
        }
        $j = 1;
        foreach ($kat_db_array as $key => $value) {
            if ($j==1)
            {
                $dis_in_kat = new statement("WHERE",$value,"=",'1','OR');
            } else {
                $st2 = new statement("WHERE",$value,"=",'1','OR');
                $dis_in_kat = $dis_in_kat->append_statement($st2, $new_logic = "OR",$brackets = TRUE);
            }
            $j++;
        }
        $this->set_where_2($dis_in_kat);
        $this->combine_where("WHERE");
    }
    function kat_form_to_db($kat_form)
    {
        if ($this->kat_dic[$kat_form]=="Männer" OR $this->kat_dic[$kat_form]=="Frauen"){
            $kat_db = array($this->kat_dic[$kat_form],"U20","U18");  
        } else {
            $kat_db = array($this->kat_dic[$kat_form]);
        }
        return $kat_db;
    }
}
/**
* 
*/
class sql_mitglied extends sql_generator
{
    protected $year;
    protected $kategorie;
    protected $sex;
    protected $order;
    protected $select;
    protected $from;

    function __construct( $year="", $kategorie="", $sex="all", $select= "*",$table = "mitglied", $order = 'Geschlecht, Vorname, Name' )
    {
        $this->year=$year;
        $this->kategorie=$kategorie;
        $this->sex=$sex;
        $this->order = $order;
        $this->from = $table;
        $this->select = $select;

        $this->set_order($order);
        $this->set_inner_join("");
        $this->set_select($select);
        $this->set_from($table);

        $this->set_where_2(new statement("WHERE",'Jg+aktiv+16',">",$year));
        $this->statement_age_array($kategorie, $year= $year);
        $this->statement_sex($sex);

        $this->combine_where("WHERE");
        
    }
    function get_sql_for_teams()
    {
        $teams = new sql_generator();
        $teams->set_select($this->select->sql);
        $teams->set_from($this->from->sql);
        $teams->set_order($this->order->sql);
        $teams->set_inner_join("");

        if ($this->sex == "all") {
            $team_gesl = array(3,4,5);
        } else{
            $team_gesl = array($this->sex+2,5);
        }
        $teams->set_where_2($this->statement_IN_array($team_gesl, 'Geschlecht'));
        $teams->set_where_3($this->statement_equal_value($this->year,'Jg'));

        $kat_dic =array("10"=>"1", "12"=>"2", "14"=>"3","16"=>"4","18"=>"5", "20"=>"6","aktiv"=>"7");
        $kat = ($this->kategorie[0]=='aktiv')? array(5,6,7):$kat_dic[$this->kategorie[0]];
        $teams->set_where_age($this->statement_IN_array($kat,'(ID-Jg-1000*Geschlecht-10000+1950)/100'));
  
        $teams->combine_where("WHERE");

        return $teams->get_sql();
    }
}

class sql_wettkampf extends sql_generator
{
    function __construct($select = "*", $Jahr="", $table = "wettkampf")
    {
        $this->set_order("Datum");
        $this->set_inner_join("");
        $this->set_select($select);
        $this->set_from($table);      
        $this->set_where($this->statement_equal_value(1, 'sichtbar', $logic="", $where = "WHERE"));        
    }
}

class sql_get_distinct_dataset extends sql_generator
{
    function __construct($table, $id, $select)
    {
        $this->set_order("");
        $this->set_inner_join("");
        $this->set_select($select);
        $this->set_from($table);
        $this->set_where(new statement("WHERE", "ID", "=", $id, ""));
    }
}


class sql_generator
{   
    const AKTIV_AGE = 16;
    const MIN_AGE = 5;
    protected  $select;
    protected  $from;
    protected  $order;
    protected  $inner_join="";
    protected  $where_lauf;
    protected  $where_sex;
    protected  $where_age;
    protected  $where_year;
    protected  $where_mitglied;
    protected  $where_dis;
    protected  $where_2;
    protected  $where_3;
    protected  $where;

    protected  $top;

    protected  $sql_where = "";       
    protected  $sql ="";
    function __construct()
    {
    	$this->set_select("*");
    	$this->set_from("bestenliste");
    	$this->set_order("");
    	$this->set_inner_join("");
    }
    function set_select($arg)
    {
        $this->select = new statement("SELECT",$arg,"","","");
    }
    function set_from($arg)
    {
        $this->from = new statement("FROM", $arg,"","","");
    }
    function set_order($arg)
    {
        $this->order = new statement("ORDER BY", $arg,"","","");
    }
    function set_inner_join($arg)
    {
        $this->inner_join = new statement("INNER_JOIN", $arg,"","","");
    }
    function set_where_sex($arg)
    {
        $this->where_sex = $arg;
    }
    function set_where_year($arg)
    {
        $this->where_year = $arg;
    }
    function set_where_mitglied($arg)
    {
        $this->where_mitglied = $arg;
    }
    function set_where_age($arg)
    {
        $this->where_age = $arg;
    }
    function set_where_dis($arg)
    {
        $this->where_dis = $arg;
    }
    function set_where_lauf($arg)
    {
        $this->where_lauf = $arg;
    }
    function set_where_2($arg)
    {
        $this->where_2 = $arg;
    }
    function set_where_3($arg)
    {
        $this->where_3 = $arg;
    }
    function set_where($arg)    // only for use if the where statement is set manually and not with the combine_where() fuction
    {
        $this->where = $arg;
    }
    function set_group_by($arg)
    {
        $this->group_by = new statement("GROUP BY", implode(", ",$arg),"","","");
    }

    function add_statement_INNER_JOIN($left_side,$right_side)
    {
        if (gettype($this->inner_join) == "string" OR $this->inner_join->sql == "") {
            $this->inner_join = new statement("INNER JOIN", $left_side, " ON ", $right_side,"");
        } else {
            $stb = new statement("INNER JOIN", " INNER JOIN ". $left_side, " ON ", $right_side,"");
            $this->inner_join = $this->inner_join->append_statement($stb,"");
        }

    }

    function statement_age_array($array, $year = "Jahr", $logic="AND",$logic_between_ages="OR", $return = FALSE)
    {
        if ($array[0] == 'all') {
            return new statement();
        }
        foreach ($array as $key => $value) {
            if ($key==0) 
            {
                $statement_age_array = $this->statement_age($value,$year=$year);
            } 
            else 
            {
                $statement_age_array = $statement_age_array->append_statement($this->statement_age($value,$year=$year), $logic_between_ages, $brackets=TRUE);
            }
        }
        $statement_age_array->logic = $logic;
        $this->where_age = $statement_age_array;
        if($return){return $statement_age_array;}
    }

    function statement_age($kat,$year="Jahr")
    {
        if ($kat=="aktiv") 
        {
            $age = new statement("WHERE", $year."-Jg",">",self::AKTIV_AGE-1,"OR");
        }
        else
        {
            if($kat == 10)
            {
                $age_lower = new statement("WHERE", $year."-Jg",">",self::MIN_AGE);
                $age_upper = new statement("WHERE", $year."-Jg","<",$kat);
            }
            else
            {
                $age_lower = new statement("WHERE", $year."-Jg",">",$kat-3);
                $age_upper = new statement("WHERE", $year."-Jg","<",$kat);
            }
            $age = $age_lower->append_statement($age_upper,"OR");
        }
        return $age;
    }

    function statement_sex($geschlecht, $return = FALSE)
    {
        if ($geschlecht == 'all') {
            $sex = new statement();
        } elseif ($geschlecht == 1 OR $geschlecht ==2) {
            $sex = new statement("WHERE", "Geschlecht", "=", $geschlecht, "OR");
            $sex2 = new statement("WHERE", "Geschlecht", "=", $geschlecht+2, "OR");
            $sex3 = new statement("WHERE", "Geschlecht", "=", 5, "OR");
            $sex = $sex->append_statement($sex2,"OR"); //3 stands for the mixed sex groups which are saved in the mitglied db.
            $sex = $sex->append_statement($sex3,"AND");
        } else {echo "ERROR something went WRONG HERE in the statment_sex function in the sql class!";}
        
        if($return){return $sex;}else{$this->where_sex = $sex;}
    }

    function statement_IN_array($array, $variable_DB, $logic="AND")
    {
        (sizeof($array) == 1) ? $list = $array[0] : $list = implode(",",$array);
        ($array[0] == 'all') ? $st = new statement() : $st = new statement("WHERE", $variable_DB , " IN ","(". $list.")", $logic);
        return $st;
    }
    function statement_equal_value($value, $variable_DB, $logic="AND", $where = "WHERE")
    {
        ($value != "") ? $st = new statement($where, $variable_DB, " = ", $value, $logic): $st = new statement();
        return $st;
    }

    function combine_where($arg="WHERE")
    {
        $i=0;
        $a="";
        foreach($this as $key => $value) {
            gettype($value)=="object"? $a = get_class($value) : $a = "no object";
            if ($a == "statement") {
                if ($value->type == $arg && $value != $this->where) {
                    if($i == 0)
                    {
                        $this->where = $value;
                    }else{
                        $this->where = $this->where->append_statement($value,"AND",$brackets=TRUE);
                    }
                    $i=$i+1;
                }
            }
       }
       $i==0 ? $this->where = new statement("","","","",$logic="") : $this->where->logic = "";
    }
    function combine_sql()
    {
        $this->sql = $this->select->get_sql_first().$this->from->get_sql_first().$this->inner_join->get_sql_first().$this->where->get_sql_first().$this->order->get_sql_first().";";
    }
    function get_sql()
    {
        $this->combine_sql();
        return $this->sql;
    }
    function get_inner_join()
    {
        print_r($this->inner_join);
       // return $this->inner_join->sql;
    }
}




class statement
{   
    public $type;
    public $sql;
    public $logic;
    function __construct($type="", $left_side="", $sign="", $right_side="", $logic = "AND")
    {
        $this->type = $type;
        $this->sql = $left_side.$sign.$right_side;
        $this->logic = $logic;
    }
    function get_sql()
    {
    	return $this->sql;
    }
    function get_attributes()
    {
        echo "</br> <b>Type:</b> ". $this->type;
        echo "</br> <b>SQL:</b> ". $this->sql;
        echo "</br> <b>Logic:</b> ". $this->logic. "</br>";
    }

    function append_statement($st2, $new_logic = "AND",$brackets = FALSE)
    {
        if ($this->type == $st2->type && $this->logic == $st2->logic && $st2->sql != "") 
        {
            if($brackets)
            {
                return new statement($this->type,"(".$this->sql.")", " ".$this->logic." ", "(".$st2->sql.")", $new_logic);
            } 
            else
            { 
                return new statement($this->type,$this->sql, " ".$this->logic." ", $st2->sql, $new_logic);
            }
        } 
        elseif($st2->sql == "")
        {
            return self;
        }
        else 
        {
            echo "ERROR: combination of two statements not successfull";
        }
    }
    function get_sql_first()
    {
        if($this->sql!=""){
        return $this->type." ".$this->sql." ".$this->logic." ";
        }
        else{
            return "";
        }

    }
}

?>