<?php
include('include/php/header.php');

$slike = Db::query('SELECT * FROM site_photos ORDER BY id ASC');

foreach($slike as $s)
{
	$input_file_name = _SITE_ROOT.'upload_data/site_photos/'.$s['photo_name'];
	$destination_file_name = _SITE_ROOT.'upload_data/site_photos/'.$s['photo_name'];
	image_resize_to($input_file_name, $destination_file_name, 1024, 768, 100);
}


include('include/php/footer.php'); ?>