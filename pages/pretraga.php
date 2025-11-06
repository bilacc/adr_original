<?php 
if(strpos($_SERVER['QUERY_STRING'], '&') !== false) {
	$nastavak = substr($_SERVER['QUERY_STRING'], strpos($_SERVER['QUERY_STRING'], "&") + 1); 
    $nastavak = ($nastavak)? '?'.$nastavak : '';
}
	$lokacije_idijevi_ima = '';// Sve lokacije di ima nekretnina ---------------
	$sub_lokacija = Db::query('SELECT id FROM city WHERE parent_id = 0 ORDER BY orderby DESC');
	foreach ($sub_lokacija as $red1) {
		$items1 = Db::query_one('SELECT id FROM items WHERE city_id = '.$red1['id'].' AND aktivno ="da"');
		$sub_sub_lokacija = Db::query('SELECT id,parent_id FROM city WHERE parent_id = '.$red1['id'].' ORDER BY orderby DESC');
		if($items1){
			$lokacije_idijevi_ima .= ','.$red1['id'];
		}
		if($sub_sub_lokacija){
			foreach ($sub_sub_lokacija as $red2) {
				$items2 = Db::query_one('SELECT id FROM items WHERE city_id = '.$red2['id'].' AND aktivno ="da"');
				$sub_sub_sub_lokacija = Db::query('SELECT id,parent_id FROM city WHERE parent_id = '.$red2['id'].' ORDER BY orderby DESC');

				if($items2){
					$lokacije_idijevi_ima .= ','.$red2['id'];
				}
				if($sub_sub_sub_lokacija){
					foreach ($sub_sub_lokacija as $red3) {
						$items3 = Db::query_one('SELECT id FROM items WHERE city_id = '.$red3['id'].' AND aktivno = "da" ORDER BY orderby DESC LIMIT 1');
						
						if($items3){
							$lokacije_idijevi_ima .= ','.$red3['id'];
						}
					}
				}
			}
		}
	}
	$lokacije_idijevi = substr($lokacije_idijevi_ima, 1);
	$lokacije_ids = explode(",", substr($lokacije_idijevi_ima, 1));

	if(isset($_GET['search'])){	

		if($_GET['ssl'] && $_GET['ssl'] != ''){//kvart
		  $sql_location = ' AND city_id = '.$_GET['ssl'];

		}else{
			if($_GET['sl'] && $_GET['sl'] != ''){//opcina
				$all_i_ids = Db::query('SELECT DISTINCT city_id FROM items WHERE city_id!=0 AND aktivno ="da"');
				foreach ($all_i_ids as $i_id) {
							$all_i_idss .= ','.$i_id['city_id'];
						}
						$idijevi_i = substr($all_i_idss, 1);
						

				$subcats = Db::query('SELECT id FROM city WHERE (parent_id = '.$_GET['sl'].' AND id IN('.$idijevi_i.')) OR id='.$_GET['sl']);//kvartovi
				if($subcats){
					foreach ($subcats as $s_idd) {
						$city_ids .= ','.$s_idd['id'];
						foreach ($subsubcats as $ss_id) {
							$city_ids .= ','.$ss_id['id'];
						}
					}

					// $sql_location = ' AND city_id IN('.implode(",",$city_ids).') ';
					$sql_location = ' AND city_id IN (SELECT id FROM city WHERE (parent_id = '.$_GET['sl'].' AND id IN('.$idijevi_i.')) OR id='.$_GET['sl'].')';


				}else{
					$sql_location = ' AND city_id = '.$_GET['sl'];//samo opcina 
					
				}

			}else{
				if($_GET['l'] && $_GET['l'] != ''){
					$all_i_ids = Db::query('SELECT DISTINCT city_id FROM items WHERE city_id!=0 AND aktivno ="da"');
						foreach ($all_i_ids as $i_id) {
							$all_i_idss .= ','.$i_id['city_id'];
						}
						$idijevi_i = substr($all_i_idss, 1);
						

					$subcats = Db::query('SELECT id FROM city WHERE parent_id = '.$_GET['l']);
					
					$city_ids = '';
					if($subcats){
						$city_ids .= $_GET['l'];
						foreach ($subcats as $s_id) {
							$subsubcats = Db::query('SELECT id FROM city WHERE parent_id = '.$s_id);
							$city_ids .= ','.$s_id['id'];
							foreach ($subsubcats as $ss_id) {
								$city_ids .= ','.$ss_id['id'];
							}
						}
						$sql_location = ' AND city_id IN (SELECT id FROM city WHERE id IN('.$idijevi_i.') OR city_id="'.$_GET['l'].'")';
						// $sql_location = ' AND city_id IN (SELECT id FROM city WHERE (parent_id = '.$_GET['sl'].' AND id IN('.$idijevi_i.')) OR id='.$_GET['sl'].')';

					}else{
						$sql_location = ' AND city_id = '.$_GET['l'];
					}

					
				}else{
					$sql_location = '';
					// $sql_location = ' AND city_id IN('.implode(",",$city_ids).') OR city_id = '.$_GET['sl'];

					
				}
			}
		}

		if(in_array('sale', $_GET['status'])){
			$sql_status_sale = ' AND prodaja="da" ';
		}
		if(in_array('rent', $_GET['status'])){
			$sql_status_rent = ' AND najam="da" ';
		}
		
		if($_GET['min-size'] && $_GET['min-size'] != '' && $_GET['max-size'] && $_GET['max-size'] != ''){
			$size_sql = ' AND (quadrature1 BETWEEN  '.$_GET['min-size'].' AND '.$_GET['max-size'].')';
		}elseif($_GET['min-size'] && $_GET['min-size'] != ''){
			$size_sql = ' AND (quadrature1 > '.$_GET['min-size'].') ';
		}elseif($_GET['max-size'] && $_GET['max-size'] != ''){
			$size_sql = ' AND (quadrature1 < '.$_GET['max-size'].') ';
		}else{

		}

		if(($_GET['min-price'] && $_GET['min-price'] != '') && ($_GET['max-price'] && $_GET['max-price'] != '')){
			$price_sql = ' AND (price BETWEEN  '.str_replace('.','',$_GET['min-price']).' AND '.str_replace('.','',$_GET['max-price']).')';
			$cijena_txt = _OD.' '.$_GET['min-price'].' '._DO.' '.$_GET['max-price'];
		
		}elseif($_GET['min-price'] && $_GET['min-price'] != ''){
			$price_sql = ' AND (price > '.str_replace('.','',$_GET['min-price']).') ';
			$cijena_txt = _OD.' '.$_GET['min-price'];
			
		}elseif($_GET['max-price'] && $_GET['max-price'] != ''){
			$price_sql = ' AND (price < '.str_replace('.','',$_GET['max-price']).') ';
			$cijena_txt =  _DO.' '.$_GET['max-price'];
			
		}else{

		}
	
		// var_dump(str_replace('.','',$_GET['min-price']));

		if($_GET['type'] && $_GET['type'] != ''){
		  $sql_type = ' AND categories_id IN('.implode(",",$_GET['type']).')';
		}
		if($_GET['id'] && $_GET['id'] != ''){
		  $sql_id = ' AND id='.$_GET['id'];
		}
		
		
		if($_GET['rooms'] && $_GET['rooms'] != ''){
		  $sql_rooms = ' AND rooms2 IN('.implode(",",$_GET['rooms']).')';
		}
		if($_GET['floor'] && $_GET['floor'] != ''){
		  $sql_floor = ' AND floor IN('.implode(",",$_GET['floor']).')';
		}

		if($_GET['furnishings'] && $_GET['furnishings'] != ''){
		  $sql_furnishings = ' AND namjestenost_id IN('.implode(",",$_GET['furnishings']).')';
		}
		if($_GET['heating'] && $_GET['heating'] != ''){
		  $sql_heating = ' AND heating_id IN('.implode(",",$_GET['heating']).')';
		}
		if($_GET['garage'] && $_GET['garage'] != ''){
		  $sql_garage = ' AND garage IN('.implode(",",$_GET['garage']).')';
		}
		if($_GET['energy_cert'] && $_GET['energy_cert'] != ''){
			$i=1;
			$sql_energy_cert = '';
			foreach ($_GET['energy_cert'] as $cert) {

				if($i==1){
					$sql_energy_cert .= 'AND energy_cert LIKE "%'.$cert.'%" ';
				}else{
					$sql_energy_cert .= 'OR energy_cert LIKE "%'.$cert.'%" ';
				}
			$i++;
			}
		}

		if($_GET['year_built'] && $_GET['year_built'] != ''){
			$i=1;
			$sql_year_built = '';
			foreach ($_GET['year_built'] as $year) {

				if($i==1){
					$sql_year_built .= 'AND year_built LIKE "%'.$year.'%" ';
				}else{
					$sql_year_built .= 'OR year_built LIKE "%'.$year.'%" ';
				}
			$i++;
			}
		}

		$sql_filter = $sql_status_sale.$sql_status_rent.$sql_location.$sql_type.$sql_id.$size_sql.$price_sql.$sql_rooms.$sql_floor.$sql_furnishings.$sql_heating.$sql_garage.$sql_energy_cert.$sql_year_built;
		
		$on_page = ($_GET['on-page'])? $_GET['on-page'] : 16;
		$all_items = Db::query('SELECT id FROM items WHERE aktivno ="da" AND 1'.$sql_filter);	
		$page = (is_numeric(_su2)) ? _su2 : 1;
		$start = $on_page * ($page-1);
		$items = Db::query('SELECT * FROM items WHERE 1'.$sql_filter.' AND aktivno = "da" ORDER BY orderby DESC LIMIT '.$start.', '.$on_page);
		// var_dump('SELECT * FROM items WHERE 1'.$sql_filter.' AND aktivno = "da" ORDER BY orderby DESC LIMIT '.$start.', '.$on_page);
		
		$ukupno = count($all_items);
		$stranica = ceil($ukupno/$on_page);

	}
	$title = _REZULTATI_PRETRAGE;
?>

<div class="row content">
	<div class="center">

	<div class="options">
		<h2><?php echo _REZULTATI_PRETRAGE;?> (<?php echo $ukupno;?>)</h2>
	</div>
	<div class="items">
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
								<span class="clearfix"></span>
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
				<p><?php echo _NEMA_REZULTATA;?></p>
			<?php } ?>
		</div>
	</div>
</div>

<?php
if($ukupno > $on_page){
?>
	<div class="pagination row">
		<div class="center">
			<?php 
			if($page > 1){ 
				echo '<a class="prev" href="'._SITE_URL._LNG.'/'._URL_PRETRAGA.'/'.($page-1).'/'.$nastavak.'">'._PRETHODNA.'</a>';
			} 
			if($stranica > 10){
				$start_page = (($page - 5) > 0)? ($page - 5) : 1;
				$end_page = (($start_page + 9) <= $stranica)? ($start_page + 9) : $stranica;
				if(($stranica - $page) <= 4){
					$start_page = $stranica - 4;
					$start_page = (($start_page - 5) > 0)? ($start_page - 5) : 1;
				}
				for($i=$start_page;$i<=$end_page;$i++){
					echo '<a href="'._SITE_URL._LNG.'/'._URL_PRETRAGA.'/'.$i.'/'.$nastavak.'" '.(($page == $i)? 'class="active"':'').'>'.$i.'</a>';
				}
			}else{
				for($i=1;$i<=$stranica;$i++){
					echo '<a href="'._SITE_URL._LNG.'/'._URL_PRETRAGA.'/'.$i.'/'.$nastavak.'" '.(($page == $i)? 'class="active"':'').'>'.$i.'</a>';
				}
			}
			if($page < $stranica){ 
				echo '<a class="next" href="'._SITE_URL._LNG.'/'._URL_PRETRAGA.'/'.($page+1).'/'.$nastavak.'">'._SLJEDECA.'</a>';
			} 
			?>
		</div>
	</div>
<?php 
	} 
?>