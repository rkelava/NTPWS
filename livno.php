<?php

#zaustavljanje pokušanja hakiranja
define('__APP__', TRUE);

#pokretanje sesija
session_start();

#konekcija na bazu
include ("dbconn.php");

if(isset($_GET['menu'])) { 
	$menu   = (int)$_GET['menu'];
}

if(isset($_GET['action'])) { 
	$action   = (int)$_GET['action']; 
}

if(!isset($_POST['_action_']))  { 
	$_POST['_action_'] = FALSE;  
}
	
if (!isset($menu)) { 
	$menu = 1; 
}

include_once("functions.php");

print'
<!DOCTYPE html>
<html>
<head>  <!--dovrsiti --->
    <!-- css -->
    <link rel="stylesheet" href="css1.css">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <meta charset="utf-8"/>
    <meta http-equiv="content-type" content="text/html>
    <meta name="author" content="Ruža Kelava">
    <meta name="description" content="O gradu Livnu i njegovim ljepotama">
	<script src="https://kit.fontawesome.com/418c2c45d8.js" crossorigin="anonymous"></script>
    <meta name="keywords" content="Livno, Bosna, Hercegovina, BiH, Konji, Sir, Zlatko, Dalic, Rimac">   
	<!-- favicon -->
	<link rel="icon" type="image/png" sizes="16x16" href="slike/favicon-16x16.png">
	

<title>Livno</title>
</head>
<body>  <!--dovrsiti --->
   <header>
   
    <div'; if ($_GET['menu'] > 1) { print ' class="hero-subimage"'; } else { print ' class="hero-image"'; }  print '></div>
		<nav>';
		 include("menu.php");
		print '
		</nav>
    
   </header>

   <main>'; 
   
    if (isset($_SESSION['message'])) {
		print $_SESSION['message'];
		unset($_SESSION['message']);
	}

   
   # Naslovna
	if (!isset($menu) || $menu == 1) { include("naslovna.php"); }
	
	# Novosti
	else if ($menu == 2) { include("novosti.php"); }
	
	# Kontakt
	else if ($menu == 3) { include("kontakt.php"); }
	
	# O nama
	else if ($menu == 4) { include("o-nama.php"); }

	# Galerija
	else if ($menu == 5) { include("galerija.php"); }
	
	# Registracija
	else if ($menu == 6) { include("registracija.php"); }
	
	# Prijava
	else if ($menu == 7) { include("prijava.php"); }
	
	# Admin
	else if ($menu == 8) { include("admin.php"); }

	#poslanKontakt
	else if($menu == 9) { include("poslanoKontakt.php"); } 

    print'
   </main>

   <footer> <!--dovrsiti --->
        
		<p>Copyright &copy; ' . date("Y") . ' Ruža Kelava. <a href=""> <i class="fa-brands fa-github"></i></a></p>
	</footer>
</body>
</html>
'

;

?>