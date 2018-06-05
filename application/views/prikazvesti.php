
Naslov: <?php echo $vest['naslov']; ?><br>
Sadrzaj: <?php echo $vest['sadrzaj']; ?><br>
Id autora: <?php echo $vest['autor']; ?><br>
Datum: <?php echo mdate('%d.%m.%Y', strtotime($vest['datum'])); ?><br>

<?php
if(file_exists("uploads/vest_".$vest['id'].".jpg")) 
{  ?>
<img src="<?php echo base_url("uploads/vest_".$vest['id'].".jpg"); ?>"/>
<a href="<?php echo site_url("$controller/preuzmi/".$vest['id']); ?>">Preuzmi</a>
<?php 
}