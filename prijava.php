<?php 
	print '
	<h1> Prijava </h1>
	<div id="prijava">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" name="prijavaform" id="prijavaform" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">

			<label for="korisnickoIme">Korisnicko Ime:*</label>
			<input type="text" id="korisnickoIme" name="korisnickoIme" value="" pattern=".{5,10}" required>
									
			<label for="lozinka">Lozinka:*</label>
			<input type="password" id="lozinka" name="lozinka" value="" pattern=".{4,}" required>
									
			<input type="submit" value="Prijavi se">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE korisnickoIme='" .  $_POST['korisnickoIme'] . "'";
        #$query .= " AND arhiva='N'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (password_verify($_POST['lozinka'], $row['lozinka'])) {
			#password_verify https://secure.php.net/manual/en/function.password-verify.php
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['rola'] = $row['id_role'];
			$_SESSION['user']['ime'] = $row['ime'];
			$_SESSION['user']['prezime'] = $row['prezime'];
			$_SESSION['message'] = ' <p>Dobrodošli, ' . $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime'] . '</p> ';
			# Redirect to admin website
			header("Location: livno.php?menu=8");
		}
		
		# Bad korisnickoIme or password
		else {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Upisali ste krivu lozinku ili korisničko ime!</p>';
			header("Location: livno.php?menu=7");
		}
	}
	print '
	</div>';
?>