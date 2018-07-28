<!Doctype html>
<html>
<head>
<meta charset="utf-8">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script type="text/javascript" src="inc/jquery.js"></script>
<script type="text/javascript" src="inc/front.js"></script>

</head>

<body>
<form  method="POST" action="eingabe_alte_bestenliste.php">
<div id="eingaben">
	<div id="mitglied">
		<h4>Mitglieder</h4>
		<div id="Jahr" >
			<b>Year</b>
			<input type="number" id='year' name="year" min='1950' max='2020' value='2015' size='4' oninput='eval_mit()'>
			
		</div>
		<br>
		<div>
			<b>Kategorie</b> <br>
			<input type='checkbox' id='U10' name='kat' value='10' onclick='eval_mit()'> U10 <br>
			<input type='checkbox' id='U12' name='kat' value='12' onclick='eval_mit()'> U12 <br>
			<input type='checkbox' id='U14' name='kat' value='14' onclick='eval_mit()'> U14 <br>
			<input type='checkbox' id='U16' name='kat' value='16' onclick='eval_mit()'> U16 <br>
			<input type='checkbox' id='U18' name='kat' value='18' onclick='eval_mit()'> U18 <br>
			<input type='checkbox' id='U20' name='kat' value='20' onclick='eval_mit()'> U20 <br>		
			<input type='checkbox' id='woman' name='kat' value='201' onclick='eval_mit()'> Frauen <br>
			<input type='checkbox' id='men' name='kat' value='202' onclick='eval_mit()'> M&aumlnner <br>
		</div>
		<br/>
		<div>
			<b>Geschlecht</b> <br>
			<input type='checkbox' id='female' name='sex' value='1' onclick='eval_mit()'> weiblich <br>
			<input type='checkbox' id='male' name='sex' value='2' onclick='eval_mit()'> männlich <br>
		</div>
	</div>
</div>	
<div id="result" >
	
</div>
</form>

<div id="bestenliste">
	<input type="button" id="search" onclick="search_leistung()" value="Daten ausgeben">

	<div id="result_best">
	</div>





</div>

<a href="http://localhost/bestenliste/"><input type="button" value="&Uuml;bersicht"/></a>

</body>
</html>