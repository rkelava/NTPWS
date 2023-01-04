<?php 
	print '
	<ul>
    <li><a href="livno.php?menu=1">Naslovna</a></li>
    <li><a href="livno.php?menu=2">Novosti</a></li>
    <li><a href="livno.php?menu=3">Kontakt</a></li>
    <li><a href="livno.php?menu=4">O nama</a></li>
    <li><a href="livno.php?menu=5">Galerija</a></li>';
    if (!isset($_SESSION['user']['valid']) || $_SESSION['user']['valid'] == 'false') {
        print '
        <li><a href="livno.php?menu=6">Registracija</a></li>
        <li><a href="livno.php?menu=7">Prijava</a></li>';
    }
    else if ($_SESSION['user']['valid'] == 'true') {
        print '
        <li><a href="livno.php?menu=8">Admin</a></li>
        <li><a href="odjava.php">Odjava</a></li>';
    }
    print '
    </ul>';
?>