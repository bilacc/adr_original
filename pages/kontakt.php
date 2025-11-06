<script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
<?php 
	$kontakt = Db::query_row("SELECT * FROM single WHERE id=2 ");
	$title = ($kontakt['page_title_'._LNG] != '')? $kontakt['page_title_'._LNG]:$kontakt['title_'._LNG];
    $description = ($kontakt['page_description_'._LNG] != '')?  $kontakt['page_description_'._LNG]:'Elion nekretnine';
    $keywords = ($kontakt['page_keywords_'._LNG] != '')?  $kontakt['page_keywords_'._LNG]:'Elion nekretnine';

    include('include/forma.php');

    if(isset($_POST['posalji_upit'])){
        $SEND_SETTINGS['domena'] = _SITE_DOMAIN;
        $SEND_SETTINGS['to_email'] = array("info@elionsolar.com");
        $SEND_SETTINGS['from_email'] = $_POST['email'];
        $SEND_SETTINGS['domena_email'] = _SITE_DOMAIN;
        $SEND_SETTINGS['provjera'] = array('email' , 'poruka', 'ime','prezime','tel','gdpr','age');
        $SEND_SETTINGS['popis'] = array('Ime'=>'ime','Prezime'=>'prezime','Email'=>'email','Kontakt Broj'=>'tel','Poruka'=>'poruka');
        $SEND_SETTINGS['uvod_poruka'] = "Poštovani,<br/><strong>".$_POST['ime'].' '.$_POST['prezime']."</strong> Vam je poslao/la poruku, u nastavku je sadržaj poruke:</p>";
        $SEND_SETTINGS['naslov_iznad_sadrzaja'] = "Kontakt podaci";
        $SEND_SETTINGS['subject'] = $_POST['ime'].' '.$_POST['prezime']." Vam je poslao/la poruku";
        $PORUKA['poslan_email'] = '<p class="success">'._PORUKA_JE_USPJESNO_POSLANA.'</p>';

        $SEND_EMAIL = sendEmail($SEND_SETTINGS);
         if(!$_POST['gdpr']){
        $msg .= _ERROR_GDPR.'<br>';
        $insert = false;
        $c2 = 'txt_error';
    }
    if(!$_POST['age']){
        $msg .= _ERROR_GDPR.'<br>';
        $insert = false;
        $c3 = 'txt_error';
    }
    }else{
        if(is_array($SEND_SETTINGS['provjera'])){
            foreach($SEND_SETTINGS['provjera'] AS $key=>$value){
                $SEND_EMAIL[$value] = "txt";
            }#end FOREACH
        }#end IF
        $SEND_EMAIL['action'] = false;
    }#end ELSE

   
   
    $bg = '<div class="top"><img class="top-img" src="images/kontakt.jpg" /><div class="center"><h1>'.$kontakt['title_'._LNG].'</h1></div></div>';
?>

<div class="bc row xs">
    <div class="center"><a href=""><?php echo _HOME;?></a><span> | </span><span><?php echo _KONTAKT;?></span></div>
</div>
<div class="row content txt xs">
    <div class="center">
        <?php echo $kontakt['text_'._LNG];?>
    </div>
</div>
<div class="row content light">
    <div class="center txt">
        <div class="kontakt-forma">
             <?php
            if($SEND_EMAIL['action']){
                echo $PORUKA['poslan_email'];
                header('Location: '._SITE_URL._LNG.'/'._URL_ZAHVALA);
                exit;
            }else{
                if(isset($_POST['posalji_upit'])){
            ?>
                <div class="error"><?php echo _ISPUNITE_POLJA?></div>
            <?php } ?>
            <form action="" method="post" name="forma" id="f_forma">
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
                    <p><?php echo _SLAZEM_SE_KONTAKT;?>
                    <?php echo _NASA_PRAVILA?> <a href="<?php echo _SITE_URL._PRAVILA_PDF_URL?>" target="_blank"><strong><?php echo _OVDJE?></strong></a> </p><br><br>
                </div>
                <div class="form-row chck <?php echo $c2?>">
                    <div class="gdpr-row">
                        <input id="gdpr" <?php echo ($_POST['gdpr']=='ok')? 'checked="checked"':''; ?> type="checkbox" value="ok" name="gdpr">
                        <label for="gdpr"><span>
                        </span><?php echo _SLAZEM_SE_S_PRAVILIMA_PRIVATNOSTI; ?></label>
                    </div>
                </div><div class="clearfix">  </div>
                <div class="form-row chck <?php echo $c3?>">
                    <div class="gdpr-row">
                        <input id="age" <?php echo ($_POST['age']=='ok')? 'checked="checked"':''; ?> type="checkbox" value="ok" name="age">
                        <label for="age"><span>
                        </span><?php echo _STARIJI_SAM_OD_16; ?></label>
                    </div>
                </div>
                <div class="clearfix">  </div>
                <div class="button_align">
                    <input type="hidden" value="ok" id="posalji_upit" class="" name="posalji_upit" placeholder=""> 
                    <button class="g-recaptcha btn btn-blue aright" data-sitekey="<?php echo _GOOGLE_SITE_KEY?>" name="posalji_upit" data-callback="onSubmit"><?php echo _POSALJITE?></button>
                </div>
                <div class="clearfix"></div>
            </form>
            <?php }#end ELSE?>
        </div><!--end kontakt_forma-->
    </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgnt-53VE5xXbvzq_fnnR-KF_luEZeZ50&callback=init" async defer></script>

<script>
    google.maps.event.addDomListener(window, 'load', init);  
      function init() {
          var mapOptions = {
              zoom: 14,
              styles: [{"featureType": "administrative","elementType": "labels","stylers": [{"visibility": "on"}]},{"featureType": "administrative.country","elementType": "labels","stylers": [{"visibility": "on"}]},{"featureType": "landscape.man_made","elementType": "geometry","stylers": [{"color": "#f8f5f0"}]},{"featureType": "landscape.natural","elementType": "geometry","stylers": [{"color": "#d0e3b4"}]},{"featureType": "landscape.natural.terrain","elementType": "geometry","stylers": [{"visibility": "off"}]},{"featureType": "poi","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "poi.business","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "poi.medical","elementType": "geometry","stylers": [{"color": "#fbd3da"}]},{"featureType": "poi.park","elementType": "geometry","stylers": [{"color": "#bde6ab"}]},{"featureType": "road","elementType": "geometry.stroke","stylers": [{"visibility": "off"}]},{"featureType": "road","elementType": "labels","stylers": [{"visibility": "on"}]},{"featureType": "road.highway","elementType": "geometry.fill","stylers": [{"color": "#bc9b6a"}]},{"featureType": "road.highway","elementType": "geometry.stroke","stylers": [{"color": "#efd151"}]},{"featureType": "road.arterial","elementType": "geometry.fill","stylers": [{"color": "#ffffff"}]},{"featureType": "road.local","elementType": "geometry.fill","stylers": [{"color": "black"}]},{"featureType": "transit.station.airport","elementType": "geometry.fill","stylers": [{"color": "#cfb2db"}]},{"featureType": "water","elementType": "geometry","stylers": [{"color": "#a2daf2"}]}],
              center: new google.maps.LatLng(<?php echo $kontakt['gmap_lat_1']; ?>,<?php echo $kontakt['gmap_lon_1']; ?>),
              scrollwheel: false
          };
          var mapElement = document.getElementById('map');
          var map = new google.maps.Map(mapElement, mapOptions);
          var marker = new google.maps.Marker({
              position: new google.maps.LatLng(<?php echo $kontakt['gmap_lat_1']; ?>,<?php echo $kontakt['gmap_lon_1']; ?>),
              map: map,
              icon: 'images/pin.png',
              title: 'Elion nekretnine'
          });
      }
</script>
<div id="map" class="map" style="width:100%;height:500px;background-color:#ffffff!important;"></div>