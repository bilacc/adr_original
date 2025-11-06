<?php 
unset($_SESSION['link']);
if(_su3 != '_su3' && !(is_numeric(_su3))){
        $item = explode('-', _su3); 
        $item_id = $item[count($item) - 1];
        $item = Db::query_row("SELECT * FROM items WHERE id = ".$item_id);
        $cat = Db::query_row("SELECT id,title_"._LNG." FROM categories WHERE id =".$item['categories_id']);
		$lokacija = Db::query_one("SELECT title_"._LNG." FROM city WHERE id =".$item['city_id']);
        $namjestenost = Db::query_row("SELECT id,title_"._LNG." FROM namjestenost WHERE id =".$item['namjestenost_id']);
        $grijanje = Db::query_row("SELECT id,title_"._LNG." FROM grijanje WHERE id =".$item['heating_id']);
        $floor = Db::query_one("SELECT title_"._LNG." FROM katnost WHERE id =".$item['floor']);
        $energy_cert = Db::query_one("SELECT title_"._LNG." FROM energetski_certifikat WHERE id =".$item['energy_cert']);

        $agent = Db::query_row("SELECT id,title_"._LNG.",tel,mail FROM our_team WHERE id =".$item['agent_id']);

		$prva_slika = Db::query_row('SELECT id,photo_name FROM site_photos WHERE table_name = "items" AND table_id = '.$item['id'].' AND tlocrt="ne" ORDER BY orderby ASC LIMIT 1');
		$slike = Db::query('SELECT * FROM site_photos WHERE table_name = "items" AND table_id = '.$item['id'].' AND table_id != '.$prva_slika['id'].' AND tlocrt="ne" ORDER BY orderby ASC');

		$tlocrt = Db::query('SELECT * FROM site_photos WHERE table_name = "items" AND table_id = '.$item['id'].' AND tlocrt="da" ORDER BY orderby ASC');
		$broj_slika = count($slike);

        // FAVORITII
        $is_favorit = Db::query_one('SELECT id FROM favoriti WHERE cookie="'.$_COOKIE[_STORE_COOKIE_NAME].'" AND items_id='.$item['id']);
        if($is_favorit){
            $wish_txt = _DODANO_U_FAVORITE;
            $class=' added';
        }else{
            $wish_txt = _DODAJ_U_FAVORITE;
            $class=' ';
        }
        $function = "sjx('favorits', ".$item['id'].",'items','"._LNG."', 1);return false";
        $title = $item['title_'._LNG];

        include('include/forma.php');
        $captcha = array('7 + 6', '12 + 5', '8 + 6', '5 + 3', '14 + 5', '2 + 9');
        $catcha_rez = array(13, 17, 14, 8, 19, 11);
        $captch = rand(0, (count($captcha) - 1));
        
        if(isset($_POST['posalji_upit'])){
        $SEND_SETTINGS['domena'] = _SITE_DOMAIN;
        $SEND_SETTINGS['to_email'] = array($agent['mail']);
        $SEND_SETTINGS['from_email'] = $_POST['email'];
        $SEND_SETTINGS['domena_email'] = _SITE_DOMAIN;
        $SEND_SETTINGS['provjera'] = array('email' , 'poruka', 'ime','prezime','tel','kod');
        $SEND_SETTINGS['popis'] = array('Ime'=>'ime','Prezime'=>'prezime','Email'=>'email','Kontakt Broj'=>'tel','Poruka'=>'poruka');
        $SEND_SETTINGS['uvod_poruka'] = "Poštovani,<br/><strong>".$_POST['ime'].' '.$_POST['prezime']."</strong> Vam je poslao/la poruku, u nastavku je sadržaj poruke:</p>";
        $SEND_SETTINGS['naslov_iznad_sadrzaja'] = "Kontakt podaci";
        $SEND_SETTINGS['subject'] = $_POST['ime'].' '.$_POST['prezime']." Vam je poslao/la upit za ".$item['title_'._LNG];
        $PORUKA['poslan_email'] = '<p class="success">'._PORUKA_JE_USPJESNO_POSLANA.'</p>';

        $SEND_EMAIL = sendEmail($SEND_SETTINGS);
    }else{
        if(is_array($SEND_SETTINGS['provjera'])){
            foreach($SEND_SETTINGS['provjera'] AS $key=>$value){
                $SEND_EMAIL[$value] = "txt";
            }#end FOREACH
        }#end IF
        $SEND_EMAIL['action'] = false;
    }#end ELSE

    }
    $bg = '<div id="map" class="map top-map" style="width:100%;height:460px;background:#e9e4db!important;"></div>';
    $naslov = $item['title_'._LNG];  
?>
<div class="bc row">
    <div class="center">
        <a class="home" href="<?php echo _SITE_URL;?>"><?php echo _HOME?></a><span> | </span>
        <?php /*<a href="<?php echo _SITE_URL._LNG.'/'._URL_TIP_SMJESTAJA.'/'.clean_uri($cat['title_'._LNG].'-'.$cat['id']);?>"></a>*/?>
        <span><?php echo $cat['title_'._LNG];?></span>    
    </div>
</div> 
<div class="row content">
    <div class="center">
    	<div class="options">
            <h1><?php echo $naslov;?></h1>
            <div class="price-details">
                <?php if($item['action_price'] > 0.00){
                        echo '<span class="crossed"><span class="line-th"></span>'.number_format($item['price'],0,",",".").' €</span>';
                        echo number_format($item['action_price'],0,",",".").' €';
                    }elseif($item['price'] > 0.00){
                        echo number_format($item['price'],0,",",".").' €';
                    }
                ?>
            </div>
            <div class="option-frame">
        		<a href="javascript:;" id="dodano_<?php echo $item['id'];?>" onclick="<?php echo $function;?>" class="btn-wishlist add aright <?php echo $class;?>">
                    <span class="heart">
                        <svg version="1.1" id="wishlist-small" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#CF3686" d="M7.963,4.019c3-1.941,4.605-2.043,5.982-0.425
                            c1.332,1.565,1.162,4.072-0.927,6.267c-4.983,5.23-4.917,5.166-9.967,0.027C2.544,9.371,2.165,8.686,1.863,8.016
                            C1.07,6.254,0.935,4.494,2.616,3.157c1.608-1.279,3.196-0.82,4.666,0.384C7.503,3.723,7.752,3.873,7.963,4.019z"/>
                        </svg>
                    </span>
                    <span id="wish_txt_<?php echo $item['id']?>"><?php echo $wish_txt;?></span>  
                </a>
                <?php /*
        		<a href="javascript:;" class="print">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     width="14.067px" height="17.996px" viewBox="0 0 14.067 17.996" enable-background="new 0 0 14.067 17.996" xml:space="preserve">
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#BC9B6A" d="M0.014,0.025c2.484,0,4.997,0,7.522,0c0.357,0,0.752-0.057,1.072,0
                    c0.13,0.019,0.271,0.204,0.378,0.315c1.463,1.459,2.947,2.796,4.421,4.273c0.086,0.086,0.215,0.209,0.34,0.327
                    c0.111,0.106,0.319,0.289,0.319,0.393c0,4.152,0,8.473,0,12.662c-4.689,0-9.379,0-14.066,0c0-5.979,0-11.959,0-17.937
                    C-0.001,0.04-0.001,0.025,0.014,0.025z M0.783,0.841c0,5.457,0,10.917,0,16.374c4.167,0,8.334,0,12.503,0c0-3.775,0-7.554,0-11.329
                    c-1.688-0.007-3.413,0.011-5.08-0.015c0-1.686,0-3.378,0-5.068c-2.471,0-4.941,0-7.413,0C0.779,0.803,0.78,0.821,0.783,0.841z
                     M8.999,1.436c0-0.01-0.015-0.007-0.013,0.016c0,1.22,0,2.434,0,3.648c1.258,0,2.515,0,3.774,0
                    C11.55,3.885,10.251,2.651,8.999,1.436z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#BC9B6A" d="M8.316,12.113c-0.727,0.133-1.512,0.308-2.136,0.608
                    c-0.089,0.04-0.197,0.092-0.308,0.147c-0.102,0.048-0.243,0.089-0.303,0.144c-0.138,0.123-0.17,0.36-0.269,0.527
                    c-0.189,0.321-0.539,0.695-0.854,0.893c-0.44,0.267-1.139,0.186-1.294-0.33c-0.087-0.289,0.074-0.604,0.218-0.77
                    c0.378-0.438,1.101-0.679,1.638-0.917c0.522-0.804,0.974-1.682,1.207-2.77C5.987,9.102,5.443,8.108,5.812,7.423
                    C5.968,7.134,6.495,6.867,6.923,7.09c0.501,0.264,0.53,1.148,0.342,1.785c-0.029,0.101-0.072,0.219-0.109,0.33
                    c-0.039,0.114-0.1,0.249-0.098,0.33c0.005,0.158,0.198,0.363,0.292,0.499c0.283,0.416,0.534,0.727,0.904,1.064
                    c0.069,0.063,0.141,0.158,0.221,0.17c0.133,0.019,0.383-0.101,0.511-0.138c0.782-0.189,1.978-0.084,1.943,0.771
                    c-0.029,0.726-0.893,0.874-1.625,0.686C8.9,12.483,8.638,12.313,8.316,12.113z M6.558,8.264C6.559,8.108,6.593,7.92,6.544,7.785
                    c-0.013,0-0.024,0-0.037,0C6.461,7.935,6.474,8.172,6.558,8.264z M6.204,11.876c0.359-0.219,0.913-0.249,1.356-0.39
                    c-0.298-0.288-0.552-0.621-0.808-0.947C6.658,11.064,6.395,11.486,6.204,11.876z"/>
                </svg>
                <?php echo _PDF;?></a>*/?>
            </div>

           
    	</div>	
        <div class="w3 details">
            <?php echo '<span><strong>'._ID_NEKRETNINE.'</strong>: '.$item['id'].'</span>';?>
            <?php echo '<span><strong>'._TIP_NEKRETNINE.'</strong>: '.$cat['title_'._LNG].'</span>';?>
            <?php echo '<span><strong>'._LOKACIJA.'</strong>: '.$lokacija.'</span>';?>
            
            <?php echo ($item['quadrature1']!='')?'<span><strong>'._POVRSINA.'</strong>: '.$item['quadrature1'].' m<sup>2</sup></span>':'';?>
            

            <?php echo ($item['rooms2']>0)?'<span><strong>'._BROJ_SOBA.'</strong>: '.$item['rooms2'].'</span>':'';?>
            <?php echo ($item['bathrooms']!='')?'<span><strong>'._BROJ_KUPAONICA.'</strong>: '.$item['bathrooms'].'</span>':'';?>
            
            <?php 
                $lift=($item['lift']=='da')?'<span class="span-in"><img class="ch" src="images/ch.svg"><strong>'._LIFT.'</strong></span>':'';
            ?>
            <?php echo ($floor)?'<span><strong>'._KATNOST.'</strong>: '.$floor.' '.$lift.'</span>':'';?>
            <?php echo ($item['etaze']!='')?'<span><strong>'._BROJ_ETAZA.'</strong>: '.$item['etaze'].'</span>':'';

            if(($item['action_price']!=0.00) && $item['quadrature1']){
                $povrsina = (float)($item['quadrature1']);
                echo '<span><strong>'._CIJENA_PO_KVADRATU.'</strong>: ≈'.round(((float)$item['action_price']/$povrsina),0,PHP_ROUND_HALF_UP).' €</span>';
               
            }elseif($item['price'] && $item['quadrature1']){
                $povrsina = (float)($item['quadrature1']);
                echo '<span><strong>'._CIJENA_PO_KVADRATU.'</strong>: ≈'.round(((float)$item['price']/$povrsina),0,PHP_ROUND_HALF_UP).' €</span><br>';
                
                
            }else{

            }
            ?>
            
        </div>

        <div class="w7">
    	   <div class="details-slider-frame">
                <?php if($broj_slika > 1){?>
                <ul class="details-slider">
                    <?php foreach($slike as $red){ ?>
                        <li class="gal">
                            <a href="<?php echo _SITE_URL.'upload_data/site_photos/'.$red['photo_name'];?>">
                                <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.'upload_data/site_photos/'.$red['photo_name'];?>&w=970&h=550&zc=1" />
                            </a>
                        </li>
                    <?php } ?>                      
                </ul>
                <?php }else{?>
                    <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.'upload_data/site_photos/'.$prva_slika['photo_name'];?>&w=970&h=550&zc=1" />
                <?php } ?> 
                <?php 
                   if($tlocrt){
                        echo '<div class="tlocrt">';
                        $i=1;
                        foreach($tlocrt as $red){
                    ?>  
                        <a href="<?php echo _SITE_URL.'upload_data/site_photos/'.$red['photo_name'];?>">
                            <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.'upload_data/site_photos/'.$red['photo_name'];?>&w=460&zc=2" alt="<?php echo _TLOCRT.' '.$item['title_'._LNG];?>" />
                            <?php if($i==1) echo _TLOCRT;?>
                        </a>
                    <?php 
                        $i++;
                        }
                        echo '</div>';
                    } 
                ?>
            </div>

            <?php if($broj_slika > 1){?>
                <div class="thumbs">
                    <div id="bx-pager">
                        <?php
                            $i=0;
                            foreach($slike as $red){
                        ?>      
                            <a data-slide-index="<?php echo $i;?>" href="javascript:;">
                                <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.'upload_data/site_photos/'.$red['photo_name'];?>&w=123&h=80&zc=1" alt="<?php echo $item['title_'._LNG].'-'.$i;?>" />
                            </a>
                        <?php
                            $i++;
                            }
                        ?>    
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php } ?>
        </div>

		<div class="description-frame">
            <?php if($agent){
            $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "our_team" AND table_id = '.$agent['id'].' ORDER BY orderby ASC LIMIT 1');
            $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
        ?>
        <div class="w7 agent">
            <span class="team-box">
                    <a class="img-box" href="<?php echo _SITE_URL._LNG.'/'._URL_AGENT.'/'.clean_uri($agent['title_hr']).'-'.$agent['id']?>">
                        <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=260&zc=2" />
                    </a>
                    <span class="height">
                        <h4><?php echo _AGENT;?>: </h4>
                        <a href="<?php echo _SITE_URL._LNG.'/'._URL_AGENT.'/'.clean_uri($agent['title_hr']).'-'.$agent['id']?>"><h3><?php echo $agent['title_hr'];?></strong></h3></a>
                        <span class="mail">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 width="22px" height="24px" viewBox="5 4 22 24" enable-background="new 5 4 22 24" xml:space="preserve">
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M8.975,12.375c0-0.112,0-0.224,0-0.336
                                        c0.027-0.116,0.047-0.235,0.082-0.349c0.102-0.334,0.272-0.62,0.583-0.805c0.24-0.143,0.508-0.165,0.776-0.165
                                        c2.567-0.002,5.136-0.001,7.703-0.001c1.487,0,2.976-0.001,4.464,0c0.574,0,1.007,0.248,1.274,0.758
                                        c0.067,0.128,0.096,0.277,0.143,0.416c0,0.121,0,0.24,0,0.361c-0.013,0.062-0.026,0.124-0.038,0.186
                                        c-0.146,0.71-0.559,1.26-1.111,1.686c-0.713,0.55-1.467,1.049-2.205,1.567c-0.595,0.416-1.191,0.832-1.79,1.242
                                        c-0.524,0.357-1.046,0.719-1.583,1.056c-0.512,0.321-1.047,0.315-1.565,0.005c-0.325-0.194-0.644-0.4-0.955-0.615
                                        c-0.723-0.5-1.438-1.008-2.158-1.511c-0.749-0.524-1.505-1.04-2.249-1.569c-0.498-0.355-0.897-0.804-1.172-1.354
                                        C9.083,12.768,9.04,12.566,8.975,12.375z"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M8.975,14.535c0.536,0.579,1.217,0.966,1.854,1.412
                                        c0.594,0.418,1.198,0.822,1.797,1.233c0.029,0.021,0.055,0.046,0.107,0.089c-0.182,0.174-0.359,0.338-0.531,0.509
                                        c-0.865,0.864-1.729,1.729-2.594,2.594c-0.209,0.208-0.422,0.413-0.633,0.62C8.975,18.84,8.975,16.688,8.975,14.535z"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M24,20.992c-0.254-0.25-0.509-0.496-0.761-0.747
                                        c-0.819-0.818-1.638-1.639-2.458-2.458c-0.173-0.171-0.35-0.338-0.54-0.522c0.123-0.087,0.225-0.159,0.328-0.231
                                        c0.863-0.59,1.732-1.172,2.586-1.775c0.301-0.213,0.564-0.48,0.845-0.723C24,16.688,24,18.84,24,20.992z"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M23.508,22.207c-0.163,0.162-0.382,0.252-0.62,0.295
                                        c-0.105,0.02-0.216,0.023-0.322,0.023c-4.053,0.001-8.105,0.001-12.157,0.001c-0.307,0-0.596-0.059-0.85-0.242
                                        c-0.035-0.025-0.067-0.055-0.091-0.074c1.418-1.417,2.832-2.829,4.254-4.249c0.299,0.211,0.617,0.446,0.944,0.666
                                        c0.472,0.314,0.974,0.565,1.542,0.656c0.441,0.072,0.855-0.038,1.262-0.202c0.652-0.265,1.191-0.708,1.76-1.106
                                        c0.014-0.01,0.025-0.018,0.028-0.02C20.675,19.373,22.089,20.787,23.508,22.207z"/>
                            </svg>
                            <?php echo $agent['mail'];?>
                        </span>
                        <span class="tel">
                            <svg version="1.1" id="phone" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 width="22px" height="24px" viewBox="5 4 22 24" enable-background="new 5 4 22 24" xml:space="preserve">
                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M20.84,24.368c-0.268-0.038-0.537-0.07-0.804-0.116
                                    c-1.601-0.272-3.071-0.878-4.396-1.809c-2.843-1.999-4.57-4.729-5.247-8.128c-0.16-0.808-0.238-1.625-0.161-2.451
                                    c0.02-0.211,0.075-0.405,0.192-0.584c0.417-0.641,0.984-1.12,1.635-1.504c0.319-0.188,0.673-0.183,1.002-0.037
                                    c0.288,0.127,0.569,0.282,0.823,0.466c0.126,0.091,0.205,0.266,0.27,0.416c0.267,0.633,0.402,1.302,0.533,1.972
                                    c0.051,0.262,0.079,0.528,0.13,0.79c0.047,0.235-0.004,0.449-0.146,0.63c-0.165,0.209-0.346,0.407-0.534,0.596
                                    c-0.214,0.213-0.441,0.412-0.67,0.609c-0.071,0.063-0.086,0.113-0.053,0.201c0.285,0.772,0.705,1.47,1.181,2.136
                                    c0.894,1.249,2.015,2.255,3.31,3.07c0.345,0.217,0.347,0.204,0.654-0.056c0.302-0.255,0.608-0.507,0.921-0.749
                                    c0.292-0.225,0.611-0.287,0.978-0.177c0.805,0.243,1.604,0.496,2.366,0.86c0.349,0.167,0.612,0.396,0.751,0.772
                                    c0.122,0.331,0.273,0.656,0.208,1.016c-0.033,0.18-0.096,0.374-0.202,0.517c-0.408,0.542-0.896,1.006-1.497,1.333
                                    c-0.176,0.096-0.372,0.152-0.56,0.227C21.296,24.368,21.068,24.368,20.84,24.368z"/>
                            </svg>
                            <?php echo $agent['tel'];?>
                        </span>
                        
                    </span>
                </span>
                <div class="clearfix">  </div>
                
        </div>
        <?php }?>
        </div> 
         <div class="line clearfix"></div>
        <div class="clearfix"></div>
       <h2><?php echo _OPIS?></h2>
       <div class="all-options details w3">
       <?php echo ($item['klima']=='da')?'<span><img class="ch" src="images/ch.svg"><strong>'._KLIMA.'</strong></span>':'';?>
            <?php echo ($item['novogradnja']=='da')?'<span><img class="ch" src="images/ch.svg"><strong>'._NOVOGRADNJA.'</strong></span>':'';?>
            
            <?php echo ($item['balkon']=='da')?'<span><img class="ch" src="images/ch.svg"><strong>'._BALKON.'</strong></span>':'';?>
            <?php echo ($item['vrt']=='da')?'<span><img class="ch" src="images/ch.svg"><strong>'._VRT.'</strong></span>':'';?>
            
            <?php if($namjestenost){
                echo '<span><strong>'._NAMJESTENOST.'</strong>: '.$namjestenost['title_'._LNG].'</span>';
                }
            ?>
            <?php if ($grijanje['title_'._LNG]) { ?>
            <?php echo '<span><strong>'._GRIJANJE.'</strong>: '.$grijanje['title_'._LNG].'</span>';?>
            <?php } ?>
           
            <?php if($item['garage']=="da" && $item['parking']=="da"){
                echo '<span><strong>'._PARKIRALISTE.'</strong>: ';
                    echo ($item['garage_broj'])?_GARAZA.' ('.$item['garage_broj'].')':_GARAZA;
                    echo ($item['parking_broj'])?', '._VANJSKO_PARKIRNO_MJESTO.' ('.$item['parking_broj'].')':', '._VANJSKO_PARKIRNO_MJESTO;
                echo '</span>';
            }elseif($item['garage']=="da" && $item['parking']=="ne"){
                echo '<span><strong>'._PARKIRALISTE.'</strong>: ';
                    echo ($item['garage_broj'])?_GARAZA.' ('.$item['garage_broj'].')':_GARAZA;
                echo '</span>';
            }elseif($item['garage']=="ne" && $item['parking']=="da"){
                echo '<span><strong>'._PARKIRALISTE.'</strong>: ';
                    echo ($item['parking_broj'])?_VANJSKO_PARKIRNO_MJESTO.' ('.$item['parking_broj'].')':_VANJSKO_PARKIRNO_MJESTO;
                echo '</span>';
            }
            ?>

            <?php echo ($item['year_built']!='')?'<span><strong>'._GODINA_IZGRADNJE.'</strong>: '.$item['year_built'].'</span>':'';?>
            <?php echo ($item['adaptacija']!='')?'<span><strong>'._GODINA_ADAPTACIJE.'</strong>: '.$item['adaptacija'].'</span>':'';?>
            <?php echo ($item['orjentacija']==1)?'<span><strong>'._ORJENTACIJA.'</strong>: Sjever </span>':'';?>
            <?php echo ($item['orjentacija']==2)?'<span><strong>'._ORJENTACIJA.'</strong>: Jug </span>':'';?>
            <?php echo ($item['orjentacija']==3)?'<span><strong>'._ORJENTACIJA.'</strong>: Istok </span>':'';?>
            <?php echo ($item['orjentacija']==4)?'<span><strong>'._ORJENTACIJA.'</strong>: Zapad </span>':'';?>
            
            <?php echo ($energy_cert)?'<span><strong>'._ENERGETSKI_CERTIFIKAT.'</strong>: '.$energy_cert.'</span>':'';?>
       </div>
       
        <div class="right">
            <?php echo $item['text_'._LNG];?>
            <a href="javascript:;" class="btn d">
                <?php echo _POSALJITE_UPIT_ZA;?>
            </a>
        </div>
    </div>
</div>
<div class="row content light hidden-form">
    <div class="center txt">
        <div class="kontakt-forma">
             <?php
            if($SEND_EMAIL['action']){
                echo $PORUKA['poslan_email'];
            }else{
                if(isset($_POST['posalji_upit'])){
            ?>
                <div class="error"><?php echo _ISPUNITE_POLJA?></div>
            <?php } ?>
            <form action="" method="post" name="forma" id="forma">
                <div class="w5">
                    <label><?php echo _IME?>:*</label>
                    <input type="text" value="<?php echo $_POST['ime'];?>" id="ime" class="<?php echo $SEND_EMAIL['ime'];?>" name="ime"> 
                </div>
                <div class="w5 aright">
                    <label><?php echo _PREZIME?>:*</label>
                    <input type="text" value="<?php echo $_POST['prezime'];?>" id="prezime" class="<?php echo $SEND_EMAIL['prezime'];?>" name="prezime"> 
                </div>
                <div class="w5">
                    <label><?php echo _E_MAIL?>:*</label>
                    <input type="text" value="<?php echo $_POST['email'];?>" id="email" class="<?php echo $SEND_EMAIL['email'];?>" name="email"> 
                    <div class="clearfix"></div>
                </div>
                <div class="w5 aright">
                    <label><?php echo _KONTAKT_BROJ?>:</label>
                    <input type="text" value="<?php echo $_POST['tel'];?>" id="tel" class="<?php echo $SEND_EMAIL['tel'];?>" name="tel" placeholder=""> 
                    <div class="clearfix"></div>
                </div>
                <div class="form-row">
                    <label><?php echo _MESSAGE?>:*</label>
                    <textarea rows="8" cols="" id="poruka_slanje" class="<?php echo $SEND_EMAIL['poruka'];?>" name="poruka"><?php echo $_POST['poruka'];?></textarea>  
                    <div class="clearfix"></div>
                </div>
                <div class="form-row">
                    <label class="capture" for="capture"><?php echo _KOLIKO_JE; ?> <?php echo $captcha[$captch];?>: *</label>
                    <input name="kod" type="text" class="full_input <?php echo $SEND_EMAIL['kod'];?>" id="kod" />
                    <input type="hidden" name="captch" value="<?php echo $catcha_rez[$captch];?>" />
                </div>
                <div class="clearfix">  </div>
                <div class="button_align">
                    <input type="submit" value="<?php echo _POSALJITE?>" name="posalji_upit" class="btn btn-blue">
                </div>
                <div class="clearfix"></div>
            </form>
            <?php }#end ELSE?>
        </div><!--end kontakt_forma-->
    </div>
</div>





<script> 
      function init() {
          var mapOptions = {
              zoom: 15,
              backgroundColor: '#e9e4db', 
              styles: [{"featureType": "administrative","elementType": "labels","stylers": [{"visibility": "on"}]},{"featureType": "administrative.country","elementType": "labels","stylers": [{"visibility": "on"}]},{"featureType": "landscape.man_made","elementType": "geometry","stylers": [{"color": "#f8f5f0"}]},{"featureType": "landscape.natural","elementType": "geometry","stylers": [{"color": "#d0e3b4"}]},{"featureType": "landscape.natural.terrain","elementType": "geometry","stylers": [{"visibility": "off"}]},{"featureType": "poi","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "poi.business","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "poi.medical","elementType": "geometry","stylers": [{"color": "#fbd3da"}]},{"featureType": "poi.park","elementType": "geometry","stylers": [{"color": "#bde6ab"}]},{"featureType": "road","elementType": "geometry.stroke","stylers": [{"visibility": "off"}]},{"featureType": "road","elementType": "labels","stylers": [{"visibility": "on"}]},{"featureType": "road.highway","elementType": "geometry.fill","stylers": [{"color": "#bc9b6a"}]},{"featureType": "road.highway","elementType": "geometry.stroke","stylers": [{"color": "#efd151"}]},{"featureType": "road.arterial","elementType": "geometry.fill","stylers": [{"color": "#ffffff"}]},{"featureType": "road.local","elementType": "geometry.fill","stylers": [{"color": "black"}]},{"featureType": "transit.station.airport","elementType": "geometry.fill","stylers": [{"color": "#cfb2db"}]},{"featureType": "water","elementType": "geometry","stylers": [{"color": "#a2daf2"}]}],
              center: new google.maps.LatLng(<?php echo $item['gmap_lat_1']; ?>,<?php echo $item['gmap_lon_1']; ?>),
              scrollwheel: false
          };
          var mapElement = document.getElementById('map');
          var map = new google.maps.Map(mapElement, mapOptions);
          var marker = new google.maps.Marker({
              position: new google.maps.LatLng(<?php echo $item['gmap_lat_1']; ?>,<?php echo $item['gmap_lon_1']; ?>),
              map: map,
              backgroundColor: (233, 228, 219),
              icon: 'images/pin.png',
              title: 'Elion nekretnine'
          });
      }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgnt-53VE5xXbvzq_fnnR-KF_luEZeZ50&callback=init" async defer></script>