<!Doctype html>
<head>
    <title>Eingabe der Leistungen</title>
	<script type="text/javascript" src="jquery-3.2.1.js"></script>
	<script>
		$(document).ready(function() {
			$("#check_un").click(function(event) {
				alert("Checking username for availability");
			event.preventDefault();
			});
		});

		$("#check_un").mouseover(function(event){
			alert("Interested in this image, are ya?");
		});
	</script>

</head>



<body>
    <h2> Eingabe Leistungen TVU Bestenliste </h2>
    

<a href="#" class="button" id="check_un">Check
Username Availability</a>



<?php
// If the name field is filled in
if (isset($_POST['name']))
{
$name = $_POST['name'];
$email = $_POST['email'];
printf("Hi %s! <br />", $name);
printf("The address %s will soon be a spam-magnet! <br />", $email);
}
?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <p>
        Name:<br />
        <input type="text" id="name" name="name" size="20" maxlength="40" />
        </p>
        <p>
        Email Address:<br />
        <input type="text" id="email" name="email" size="20" maxlength="40" />
        </p>
        <input type="submit" id="submit" name = "submit" value="Go!" />


    </form>
<br />     
</body>


