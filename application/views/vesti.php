
<?php echo form_open("$controller/$metoda", "method=get"); ?>
Pretraga: <input type="text" name="pretraga">
<br>
<input type="submit" value="Trazi">
<br>
<?php echo form_close(); ?>

<?php
foreach ($vesti as $vest) {
    echo $vest['naslov'];
    ?>
<a href="<?php echo site_url("$controller/prikazivest/".$vest['id']); ?>">Prikazi</a><br>
<?php
}
?>
<hr>