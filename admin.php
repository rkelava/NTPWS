<?php 
	if ($_SESSION['user']['valid'] == 'true') {
		#if (!isset($action)) { $action = 1; }
		print '
		<h1>Administracija</h1>
		<div id="admin">
			<ul>
				<li><a href="livno.php?menu=8&amp;action=1">Korisnici</a></li>
				<li><a href="livno.php?menu=8&amp;action=2">Novosti</a></li>
			</ul>';
			# Admin Users
			if(isset($action)){if ($action == 1) { include("admin/korisnici.php"); }
			
			# Admin News
			else if ($action == 2) { include("admin/novost.php"); }}
			
		print '
		</div>';
	}
	else {
		$_SESSION['message'] = '<p>Please register or login using your credentials!</p>';
		header("Location: livno.php?menu=6");
	}
?>