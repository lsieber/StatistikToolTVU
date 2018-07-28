<?php

/**
* Class for all the links used within the statistic tool
*/
class link
{
    // ---------------------------------------------------------------------
    /* Change the path to the corresponding Website here. Relative and absolute paths are both possible. A link is the creted with a link_WEBSITENAME() function as defined below.
    */
    const PATH_PHPMYADMIN = "http://localhost/phpmyadmin/db_structure.php?server=1&db=bestenliste&token=4b8fa35d2b798669af3cf5e9e487b270";
    const PATH_OUTPUT = "output.php";
    const PATH_INPUT = "input.php";
    const PATH_INDEX = "http://localhost/statistik_tool_tvu/index.php";
    const PATH_TEAM_INPUT_SCRIPT = "show_mitglied.php";


    // ---------------------------------------------------------------------

    private $server = "localhost";
    private $url = "/statistik_tool_tvu/"; 

    // -------------------- constructor ------------------------------------
    function __construct()
    {
    }
    // -------------------- Basic Link Configuration -----------------------
    function create_link($path, $link_text, $target = '_blank')
    {
        echo "<p><a href=". $path ." target=$target>". $link_text ."</a></p>";
    }
    // -------------------- Functions for the different Links --------------
    function link_phpmyadmin($target = '_blank')
    {
        self::create_link(self::PATH_PHPMYADMIN, "php my admin", $target);
    }
    function link_output($target = '_top')
    {
        self::create_link($this->create_abs_path(self::PATH_OUTPUT), "Bestenlisten Tool", $target);
    }
    function link_input($target = '_top')
    {
        self::create_link($this->create_abs_path(self::PATH_INPUT), "Eingabe der Leistungen", $target);
    }
    function link_index($target = '_top')
    {
        self::create_link(self::PATH_INDEX, "zur Übersicht", $target);
    }
    function link_team_input($target = '_blank')
    {
        self::create_link($this->create_abs_path(self::PATH_TEAM_INPUT_SCRIPT), "input team script", $target);
    }
    
    // ---------- creates the absolute path out of the relative path ----
    function create_abs_path($rel_path)
    {
        return "http://".$this->server.$this->url.$rel_path;
    }

    // ---------------- get the atributes from the class -----------------
    function get_server()
    {
        echo $this->server. "\n";
    }
    function get_attributes()
    {
        echo "<p><b>PATH_PHPMYADMIN is: </b>". self::PATH_PHPMYADMIN."</p>";
        echo "<p><b>server is: </b>". $this->server ."</p>";
        echo "<p><b>url is: </b>". $this->url ."</p>";
    }
}

?>