<?php
if(_su2 == 'zagreb'){
	$main_id = 1;
}elseif(_su2 == 'jadran'){
	$main_id = 6;
}elseif(_su2=='ostalo'){
	$main_id = 7;
}
if(_su2 != '_su2' && !(is_numeric(_su2))){
	if (strpos($_SERVER['QUERY_STRING'], '&') !== false) {
	    $nastavak = substr($_SERVER['QUERY_STRING'], strpos($_SERVER['QUERY_STRING'], "&") + 1); 
        $nastavak = ($nastavak)? '?'.$nastavak : '';
    }
 	// $main = explode('-', _su2); 
 	// $main_id = $main[count($main) - 1];
 	$main = Db::query_row("SELECT * FROM city WHERE id = ".$main_id);
	
	$on_page = ($_GET['on-page'])? $_GET['on-page'] : 16;
	$remove_on_page = remove_url_action($nastavak, 'on-page');
	$nastavak .= '&on-page='.$on_page;

	$lokacije_idijevi_ima = '';// Sve lokacije di ima nekretnina ---------------
	$sub_lokacija = Db::query('SELECT id,parent_id FROM city WHERE parent_id = '.$main_id.' ORDER BY orderby DESC');
	foreach ($sub_lokacija as $red1) {
		$items1 = Db::query_one('SELECT id FROM items WHERE prodaja="da" AND city_id = '.$red1['id'].' AND aktivno ="da"');
		$sub_sub_lokacija = Db::query('SELECT id,parent_id FROM city WHERE parent_id = '.$red1['id'].' ORDER BY orderby DESC');
		if($items1){
			$lokacije_idijevi_ima .= ','.$red1['id'];
		}
		if($sub_sub_lokacija){
			foreach ($sub_sub_lokacija as $red2) {
				$items2 = Db::query_one('SELECT id FROM items WHERE prodaja="da" AND city_id = '.$red2['id'].' AND aktivno ="da"');
				if($items2){
					$lokacije_idijevi_ima .= ','.$red2['id'];
				}
			}
		}
	}
	$lokacije_idijevi = substr($lokacije_idijevi_ima, 1);
	$lokacije_ids = explode(",", substr($lokacije_idijevi_ima, 1));

	//FILTER
	if(isset($_GET['location'])){
		$sql_location = ' AND city_id IN('.implode(",",$_GET['location']).')';
		$all_items = Db::query('SELECT id FROM items WHERE aktivno ="da" AND prodaja ="da" AND 1'.$sql_location);	
		$page = (is_numeric(_su3)) ? _su3 : 1;
		$start = $on_page * ($page-1);
		$items = Db::query('SELECT * FROM items WHERE 1'.$sql_location.' AND prodaja ="da" AND aktivno = "da" ORDER BY orderby DESC LIMIT '.$start.', '.$on_page);
		$ukupno = count($all_items);
		$stranica = ceil($ukupno/$on_page);
	}else{
		$all_items = Db::query('SELECT id FROM items WHERE prodaja ="da" AND aktivno ="da" AND city_id IN('.$lokacije_idijevi.') AND 1'.$sql_location);
		$page = (is_numeric(_su3)) ? _su3 : 1;
		$start = $on_page * ($page-1);
		$items = Db::query('SELECT * FROM items WHERE prodaja ="da" AND city_id IN('.$lokacije_idijevi.') AND aktivno = "da" ORDER BY orderby DESC LIMIT '.$start.', '.$on_page);
		$ukupno = count($all_items);
		$stranica = ceil($ukupno/$on_page);
	}
	$kat_filter = clean_uri($main['title_'._LNG]);
$url_filter = _SITE_URL._LNG.'/'._URL_PRODAJA.'/'.$kat_filter;

	$title = _PRODAJA;
}else{
	$title = _PRODAJA;
}
$bg = '<div class="top"><img class="top-img" src="images/bg.jpg" /><div class="center"></div></div>';



?>
<div class="bc row">
    <div class="center">
        <a class="home" href="<?php echo _SITE_URL;?>"><?php echo _HOME?></a><span> | </span><span><?php echo _PRODAJA;?></span>
    </div>
</div> 
<?php if(_su2 != '_su2' && !(is_numeric(_su2))){?>
<div class="row content <?php echo($lokacije_idijevi)?' filter-items':'';?>">
	<div class="center">
		<div class="options">
			<h1><?php echo _PRODAJA;?> - <strong><?php echo $main['title_'._LNG];?></strong> (<?php echo $ukupno;?>) </h1>
		</div>	
		<a href="javascript:;" class="f-toggle"><?php echo _FILTRIRAJTE;?></a>
		<div class="clearfix"></div>
		<div class="filters">
			<form name="filter" action="<?php echo $url_filter; ?>" method="GET">
	            <ul class="filter-list">
	            	<?php
	            		$lokacije = Db::query('SELECT id,parent_id,title_'._LNG.' FROM city WHERE parent_id = '.$main_id.' ORDER BY orderby DESC');
						foreach ($lokacije as $red) {
							echo '<li class="m-li"><ul class="m-checks '.((in_array($red['id'], $_GET['location']))? "checked-c" : "").'">';
							$s_lokacije = Db::query('SELECT id,parent_id,title_'._LNG.' FROM city WHERE parent_id = '.$red['id'].' AND id IN('.$lokacije_idijevi.') ORDER BY orderby DESC');

							if($s_lokacije){?>
							
								<li class="chck chck-all ">
				                    <input <?php echo(in_array($red['id'], $_GET['location']))? "checked" : "";?> class="checkbox" id="location-<?php echo $red['id'];?>" name="location[]" value="<?php echo $red['id'];?>" onchange="check_All<?php echo $red['id'];?>(this.value)"  value="" type="checkbox"/>
									<label for="location-<?php echo $red['id'];?>">
										<span>&nbsp;</span><?php echo $red['title_'._LNG];?>
									</label>
								</li>
								<?php if(in_array($red['id'], $_GET['location'])) {?>
								<script type="text/javascript">
			            			function check_All<?php echo $red['id'];?>() {
			            			$( ".location-group-<?php echo $red['id'];?>" ).prop( "checked", false );
			            			document.forms.filter.submit();
			            		}
	            				</script>
								<?php }else{?>

								<script type="text/javascript">
	            				function check_All<?php echo $red['id'];?>() {
	            					$( ".location-group-<?php echo $red['id'];?>" ).prop( "checked", true );

	            					document.forms.filter.submit();
	            				}
	            				</script>

<?php 
								}
								?>
				
							<?php
								foreach ($s_lokacije as $red2) {
							?>
								<li class="chck" class="<?php echo(in_array($red2['id'], $_GET['location']))? "checked-c" : "";?>">
				                    <input  class="checkbox location-group-<?php echo $red['id'];?>" name="location[]" id="location-<?php echo $red2['id'];?>" onclick="document.forms.filter.submit();" <?php echo(in_array($red2['id'], $_GET['location']))? "checked" : "";?> value="<?php echo $red2['id'];?>" type="checkbox"/>
									<label for="location-<?php echo $red2['id'];?>">
										<span>&nbsp;</span><?php echo $red2['title_'._LNG];?>
									</label>
								</li>
							<?php
								}
								
							}
							echo '</ul></li>';
							
						?>
						
						
					<?php
						}
					?>
	            </ul><div class="clearfix"></div>
	            
	       	</form>
	       	<div class="clearfix"></div>
		</div>
		<div class="items filter-items">
		<?php if($items){
			foreach ($items as $red) {
				$cat = Db::query_row("SELECT id,title_"._LNG." FROM categories WHERE id =".$red['categories_id']);
				$slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "items" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
            	$slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
            	$lokacija = Db::query_one("SELECT title_"._LNG." FROM city WHERE id =".$red['city_id']);
            	$kat = Db::query_row("SELECT id,title_"._LNG." FROM katnost WHERE id =".$red['floor']);

            	if(($red['prodaja']=='da') && ($red['najam']=='ne')){
            		$prodaja = _PRODAJA;
            	}elseif(($red['najam']=='da') && ($red['prodaja']=='ne')){
            		$prodaja = _NAJAM;
            	}elseif(($red['prodaja']=='da') && ($red['najam']=='da')){
            		$prodaja = _PRODAJA.' / '._NAJAM;
            	}
			    $naslov = $red['title_'._LNG];
			?>
				<a href="<?php echo _SITE_URL._LNG.'/'._URL_DETALJI.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>" class="box h">
					<span class="cat"><?php echo $prodaja;?></span>
					<span class="img-frame">	
						<img alt="<?php echo $red['title_'._LNG];?>" src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika?>&w=450&h=253&zc=1" />
						<span class="overlay">
							<span class="icon-frame">
								<?php echo ($red['quadrature1']!='')?'<span class="icon"><img src="images/quadrature.svg" /><span>'.$red['quadrature1'].' m<sup>2</sup></span></span>':'';?>
								<?php echo ($kat)?'<span class="icon"><img src="images/floor.svg" /><span>'.$kat['title_'._LNG].'</span></span>':'';?>
								<?php echo ($red['bathrooms']!='')?'<span class="icon h-i"><img src="images/bathrooms.svg" /><span>'.$red['bathrooms'].'</span></span>':'';?>
								<?php echo ($red['rooms2']>0)?'<span class="icon h-i"><img src="images/rooms.svg" /><span>'.$red['rooms2'].'</sup></span></span>':'';?>
							</span>
						</span>
					</span>
					<span class="title"><?php echo $naslov;?></span>
					<span class="price">
						<?php 
						if($red['action_price'] > 0.00){
                            echo '<span class="crossed"><span class="line-th"></span>'.number_format($red['price'],0,",",".").' €</span>';
                            echo number_format($red['action_price'],0,",",".").' €';
                        }elseif($red['price'] > 0.00){
                            echo number_format($red['price'],0,",",".").' €';
                        }
						?>
					</span>	
				</a>
			<?php } }else{?>
				<p><?php echo _NEMA_NEKRETNINA;?></p>
			<?php } ?>
		</div><div class="clearfix"></div>
	</div>
</div>
<?php
if($ukupno > $on_page){
?>
	<div class="pagination row">
		<div class="center">
			<?php 
			if($page > 1){ 
				echo '<a class="prev" href="'._SITE_URL._LNG.'/'._URL_PRODAJA.'/'.$kat_filter.'/'.($page-1).'/'.$nastavak.'">'._PRETHODNA.'</a>';
			} 
			if($stranica > 10){
				$start_page = (($page - 5) > 0)? ($page - 5) : 1;
				$end_page = (($start_page + 9) <= $stranica)? ($start_page + 9) : $stranica;
				if(($stranica - $page) <= 4){
					$start_page = $stranica - 4;
					$start_page = (($start_page - 5) > 0)? ($start_page - 5) : 1;
				}
				for($i=$start_page;$i<=$end_page;$i++){
					echo '<a href="'._SITE_URL._LNG.'/'._URL_PRODAJA.'/'.$kat_filter.'/'.$i.'/'.$nastavak.'" '.(($page == $i)? 'class="active"':'').'>'.$i.'</a>';
				}
			}else{
				for($i=1;$i<=$stranica;$i++){
					echo '<a href="'._SITE_URL._LNG.'/'._URL_PRODAJA.'/'.$kat_filter.'/'.$i.'/'.$nastavak.'" '.(($page == $i)? 'class="active"':'').'>'.$i.'</a>';
				}
			}
			if($page < $stranica){ 
				echo '<a class="next" href="'._SITE_URL._LNG.'/'._URL_PRODAJA.'/'.$kat_filter.'/'.($page+1).'/'.$nastavak.'">'._SLJEDECA.'</a>';
			} 
			?>
		</div>
	</div>
<?php 
	} 

}else{
 $bg = '<div class="top"><img class="top-img" src="images/bg.jpg" /><div class="center"><h1>'._PRODAJA.'</h1></div></div>';
 $img1 = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "city" AND table_id = 1 ORDER BY orderby ASC LIMIT 1');
    $img1 = ($img1) ? 'upload_data/site_photos/'.$img1 : 'images/default.jpg';

    $img2 = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "city" AND table_id = 6 ORDER BY orderby ASC LIMIT 1');
    $img2 = ($img2) ? 'upload_data/site_photos/'.$img2 : 'images/default.jpg';

    $img3 = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "city" AND table_id = 7 ORDER BY orderby ASC LIMIT 1');
    $img3 = ($img3) ? 'upload_data/site_photos/'.$img3 : 'images/default.jpg';
?>
<div class="row content">
	<div class="center txt-center services">
		
			
			<div class="items">
				<a href="<?php echo _SITE_URL._LNG.'/'._URL_PRODAJA.'/'.clean_uri('Zagreb')?>" class="w30" style="">
                    <span class="img-frame">    
                        <img alt="" src="<?php echo _SITE_URL;?>/lib/plugins/thumb.php?src=<?php echo _SITE_URL.$img1?>&w=420&h=300&zc=1">
                    </span>
                    <span class="title" style="">Zagreb</span>
                </a>
                <a href="<?php echo _SITE_URL._LNG.'/'._URL_PRODAJA.'/'.clean_uri('Jadran')?>" class="w30" style="">
                    <span class="img-frame">    
                       <img alt="" src="<?php echo _SITE_URL;?>/lib/plugins/thumb.php?src=<?php echo _SITE_URL.$img2?>&w=420&h=300&zc=1">
                    </span>
                    <span class="title" style="">Jadran</span>
                </a>
                <a href="<?php echo _SITE_URL._LNG.'/'._URL_PRODAJA.'/'.clean_uri(_OSTALO)?>" class="w30" style="">
                    <span class="img-frame">    
                        <img alt="" src="<?php echo _SITE_URL;?>/lib/plugins/thumb.php?src=<?php echo _SITE_URL.$img3?>&w=420&h=300&zc=1">
                    </span>
                    <span class="title" style="">Ostalo</span>
                </a>
			</div>

		
	</div>
</div>
<?php 
}
?>