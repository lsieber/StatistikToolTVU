<!Doctype html>
<head>
    <title>Eingabe der Leistungen</title>

</head>



<body>
    <h2> Eingabe Leistungen TVU Bestenliste </h2>
    
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


