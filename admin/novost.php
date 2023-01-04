<?php 
	
	#Add novosti
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_novosti') {
		$_SESSION['message'] = '';
		# htmlspecialchars — Convert special characters to HTML entities
		# http://php.net/manual/en/function.htmlspecialchars.php
		$query  = "INSERT INTO novosti (naslov, opis, arhiva)";
		$query .= " VALUES ('" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', '" . $_POST['arhiva'] . "')";
		$result = @mysqli_query($MySQL, $query);
		
		$ID = mysqli_insert_id($MySQL);

        // Count # of uploaded files in array

        $count = 0;



        // Loop through each file

        if ($_FILES['slike']['name'][0] != "") {

            foreach ($_FILES['slike']['name'] as $filename) {
                # strtolower - Returns string with all alphabetic characters converted to lowercase.
                # strrchr - Find the last occurrence of a character in a string
                $ext = strtolower(strrchr($_FILES['slike']['name'][$count], "."));
                $_picture = $ID . '-' . rand(1,100) . $ext;
                copy($_FILES['slike']['tmp_name'][$count], "img/novosti/" . $_picture);
                if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
                    $query2  = "INSERT INTO slike (naziv, opis, id_novosti)";
                    $query2 .= " VALUES ('" . $_picture . "', '" . $filename . "', '" . $ID . "')";
                    $result2 = @mysqli_query($MySQL, $query2);
                }
                $count = $count + 1;
            }
        }
		
		
		$_SESSION['message'] .= '<p>Uspješno ste dodali novost!</p>';
		
		# Redirect
		header("Location: livno.php?menu=8&action=2");
	}
	
	# Update novosti
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_novosti') {
		$query  = "UPDATE novosti SET naslov='" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', opis='" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', arhiva='" . $_POST['arhiva'] . "'";
        $query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		
		# picture
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			# strtolower - Returns string with all alphabetic characters converted to lowercase. 
			# strrchr - Find the last occurrence of a character in a string
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
            
			$_picture = (int)$_POST['edit'] . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "novosti/".$_picture);
			
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE novosti SET picture='" . $_picture . "'";
				$_query .= " WHERE id=" . (int)$_POST['edit'] . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>You successfully added picture.</p>';
			}
        }
		
		$_SESSION['message'] = '<p>Uspješno ste promijenili novost!</p>';
		
		# Redirect
		header("Location: livno.php?menu=8&action=2");
	}
	# End update novosti
	
	# Delete novosti
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
		# Delete picture
        $query  = "DELETE FROM slike";
        $query .= " WHERE id_novosti=".(int)$_GET['delete'];
        $result = @mysqli_query($MySQL, $query);
       
		
		# Delete novosti
		$query  = "DELETE FROM novosti";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste obrisali novost!</p>';
		
		# Redirect
		header("Location: livno.php?menu=8&action=2");
	}
	# End delete novosti
	
	
	#Show novosti info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM novosti";
		$query .= " WHERE id=".$_GET['id'];
		$query .= " ORDER BY datum DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);

        $query2  = "SELECT * FROM slike";
        $query2 .= " WHERE id_novosti = " . $_GET['id'];

        $result2 = @mysqli_query($MySQL, $query2);
        print '<h2>novosti pregled</h2>
        ';
        while($row2 = @mysqli_fetch_array($result2)){
            print '

                <figure>
                    <img src="slike/' . $row2['naziv'] . '" alt="' . $row2['opis'] . '" title="' . $row2['opis'] . '">
                    <figcaption>' . $row2['opis'] . '</figcaption>
                </figure>'; }
		print '
		<div class="novosti">
			<h2>' . $row['naslov'] . '</h2>
			' . $row['opis'] . '
			<time datetime="' . $row['datum'] . '">' . pickerDateToMysql($row['datum']) . '</time>
			<hr>
		</div>
		<p><a href="livno.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	
	#Add novosti 
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		
		print '
		<h2>Dodaj novost</h2>
		<form action="" id="novosti_form" name="novosti_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="add_novosti">
			
			<label for="naslov">naslov *</label>
			<input type="text" id="naslov" name="naslov" placeholder="novosti naslov.." required>
			<label for="opis">opis *</label>
			<textarea id="opis" name="opis" placeholder="novosti opis.." required></textarea>
				
			<label for="picture">Slika</label>
			<input type="file" id="picture" name="slike[]" multiple>
						
			<label for="arhiva">arhiva:</label><br />
            <input type="radio" name="arhiva" value="Y"> YES &nbsp;&nbsp;
			<input type="radio" name="arhiva" value="N" checked> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="livno.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	#Edit novosti
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM novosti";
		$query .= " WHERE id=".$_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_arhiva = false;

		print '
		<h2>Edit novosti</h2>
		<form action="" id="novosti_form_edit" name="novosti_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="edit_novosti">
			<input type="hidden" id="edit" name="edit" value="' . $row['id'] . '">
			
			<label for="naslov">naslov *</label>
			<input type="text" id="naslov" name="naslov" value="' . $row['naslov'] . '" placeholder="novosti naslov.." required>
			<label for="opis">opis *</label>
			<textarea id="opis" name="opis" placeholder="novosti opis.." required>' . $row['opis'] . '</textarea>
				
			<label for="picture">Slika</label>
			<input type="file" id="picture" name="picture">
						
			<label for="arhiva">arhiva:</label><br />
            <input type="radio" name="arhiva" value="Y"'; if($row['arhiva'] == 'Y') { echo ' checked="checked"'; $checked_arhiva = true; } echo ' /> YES &nbsp;&nbsp;
			<input type="radio" name="arhiva" value="N"'; if($checked_arhiva == false) { echo ' checked="checked"'; } echo ' /> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="livno.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	else {
		print '
		<h2>novosti</h2>
		<div id="novosti">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>naslov</th>
						<th>opis</th>
						<th>Date</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM novosti";
				$query .= " ORDER BY datum DESC";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="livno.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="slike/info.png" alt="info"></a></td>
						<td><a href="livno.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="slike/edit.png" alt="uredi"></a></td>
						<td><a href="livno.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="slike/delete.png" alt="obriši"></a></td>
						<td>' . $row['naslov'] . '</td>
						<td>';
						if(strlen($row['opis']) > 160) {
                            echo substr(strip_tags($row['opis']), 0, 160).'...';
                        } else {
                            echo strip_tags($row['opis']);
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['datum']) . '</td>
						<td>';
							if ($row['arhiva'] == 'Y') { print '<img src="slike/inactive.png" alt="" naslov="" />'; }
                            else if ($row['arhiva'] == 'N') { print '<img src="slike/active.png" alt="" naslov="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
			<a href="livno.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Dodaj novost</a>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>