<?php 
	$Opis = [
        "Livanjski divljni konji na bunarima",
        "Livanjski divlji konji trce",
        "Livanjski divlji konji povrh grada",
        "Livanjski divlji u jezercu",
        "Izvor Dumana",
        "Potok kod izvora",
        "Livansjke planine",
        "Duman",
        "Buško blato noću",
        "Livno zimi",
        "jezero Mandek",
        "pogled na grad"
    ];
    
    print '
	<h1>Galerija</h1>
    <div id="gal">';

    for($i = 0; $i < 12; $i++) {
        echo '<figure>
                <a href="slike/galerija/' . $i + 1 . '.jpg" target="_blank"><img src="slike/galerija/' . $i + 1 . '.jpg" alt="' . $Opis[$i] . '" title="' . $Opis[$i] . '"></a>
                <figcaption>' . $Opis[$i] . '<figcaption>
            </figure>';
    };

    print '</div>';

    
?>