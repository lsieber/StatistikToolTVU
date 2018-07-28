<!Doctype html>

<head>

<title>Eingabe von Mehrkampfleistungen</title>

</head>

<body>
    
    <h2>
        Eingabe Leistungen TVU Bestenliste
    </h2>
    
    <form  method="POST" action="empfanger.php">
        
        <td >
            <input type="text" size="7" name="1" id="1"/>  <br/>
            <input type="text" size="7" name="2" id="2"/>  <br/>
            <input type="submit" value="Eingabe" />
        </td>
        <?php
        SESSION_START();

        $var=33;
        echo ($var);
        $_SESSION["testvariable"]=$var;
        ?>

    </form>
<br />

        
</body>


