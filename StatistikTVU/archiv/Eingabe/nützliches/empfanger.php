<!Doctype html>

<head>

<title>empfangen von Mehrkampfleistungen</title>

</head>

<body>

    
    <h2>
        Empfang Leistungen TVU Bestenliste
    </h2>

	<?php

	SESSION_START();

		$postwettkampf=$_POST["1"];
		var_dump($postwettkampf);
		$postwettkampf=$_POST["2"];
		var_dump($postwettkampf);
		

		$test=$_SESSION["testvariable"];
		echo ($test);
		var_dump($test);
	?>

<br />

        
</body>