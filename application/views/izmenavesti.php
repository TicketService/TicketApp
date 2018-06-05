<form action="<?php echo site_url("Korisnik/menjajvest/".$vest['id']);?>" method="post">
    Naslov: <input type="text" name="naslov" value="<?php echo  $vest['naslov'] ?>">
    <?php echo form_error("naslov"); ?> <br>
    Sadrzaj: <input type="text" name="sadrzaj" value="<?php echo  $vest['sadrzaj'] ?>">
    <?php echo form_error("sadrzaj"); ?> <br>
    <input type="submit" value="Izmeni">
</form>
