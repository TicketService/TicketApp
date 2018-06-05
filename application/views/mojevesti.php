<?php
if(count($vesti)==0)
{
    echo "Nepostoji ni jedna vest";
}
else {
    foreach ($vesti as $vest) {
        echo $vest['naslov'];
        ?>
    <a href="<?php echo site_url("Korisnik/izmenivest/".$vest['id']); ?>">Izmeni</a>
    <a href="<?php echo site_url("Korisnik/obrisivest/".$vest['id']); ?>" 
       onclick="return confirm('Da li ste sigurni da zelite da obrisete vest?');">Obrisi</a><br>
    <?php

    }
echo $links;    

}