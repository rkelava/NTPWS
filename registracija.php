<?php 
	print '
	<h1>REGISTRACIJA</h1>
	<div id="rega">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" id="registracijska_forma" name="registracijska_forma" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
			<label for="ime">Ime *</label>
			<input type="text" id="ime" name="ime" placeholder="Unesite ime.." required>
			<label for="prezime">Prezime *</label>
			<input type="text" id="prezime" name="prezime" placeholder="Unesite prezime.." required>
				
			<label for="email"> E-mail *</label>
			<input type="email" id="email" name="email" placeholder="Unesite e-mail adresu.." required>
			
			<label for="korisnickoIme">Korisničko ime:* <small>(Korisničko ime mora sadržavati minimalno 5 i maksimalno 10 znakova)</small></label>
			<input type="text" id="korisnickoIme" name="korisnickoIme" pattern=".{5,10}" placeholder="Korisničko ime.." required><br>
			
									
			<label for="lozinka">Lozinka:* <small>(Lozinka mora sadržavati minimalno 4 znaka)</small></label>
			<input type="password" id="lozinka" name="lozinka" placeholder="lozinka.." pattern=".{4,}" required>

			</br><br>
			<label for="drzava">Država:</label>
			<select name="drzava" id="drzava">
				<option value="">molimo odaberite</option>';
				$query  = "SELECT * FROM drzave";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '<option value="' . $row['drzavaKod'] . '">' . $row['drzavaIme'] . '</option>';
				}
			print '
			</select>
            <label for="grad">Grad</label>
                <input type="text" id="grad" name="grad" placeholder="Grad...">

            <label for="ulica">Ulica</label>
			    <input type="text" id="ulica" name="ulica" placeholder="Ulica...">

            <label for="datumRodenja">Datum rođenja *</label>
			    <input type="date" id="datumRodenja" name="datumRodenja" required> <br><br>
                
			<input type="submit" value="Registriraj se">

		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE email='" .  $_POST['email'] . "'";
		$query .= " OR korisnickoIme='" .  $_POST['korisnickoIme'] . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if ($row === null) {
			# password_hash https://secure.php.net/manual/en/function.password-hash.php
			# password_hash() creates a new password hash using a strong one-way hashing algorithm
			$pass_hash = password_hash($_POST['lozinka'], PASSWORD_DEFAULT, ['cost' => 12]);
			
			$query  = "INSERT INTO korisnik (ime, prezime, email, korisnickoIme, lozinka, drzava, grad, ulica, datumRodenja)";
			$query .= " VALUES ('" . $_POST['ime'] . "', '" . $_POST['prezime'] . "', '" . $_POST['email'] . "', '" . $_POST['korisnickoIme'] . "', '" . $pass_hash . "', '" . $_POST['drzava'] . "', '" . $_POST['grad'] . "', '" . $_POST['ulica'] . "', '" . $_POST['datumRodenja'] ."')";
			$result = @mysqli_query($MySQL, $query);
			
			# ucfirst() — Make a string's first character uppercase
			# strtolower() - Make a string lowercase
			echo '<p>' . ucfirst(strtolower($_POST['ime'])) . ' ' .  ucfirst(strtolower($_POST['prezime'])) . ', hvala Vam  na registraciji</p>
			<hr>';
		}
		else {
			echo '<p>Korisnik s ovom e-mail adres je već registriran!</p>';
		}
	}
	print '
	</div>';
?>