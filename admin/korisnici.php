<?php 
	
	# Update profila
	if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
		$query  = "UPDATE korisnik SET ime='" . $_POST['ime'] . "', prezime='" . $_POST['prezime'] . "', email='" . $_POST['email'] . "', korisnickoIme='" . $_POST['korisnickoIme'] . "', drzava='" . $_POST['drzava'] . "', arhiva='" . $_POST['arhiva'] . "'";
        $query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		@mysqli_close($MySQL);
		
		$_SESSION['message'] = '<p>Uspješno ste promijenili korisnički profil!</p>';
		
		# Redirect
		header("Location: livno.php?menu=8&action=1");
	}
	# End update user profile
	
	# Delete user profile
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
	
		$query  = "DELETE FROM korisnik";
		$query .= " WHERE id=" . (int) $_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste izbrisali korisnika!</p>';
		
		# Redirect
		header("Location: livno.php?menu=8&action=1");
	}
	# End delete user profile
	#Approve users
    if (isset($_GET['approve']) && $_GET['approve'] != ''){
        $query  = "UPDATE korisnik SET odobren = 'Y'";
        $query .= " WHERE id = " . (int)$_GET['approve'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
        $_SESSION['message'] = '<p>Uspješno ste odobrili korisnika!</p>';
        # Redirect
        header("Location: livno.php?menu=8&action=1");
    }
	
	#Show user info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE id=" . $_GET['id'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>Korisnički profil</h2>
		<p><b>Ime: </b> ' . $row['ime'] . '</p>
		<p><b>Prezime: </b> ' . $row['prezime'] . '</p>
		<p><b>korisnickoIme:</b> ' . $row['korisnickoIme'] . '</p>';
		$_query  = "SELECT * FROM drzave";
		$_query .= " WHERE drzavaKod='" . $row['drzava'] . "'";
		$_result = @mysqli_query($MySQL, $_query);
		$_row = @mysqli_fetch_array($_result);
		print '
		<p><b>Drzava: </b> ' . $_row['drzavaIme'] . '</p>
		<p><b>Datum: </b> ' . pickerSamoDateToMysql($row['datumRodenja']) . '</p>
		<p><a href="livno.php?menu='.$menu.'&amp;action=' . $action . '">Nazad</a></p>';
	}
	#Edit user profile
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
        if($_SESSION['user']['rola']== 1 ){
		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE id=".$_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_archive = false;

        
		
		print '
		<h2>Uredite svoje korisnike</h2>
		<form action="" id="registration_form" name="registration_form" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
			
			<label for="fname">First Name *</label>
			<input type="text" id="fname" name="ime" value="' . $row['ime'] . '" placeholder="Your name.." required>
			<label for="lname">Last Name *</label>
			<input type="text" id="lname" name="prezime" value="' . $row['prezime'] . '" placeholder="Your last natme.." required>
				
			<label for="email">Your E-mail *</label>
			<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Your e-mail.." required>
			
			<label for="korisnickoIme">korisnickoIme *<small>(korisnickoIme must have min 5 and max 10 char)</small></label>
			<input type="text" id="korisnickoIme" name="korisnickoIme" value="' . $row['korisnickoIme'] . '" pattern=".{5,10}" placeholder="korisnickoIme.." required><br>
			
			<label for="drzava">Drzava</label>
			<select name="drzava" id="country">
				<option value="">molimo odaberite</option>';
				#Select all countries from database webprog, table countries
				$_query  = "SELECT * FROM drzave";
				$_result = @mysqli_query($MySQL, $_query);
				while($_row = @mysqli_fetch_array($_result)) {
					print '<option value="' . $_row['drzavaKod'] . '"';
					if ($row['drzava'] == $_row['drzavaKod']) { print ' selected'; }
					print '>' . $_row['drzavaIme'] . '</option>';
				}
			print '
			</select>
			
			<label for="archive">Arhiva:</label><br />
            <input type="radio" name="arhiva" value="Y"'; if($row['arhiva'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> DA &nbsp;&nbsp;
			<input type="radio" name="arhiva" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NE
			
			<hr>
			
			<input type="submit" value="Potvrdi">
		</form>
		<p><a href="livno.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
            }
	}
	else {
		print '
		<h2>lista korisnika</h2>
		<div id="users">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>E mail</th>
						<th>Država</th>
						<th width="16"></th>
                        <th width="16"></th> 
                        <th width="16"></th>         
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM korisnik";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="livno.php?menu=' . $menu . '&amp;action=' . $action . '&amp;id=' . $row['id'] . '"><img src="slike/user.png" alt="user"></a></td>
						<td><a href="livno.php?menu=' . $menu . '&amp;action=' . $action . '&amp;edit=' . $row['id'] . '"><img src="slike/edit.png" alt="uredi"></a></td>
						<td><a href="livno.php?menu=' . $menu . '&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="slike/delete.png" alt="obriši"></a></td>
						<td><strong>' . $row['ime'] . '</strong></td>
						<td><strong>' . $row['prezime'] . '</strong></td>
						<td>' . $row['email'] . '</td>
						<td>';
							$_query  = "SELECT * FROM drzave";
							$_query .= " WHERE drzavaKod='" . $row['drzava'] . "'";
							$_result = @mysqli_query($MySQL, $_query);
							$_row = @mysqli_fetch_array($_result, MYSQLI_ASSOC);
							print $_row['drzavaIme'] . '
						</td>
						<td>';
							if ($row['arhiva'] == 'Y') { print '<img src="slike/inactive.png" alt="" title="" />'; }
                            else if ($row['arhiva'] == 'N') { print '<img src="slike/active.png" alt="" title="" />'; }
						print '
						</td>
                        ';
                        if($row['odobren'] == 'N'){
                            print '                           
                        <td><a href="livno.php?menu=' . $menu . '&amp;action=' . $action . '&amp;approve=' . $row['id'] . '"><img src="slike/tick.png" alt="odobri"></a></td>
                        <td><a href="livno.php?menu=' . $menu . '&amp;action=' . $action . '&amp;delete=' . $row['id'] . '"><img src="slike/cross.png" alt="odbij"></a></td>';
                        }
                        print'
					</tr>';
				}
			print '
				</tbody>
			</table>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>