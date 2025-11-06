<?php 


if(isset($_SESSION['link'])){
$old_link_id = Db::query_row('SELECT id,title_'._LNG.',categories_id FROM items WHERE redirect_url = "'.$_SESSION['link'].'"');
	if($old_link_id){
	    $cat = Db::query_row("SELECT id,title_"._LNG." FROM categories WHERE id =".$old_link_id['categories_id']);
	    $redirect_url = _SITE_URL._LNG.'/'._URL_DETALJI.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($old_link_id['title_'._LNG]).'-'.$old_link_id['id'];

	    header('Location:'.$redirect_url);
	    exit;
	}	
}



	$home_txt = Db::query_row("SELECT * FROM single WHERE id=3 ");
	$title = ($home_txt['page_title_'._LNG] != '')? $home_txt['page_title_'._LNG]:$home_txt['title_'._LNG];
    $description = ($home_txt['page_description_'._LNG] != '')?  $home_txt['page_description_'._LNG]:'Elion nekretnine';
    $keywords = ($home_txt['page_keywords_'._LNG] != '')?  $home_txt['page_keywords_'._LNG]:'Elion nekretnine';

    $featured = Db::query("SELECT id,city_id,prodaja,action_price,price,najam,aktivno,floor,front_page,quadrature1,categories_id,rooms2,bathrooms,title_"._LNG." FROM items WHERE aktivno ='da' AND front_page = 'da' ORDER BY orderby DESC LIMIT 4");
    $featured2 = Db::query("SELECT id,city_id,prodaja,action_price,price,najam,aktivno,floor,front_page,quadrature1,categories_id,rooms2,bathrooms,title_"._LNG." FROM items WHERE aktivno ='da' AND front_page3 = 'da' ORDER BY orderby DESC LIMIT 2");

    $img1 = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "city" AND table_id = 1 ORDER BY orderby ASC LIMIT 1');
    $img1 = ($img1) ? 'upload_data/site_photos/'.$img1 : 'images/default.jpg';

    $img2 = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "city" AND table_id = 6 ORDER BY orderby ASC LIMIT 1');
    $img2 = ($img2) ? 'upload_data/site_photos/'.$img2 : 'images/default.jpg';

    $img3 = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "city" AND table_id = 7 ORDER BY orderby ASC LIMIT 1');
    $img3 = ($img3) ? 'upload_data/site_photos/'.$img3 : 'images/default.jpg';
?>
<div class="row content top-content">
	<div class="center txt-center">
		<h1><?php echo $home_txt['title_'._LNG];?></h1>
		<?php echo $home_txt['text_'._LNG];?>
		<div class="icons">
			<a href="<?php echo _SITE_URL._LNG.'/'._URL_O_NAMA.'/upoznajte-nas-1';?>" class="ic1"><span></span><?php echo _UPOZNAJTE_NAS;?></a>
			<a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE?>" class="ic2"><span></span><?php echo _USLUGE;?></a>
			<a href="<?php echo _SITE_URL._LNG.'/'._URL_BLOG?>" class="ic3"><span></span><?php echo _BLOG;?></a>
		</div>
	</div>
</div>
<div class="row content b-line">
	<div class="center">
		
			
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
<?php if($featured){?>
<div class="row content">
	<div class="center">
		<h2><strong><?php echo _IZDVOJENE;?></strong> <?php echo _NEKRETNINE;?></h2>
		<div class="items">
			<?php foreach ($featured as $red) {
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
				<a href="<?php echo _SITE_URL._LNG.'/'._URL_DETALJI.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>" class="box h w5">
					<span class="cat"><?php echo $prodaja;?></span>
					<span class="img-frame">	
						<img alt="<?php echo $red['title_'._LNG];?>" src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika?>&w=610&h=340&zc=1" />
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
			<?php } ?>
		</div>



	</div>
</div>
<?php } ?>


<?php if($featured2){?>
<div class="row content snizene">
	<div class="center">
		<h2><strong><?php echo _SNIZENE;?></strong> <?php echo _NEKRETNINE;?></h2>
		<div class="items">
			<?php foreach ($featured2 as $red) {
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
				<a href="<?php echo _SITE_URL._LNG.'/'._URL_DETALJI.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>" class="box h w5">
					<span class="cat"><?php echo $prodaja;?></span>
					<span class="img-frame">	
						<img alt="<?php echo $red['title_'._LNG];?>" src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika?>&w=610&h=340&zc=1" />
						<span class="overlay">
							<span class="icon-frame">
								<?php echo ($red['quadrature1']!='')?'<span class="icon"><img src="images/quadrature.svg" /><span>'.$red['quadrature1'].' m<sup>2</sup></span></span>':'';?>
								<?php echo ($kat)?'<span class="icon"><img src="images/floor.svg" /><span>'.$kat['title_'._LNG].'</span></span>':'';?>
								<span class="clearfix">	</span>
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
			<?php } ?>
		</div>



	</div>
</div>
<?php } ?>