<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

$con = mysqli_connect('localhost','adresa07_2017','gSuT98*71{il','adresa07_elionnek_2017');
mysqli_set_charset($con, "utf8");
$sql = "Select items.*,categories.title_hr as cat_title_hr,categories.title_en as cat_title_en,city.title_hr as city_title_hr,city.title_en as city_title_en,energetski_certifikat.title_hr as energetski_certifikat_title_hr,energetski_certifikat.title_en as energetski_certifikat_title_en from items left join categories on items.categories_id=categories.id left join city on items.city_id=city.id left join energetski_certifikat on items.energy_cert=energetski_certifikat.id order by items.id desc";
$exe = mysqli_query($con,$sql);

header( "Content-type: text/xml");
echo "<?xml version='1.0' encoding='UTF-8'?>
		<rss version='2.0'>
		<channel>
			<title>Adresar | RSS</title>
			<link>https://adresar.net</link>
			<description>Items RSS</description>";
			while($row = mysqli_fetch_array($exe))
			{
			?>
				<item>
				    <type_hr><?= html_entity_decode($row['cat_title_hr'], null, "UTF-8");?></type_hr>
					<type_en><?= html_entity_decode($row['cat_title_en'], null, "UTF-8");?></type_en>
					<city_hr><?= html_entity_decode($row['city_title_hr'], null, "UTF-8");?></city_hr>
					<city_en><?= html_entity_decode($row['city_title_en'], null, "UTF-8");?></city_en>
					<title_hr><?= html_entity_decode($row['title_hr'], null, "UTF-8");?></title_hr>
					<title_en><?= html_entity_decode($row['title_en'], null, "UTF-8");?></title_en>
					<description_hr>
					    <?php 
					    /*$text = html_entity_decode($row['text_hr'], null, "UTF-8");
					    echo strip_tags($text);*/
					    ?>
					</description_hr>
					<description_en>
					    <?php 
					    $text = html_entity_decode($row['text_en'], null, "UTF-8");
					    echo strip_tags($text);
					    ?>
					</description_en>
					<address><?= html_entity_decode($row['address'], null, "UTF-8");?></address>
					<aktivno><?= html_entity_decode($row['aktivno'], null, "UTF-8");?></aktivno>
					<front_page><?= html_entity_decode($row['front_page'], null, "UTF-8");?></front_page>
					<klima><?= html_entity_decode($row['klima'], null, "UTF-8");?></klima>
					<novogradnja><?= html_entity_decode($row['novogradnja'], null, "UTF-8");?></novogradnja>
					<balkon><?= html_entity_decode($row['balkon'], null, "UTF-8");?></balkon>
					<lift><?= html_entity_decode($row['lift'], null, "UTF-8");?></lift>
					<vrt><?= html_entity_decode($row['vrt'], null, "UTF-8");?></vrt>
					<orjentacija><?= html_entity_decode($row['orjentacija'], null, "UTF-8");?></orjentacija>
					<front_page2><?= html_entity_decode($row['front_page2'], null, "UTF-8");?></front_page2>
					<front_page3><?= html_entity_decode($row['front_page3'], null, "UTF-8");?></front_page3>
					<video_url><?= html_entity_decode($row['video_url'], null, "UTF-8");?></video_url>
					<prodaja><?= html_entity_decode($row['prodaja'], null, "UTF-8");?></prodaja>
					<najam><?= html_entity_decode($row['najam'], null, "UTF-8");?></najam>
					
					<?php 
					$sql2 = "Select * from `site_photos` where `table_name`='items' and `table_id`=".$row['id'];
					$exe2 = mysqli_query($con,$sql2);
					if($exe2)
					{
					    if(mysqli_num_rows($exe)>0)
					    {
					        echo "<images>";
					        while($row2 = mysqli_fetch_assoc($exe2))
    					    {
    					   ?>
    					    <image><?= 'https://adresar.net/upload_data/site_photos/'.$row2['photo_name'];?></image>
    					   <?php
    					    }    
    					    echo "</images>";
					    }
					    
					}
					?>
					
					
					<price><?= html_entity_decode($row['price'], null, "UTF-8");?></price>
					<action_price><?= html_entity_decode($row['action_price'], null, "UTF-8");?></action_price>
					<quadrature1><?= html_entity_decode($row['quadrature1'], null, "UTF-8");?></quadrature1>
					<floor><?= html_entity_decode($row['floor'], null, "UTF-8");?></floor>
					<etaze><?= html_entity_decode($row['etaze'], null, "UTF-8");?></etaze>
					<rooms><?= html_entity_decode($row['rooms'], null, "UTF-8");?></rooms>
					<code><?= html_entity_decode($row['code'], null, "UTF-8");?></code>
					<bathrooms><?= html_entity_decode($row['bathrooms'], null, "UTF-8");?></bathrooms>
					<garage><?= html_entity_decode($row['garage'], null, "UTF-8");?></garage>
					<garage_broj><?= html_entity_decode($row['garage_broj'], null, "UTF-8");?></garage_broj>
					<parking><?= html_entity_decode($row['parking'], null, "UTF-8");?></parking>
					<rooms2><?= html_entity_decode($row['rooms2'], null, "UTF-8");?></rooms2>
					<parking_broj><?= html_entity_decode($row['parking_broj'], null, "UTF-8");?></parking_broj>
					<namjestenost_id><?= html_entity_decode($row['namjestenost_id'], null, "UTF-8");?></namjestenost_id>
					<heating_id><?= html_entity_decode($row['heating_id'], null, "UTF-8");?></heating_id>
					<adaptacija><?= html_entity_decode($row['adaptacija'], null, "UTF-8");?></adaptacija>
					<gmap_lat_1><?= html_entity_decode($row['gmap_lat_1'], null, "UTF-8");?></gmap_lat_1>
					<gmap_lon_1><?= html_entity_decode($row['gmap_lon_1'], null, "UTF-8");?></gmap_lon_1>
					<created><?= html_entity_decode($row['created'], null, "UTF-8");?></created>
					
					<energetski_certifikat_title_hr><?= html_entity_decode($row['energetski_certifikat_title_hr'], null, "UTF-8");?></energetski_certifikat_title_hr>
					<energetski_certifikat_title_en><?= html_entity_decode($row['energetski_certifikat_title_en'], null, "UTF-8");?></energetski_certifikat_title_en>
					
					<redirect_url>
					<?php 
					$url = html_entity_decode($row['redirect_url'], null, "UTF-8");
					$url = urlencode($url);
					?>
					</redirect_url>
				</item>
			<?php
			}
echo "</channel></rss>";
?>

