<html>
<head>
<meta name="viewport" content="width=device-width" />
<title>Trigger</title>
</head>
     <body>
     Trigger switch:
     <form method="get" action="switch.php">
             <input type="submit" value="Trigger" name="switch">
     </form>
     <?php
     $setmode17 = shell_exec("/usr/local/bin/gpio -g mode 17 out");
     if(isset($_GET['switch'])){
             $gpio_off = shell_exec("/usr/local/bin/gpio -g write 17 1");
             sleep (0.5);
             $gpio_on = shell_exec("/usr/local/bin/gpio -g write 17 0");
             echo "Done!";
     }
     ?>
     </body>
</html>
