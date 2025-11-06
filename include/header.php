<?php 
if(!isset($_SESSION['on-page']) && empty($_SESSION['on-page'])){
$_SESSION['on-page'] = 16;
}

$items_cats_ids = Db::query('SELECT DISTINCT city_id FROM items WHERE city_id != 0 ORDER BY orderby DESC'); 
$i=1;
foreach ($items_cats_ids as $aaa) {
    $lokacije_i .= ($i==1)?'':',';
    $lokacije_i .= $aaa['city_id'];
$i++;
}
$items_cats_idsss = Db::query('SELECT DISTINCT parent_id FROM city WHERE id IN('.$lokacije_i.') ORDER BY orderby DESC'); 
$i=1;
foreach ($items_cats_idsss as $r) {
    if($parent != ','.$r['parent_id']){
        $parent = ','.$r['parent_id'];
        $svi .= $parent;
    }
}
$lokacije_idijevi = substr($svi, 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <base href="<?php echo _SITE_URL; ?>" />
     
    <title>Adresar - agencija za nekretnine</title>
    <meta http-equiv='Cache-control' content='public, max-age=86400' />
    <meta name='robots' content='index,follow' />
    <meta name="author" content="Adresar webmaster" />
    <meta name="Copyright" content="Adresar nekretnine" />
    <meta name="DC.language" content="hr-HR" />
    <meta name="DC.title" content="Adresar - agencija za nekretnine" />
    <meta name="DC.creator" content="Adresar nekretnine" />
    <meta name="DC.format" content="text/html" />
    <meta name="DC.identifier" content="http://www.adresar.net/" />
    <meta name="DC.publisher" content="Adresar nekretnine" />
    <meta name="description" content="Adresar - agencija za nekretnine"/>
    <meta name="keywords" content="Adresar - agencija za nekretnine"/>
    <meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
    <link href="images/favicon.png" rel="shortcut icon" type="image/x-icon"/>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgnt-53VE5xXbvzq_fnnR-KF_luEZeZ50&libraries=places" async defer></script>
    
    
    <script type="text/javascript">
    function onloadCSS(e,n){e.onload=function(){e.onload=null,n&&n.call(e)},"isApplicationInstalled"in navigator&&"onloadcssdefined"in e&&e.onloadcssdefined(n)}!function(e){"use strict";var n=function(n,t,o){function i(e){if(a.body)return e();setTimeout(function(){i(e)})}function d(){r.addEventListener&&r.removeEventListener("load",d),r.media=o||"all"}var l,a=e.document,r=a.createElement("link");if(t)l=t;else{var s=(a.body||a.getElementsByTagName("head")[0]).childNodes;l=s[s.length-1]}var f=a.styleSheets;r.rel="stylesheet",r.href=n,r.media="only x",i(function(){l.parentNode.insertBefore(r,t?l:l.nextSibling)});var u=function(e){for(var n=r.href,t=f.length;t--;)if(f[t].href===n)return e();setTimeout(function(){u(e)})};return r.addEventListener&&r.addEventListener("load",d),r.onloadcssdefined=u,u(d),r};"undefined"!=typeof exports?exports.loadCSS=n:e.loadCSS=n}("undefined"!=typeof global?global:this);
    var ss = loadCSS( "css/style_all.css" );
    onloadCSS( ss, function() {
    loadCSS("css/style.css");
    <?php if(_su1==_URL_KONTAKT){?>
       loadCSS("css/form.css");
    <?php } ?>
    });
    </script>
    <style type="text/css">
        <?php 
            include("css/header.css");
            if(_su1 == '_su1'){
                include("css/slider.css");
            }elseif(_su1 == _URL_BLOG){
                include("css/filter.css");
            }elseif(_su1 == _URL_KONTAKT){
                include("css/form.css");
            }elseif(_su1 == _URL_DETALJI || _su1 == _URL_O_NAMA || _su1 == _URL_NEWS){
                include("css/details.css");
            }elseif(_su1 == _URL_AGENT){
                include("css/agent.css");
            }
        ?>
    </style>
    <script type="text/javascript">
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-143173599-1', 'auto');
      ga('send', 'pageview');
    </script>
</head>
<?php 
$glavne_lokacije = Db::query("SELECT id,title_hr FROM city WHERE parent_id = 0 ORDER BY orderby ASC");
$o_nama = Db::query("SELECT id,title_"._LNG." FROM o_nama ORDER BY orderby ASC");
if(_su1 != _URL_KONTAKT){?>
<body class="not-ready <?php if(_su1!='_su1' && _su1 != _URL_PRETRAGA) echo ' others';?>" onload="$('body').removeClass('not-ready').delay(500).addClass('visible');">
<?php }else{?>
<body class="<?php if(_su1 == _URL_PRETRAGA){?>  <?php }else{ ?> others <?php } ?>">
<?php } ?>
<div class="row top-header">
    <div class="center">
        <div class="top-contacts">
            <?php include("include/top-contacts.php");?>
        </div>
        <!-- <div class="lang">
            <a href="hr" class="<?php if (_LNG == 'hr') echo 'active';?>">HR</a>
            <a href="en" class="<?php if (_LNG == 'en') echo 'active';?>">EN</a>
        </div> -->
        <div class="social">
            <?php include("include/social.php");?>
        </div>
    </div>  
</div>
<div class="row header">
    <div class="center">
        <a href="<?php echo _SITE_URL;?>" class="logo">
            <strong>ADRESAR</strong>
            
            <span class="gold">agencija za nekretnine</span>
        </a>
        <div class="clearfix h-c" style="display:none"></div>
        <span class="hgk"><img src="images/hgk.png"><span><?php echo _LICENCIRANA_AGENCIJA?></span></span> 
        <a href="javascript:;" class="nav-toggle"><?php echo _IZBORNIK?></a>
        <div class="nav">
            <ul class="main-nav">
                <li class="o-nama">
                    <a href="javascript:;" <?php if( _su1 == _URL_O_NAMA) echo ' class="active"';?>><?php echo _O_NAMA;?></a>
                    <a href="javascript:;" class="drop drop-xs"></a>
                    <ul class="sub">
                        <?php 
                            foreach ($o_nama as $red) {?>
                            <li>
                                <a href="<?php echo _SITE_URL._LNG.'/'._URL_O_NAMA.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>">
                                    <?php echo $red['title_'._LNG];?>
                                </a> 
                            </li>
                        <?php 
                            }
                        ?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo _SITE_URL._LNG.'/'._URL_PRODAJA?>" <?php if( _su1 == _URL_PRODAJA) echo ' class="active"';?>><?php echo _PRODAJA;?></a>
                    <a href="javascript:;" class="drop drop-xs"></a>
                    <ul class="sub">
                        <?php 
                            foreach ($glavne_lokacije as $red) {?>
                            <li>
                                <a href="<?php echo _SITE_URL._LNG.'/'._URL_PRODAJA.'/'.clean_uri($red['title_hr'])?>">
                                    <?php echo $red['title_hr'];?>
                                </a> 
                            </li>
                        <?php 
                            } 
                        ?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo _SITE_URL._LNG.'/'._URL_NAJAM?>"  <?php if( _su1 == _URL_NAJAM) echo ' class="active"';?>><?php echo _NAJAM;?></a>
                    <a href="javascript:;" class="drop drop-xs"></a>
                    <ul class="sub">
                        <?php 
                            foreach ($glavne_lokacije as $red) {?>
                            <li>
                                <a href="<?php echo _SITE_URL._LNG.'/'._URL_NAJAM.'/'.clean_uri($red['title_hr'])?>">
                                    <?php echo $red['title_hr'];?>
                                </a> 
                            </li>
                        <?php 
                            } 
                        ?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE?>" <?php if( _su1 == _URL_USLUGE) echo ' class="active"';?>><?php echo _USLUGE;?></a>
                    <a href="javascript:;" class="drop drop-xs"></a>
                   <ul class="sub">
                       <?php 
                            $usluge_cat = Db::query("SELECT * FROM categories_usluge ORDER BY orderby ASC");
                                foreach ($usluge_cat as $red) {
                                    $usluge_subcat = Db::query("SELECT * FROM services WHERE categories_id = ".$red['id']." ORDER BY orderby DESC");
                                    $usluge = Db::query("SELECT id,title_"._LNG." FROM services WHERE categories_id=".$red['id']." ORDER BY orderby ASC");
                                    $br_s = count($usluge);

                                    if($br_s==1){
                                        $usluge_row = Db::query_row("SELECT id,title_"._LNG." FROM services WHERE categories_id=".$red['id']." ORDER BY orderby ASC");
                                    ?>
                                    <li>
                                        <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($red['title_'._LNG]).'/'.clean_uri($usluge_row['title_'._LNG]).'-'.$usluge_row['id']?>">
                                            <?php echo $red['title_'._LNG];?>
                                        </a>
                                    
                                    <?php }elseif($br_s>1){?>
                                    <li>
                                        <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>">
                                            <?php echo $red['title_'._LNG];?>
                                        </a>
                                        
                                    <?php }
                                    if($br_s>1){
                                        if($usluge_subcat){
                                            echo '<ul class="ssub">';
                                            foreach ($usluge_subcat as $red2) {?>
                                                <li>
                                        <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($red['title_'._LNG]).'/'.clean_uri($red2['title_'._LNG]).'-'.$red2['id']?>">
                                            <?php echo $red2['title_'._LNG];?>
                                        </a>
                                        </li>
                                            <?php 
                                            }
                                            echo '</ul>';
                                        }
                                    }
                                    ?>
                                    </li>
                            <?php } 
                        ?>
                   </ul>
                </li>
                <li><a href="<?php echo _SITE_URL._LNG.'/'._URL_BLOG?>" <?php if( _su1 == _URL_BLOG) echo ' class="active"';?> ><?php echo _BLOG;?></a></li>
                <li><a href="<?php echo _SITE_URL._LNG.'/'._URL_KONTAKT?>" <?php if( _su1 == _URL_KONTAKT) echo ' class="active"';?>><?php echo _KONTAKT;?></a></li>
            </ul>
        </div>
    </div>  
</div>
<?php
if(_su1 == '_su1'){
    $animation_items = Db::query("SELECT * FROM items WHERE aktivno = 'da' AND front_page2 = 'da' ORDER BY orderby DESC LIMIT 6");
    if($animation_items){
?>
<div class="s-frame">
    <div class="slider-bg-frame">
        <ul class="slider-bg">
        <?php foreach ($animation_items as $red) {
            $cat = Db::query_row("SELECT id,title_"._LNG." FROM categories WHERE id =".$red['categories_id']);
            $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "items" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
            $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default-bg.jpg';
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
                if($cat){
                    $url = _SITE_URL._LNG.'/'._URL_DETALJI.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($red['title_'._LNG]).'-'.$red['id'];
                }else{
                    $url = _SITE_URL._LNG.'/'._URL_DETALJI.'//'.clean_uri($red['title_'._LNG]).'-'.$red['id'];
                }
        ?>
            <li style="background-image:url(<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=1920&h=1080&zc=1)">&nbsp;
                <div class="center">
                    <div class="slider-txt slider-txt-frame"> 
                    <div class="aleft">
                        <span class="cat"><?php echo $prodaja;?></span>
                        <a href="<?php echo $url?>" class="title"><?php echo $naslov;?></a>
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
                    </div>
                    <span class="icon-frame">
                        <?php echo ($red['quadrature1']!='')?'<span class="icon"><img src="images/quadrature.svg" /><span>'.$red['quadrature1'].' m<sup>2</sup></span></span>':'';?>
                        <?php echo ($kat)?'<span class="icon"><img src="images/floor.svg" /><span>'.$kat['title_'._LNG].'</span></span>':'';?>
                        <?php echo ($red['bathrooms']!='')?'<span class="icon h-i"><img src="images/bathrooms.svg" /><span>'.$red['bathrooms'].'</span></span>':'';?>
                        <?php echo ($red['rooms2']>0)?'<span class="icon h-i"><img src="images/rooms.svg" /><span>'.$red['rooms2'].'</sup></span></span>':'';?>
                    </span>
                
                </div> </div>
            </li>
        <?php } ?>
        </ul>
    </div>
   
     <div id="controls">
            <a id="slider-next" href="javascript:;"></a>
            <a id="slider-prev" href="javascript:;"></a>
        </div>
</div>
<?php 
    }
}else{
    echo $page->data['bg'];
}
?>

<div class="row search-btn-row" <?php echo (_su1 != '_su1')?'':' style="display:none;"';?>>
    <a class="s-btn btn" href="javascript:;"><?php echo _PROSIRITE_PRETRAGU; ?></a>
</div>


<div class="row search-row <?php if(_su1 == _URL_PRETRAGA) echo ' vis-search';?>">
    <div class="center">
        <form id="forma" name="forma" method="GET" action="<?php echo _SITE_URL._LNG.'/'._URL_PRETRAGA.$nastavak;?>">
            <div class="search-container">
                <div class="search-container txt-center">
                    <div class="chck">
                        <input id="prodaja" type="checkbox" value="sale" name="status[]" <?php echo(in_array('sale', $_GET['status']))? "checked" : "";?>>
                        <label for="prodaja">
                            <span>
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="8.886px" height="6.607px" viewBox="57.953 14.778 8.886 6.607" enable-background="new 57.953 14.778 8.886 6.607" xml:space="preserve">
                                    <polyline fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-miterlimit="10" points="58.501,17.833 60.834,20.333 
                                     66.334,15.333 "/>
                                </svg>
                            </span>
                            <?php echo _PRODAJA; ?>
                        </label>
                    </div>
                    <div class="chck">
                        <input id="najam" type="checkbox" value="rent" name="status[]" <?php echo(in_array('rent', $_GET['status']))? "checked" : "";?>>
                        <label for="najam">
                            <span>
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="8.886px" height="6.607px" viewBox="57.953 14.778 8.886 6.607" enable-background="new 57.953 14.778 8.886 6.607" xml:space="preserve">
                                    <polyline fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-miterlimit="10" points="58.501,17.833 60.834,20.333 
                                     66.334,15.333 "/>
                                </svg>
                            </span>
                            <?php echo _NAJAM; ?>
                        </label>
                    </div>
                </div> 
                <div class="search-frame inactive select over s-open">
                <?php 
                    if($_GET['type'] && $_GET['type'] != ''){
                        $selected = Db::query("SELECT title_"._LNG." FROM categories WHERE id IN(".implode(',',$_GET['type']).") ORDER BY orderby ASC");
                        $name_list = '';
                        foreach ($selected as $name) {
                            $name_list .= ', '.$name['title_'._LNG];
                        }

                    }
                    $categories = Db::query("SELECT id,title_"._LNG." FROM categories ORDER BY orderby ASC");
                  
                ?>
                        <a href="javascript:;" class="dropdown-title">
                        <span id="type" class="inside">
                            <input readonly placeholder="<?php echo _TIP_NEKRETNINE?>" value="<?php echo ($_GET['type'] && $_GET['type'])?substr($name_list, 1):''._TIP_NEKRETNINE;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                        <div class="dropdown">
                            <div class="max">
                            <?php 
                            foreach ($categories as $red) { ?>
                                <div class="chck-select">   
                                <input name="type[]" id="type-<?php echo $red['id'];?>" class="type" <?php echo(in_array($red['id'], $_GET['type']))? "checked" : "";?> data-title="<?php echo $red['title_'._LNG];?>" value="<?php echo $red['id'];?>" type="checkbox"/>
                                <label for="type-<?php echo $red['id'];?>">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label"><?php echo $red['title_'._LNG];?></span>
                                </label>
                            </div>
                            <?php 
                            } ?>
                            </div>
                        </div>
                    </div>
                
                
                <?php 
                    if($_GET['min-price'] && $_GET['min-price'] != '' && $_GET['max-price'] && $_GET['max-price'] != ''){
                        $cijena_txt = _OD.' '.$_GET['min-price'].' '._DO.' '.$_GET['max-price'];
                    }elseif($_GET['min-price'] && $_GET['min-price'] != ''){
                        $cijena_txt = _OD.' '.$_GET['min-price'];
                    }elseif($_GET['max-price'] && $_GET['max-price'] != ''){
                        $cijena_txt =  _DO.' '.$_GET['max-price'];
                    }else{
                        $cijena_txt = '';
                    }

                    if($_GET['min-size'] && $_GET['min-size'] != '' && $_GET['max-size'] && $_GET['max-size'] != ''){
                        $povrsina_txt = _OD.' '.$_GET['min-size'].' '._DO.' '.$_GET['max-size'];
                    }elseif($_GET['min-size'] && $_GET['min-size'] != ''){
                        $povrsina_txt = _OD.' '.$_GET['min-size'];
                    }elseif($_GET['max-size'] && $_GET['max-size'] != ''){
                        $povrsina_txt =  _DO.' '.$_GET['max-size'];
                    }else{
                        $povrsina_txt = '';
                    }
                ?>
                <div class="search-frame inactive select s-open">
                    <a class="dropdown2-title" href="javascript:;"><?php echo _POVRSINA.' '.$povrsina_txt;?> (m<sup>2</sup>)<span class="arrow"></span></a>  
                    <div class="hidden-select">
                        <input  class="w3 size-input" type="text" name="min-size" placeholder="-" value="<?php echo $_GET['min-size'];?>"> 
                        <span class="w3"><?php echo _DO; ?></span>      
                        <input class="w3 size-input aright" type="text" name="max-size" placeholder="-" value="<?php echo $_GET['max-size'];?>">    
                    </div>     
                </div>
                <div class="search-frame inactive select s-open">
                    <a class="dropdown2-title" href="javascript:;"><?php echo _CIJENA.' '.$cijena_txt;?> (€)<span class="arrow"></span></a>     
                    <div class="hidden-select">
                        <input  class="w3 price-input" type="text" name="min-price" placeholder="-" value="<?php echo $_GET['min-price'];?>"> 
                        <span class="w3"><?php echo _DO;?> </span>      
                        <input class="w3 price-input aright" type="text" name="max-price" placeholder="-" value="<?php echo $_GET['max-price'];?>"> 
                    </div>   
                </div>
               <?php 
                    if($_GET['l']=='1'){
                        $triggerTxt = 'Zagreb';
                    }elseif ($_GET['l']=='6') {
                        $triggerTxt = 'Jadran';
                    }elseif ($_GET['l']=='7') {
                        $triggerTxt = 'Ostalo';
                    }else{
                        $triggerTxt= _LOKACIJA;
                    }
                    ?>
                    <div class="w100" id="location-select">
                        <div class="select-frame inactive main-l search-frame select  ">
                            <input type="hidden" name="l" id="lokacija_data" value="<?php echo $_GET['l']?>">
                            <a href="javascript:;" class="select-trigger"><span class="triggerText"><?php echo $triggerTxt;?> <span class="arrow"></span></a>
                            <select id="lokacija" class="hidden-select" value="" onchange="sjx('generateSublocations',$(this).val(),'<?php echo _LNG?>');return false;">
                                <option id="lokacija1" <?php echo ($_GET['l']=='1')?'selected':''?> value="1">Zagreb</option>
                                <option id="lokacija6" <?php echo ($_GET['l']=='6')?'selected':''?> value="6">Jadran</option>    
                                <option id="lokacija7" <?php echo ($_GET['l']=='7')?'selected':''?> value="7">Ostalo</option>    
                            </select>
                            <div class="select-frame-max">
                                <ul class="select-ul">
                                    <li<?php echo ($_GET['l']=='1')?' class="active"':''?>>
                                        <a rel="lokacija1" data-title="Zagreb">Zagreb</a>
                                    </li>
                                   <li<?php echo ($_GET['l']=='6')?' class="active"':''?>>
                                        <a rel="lokacija6" data-title="Jadran">Jadran</a>
                                    </li>
                                    <li<?php echo ($_GET['l']=='7')?' class="active"':''?>>
                                        <a rel="lokacija7" data-title="<?php echo _OSTALO?>"><?php echo _OSTALO?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="sub1">
                           <?php 
                            $sub_kategorija = Db::query_row('SELECT id,parent_id,title_'._LNG.' FROM city WHERE id='.$_GET['sl']);

                            $sub_cats =  Db::query('SELECT id,parent_id,title_'._LNG.' FROM city WHERE parent_id='.$_GET['l'].' AND id IN('.$lokacije_i.')'  );
                          
                            if($sub_cats){
                                if($_GET['sl']!=''){
                                    $triggerTxt = $sub_kategorija['title_'._LNG];
                                    if($_GET['l']==1){
                                        $triggerTxt_def = 'Odaberite općinu';
                                    }else{
                                        $triggerTxt_def = 'Odaberite regiju';
                                    }
                                }else{
                                     if($_GET['l']==1){
                                        $triggerTxt = 'Odaberite općinu';
                                        $triggerTxt_def = 'Odaberite općinu';
                                    }else{
                                        $triggerTxt = 'Odaberite regiju';
                                        $triggerTxt_def = 'Odaberite regiju';
                                    }
                                }
                                $html .= '
                                    <span class="next-arrow"></span>
                                    <div class="select-frame search-frame select inactive">
                                        <input name="sl" type="hidden" id="sublokacija_data" value="'.$_GET['sl'].'">
                                        <a href="javascript:;" class="select-trigger2"><span class="triggerText">'.$triggerTxt.' <span class="arrow"></span></a>
                                        <select id="sublokacija" class="hidden-select" onchange="sjx(\'generateSubSublocations\',$(this).val(),\''._LNG.'\',\''.$_GET['l'].'\',\'\');return false;">';
                                            $html .= '<option id="sublokacija0" '.(($_GET['sl']=='')?'selected':'').' value="">'.$triggerTxt.'</option>';
                                            foreach ($sub_cats as $sub) {
                                                $html .= '<option id="sublokacija'.$sub['id'].'" '.(($_GET['sl']==$sub['id'])?'selected':'').' value="'.$sub['id'].'">'.$sub['title_'._LNG].'</option>';
                                            }  
                                        $html .= '</select>
                                        <div class="select-frame-max">
                                            <ul class="select-ul">';
                                            $html .= '
                                                <li>
                                                    <a rel="sublokacija0" data-title="'.$triggerTxt_def.'">'.$triggerTxt_def.'</a>
                                                </li>';
                                            foreach ($sub_cats as $sub) {
                                                $html .= '
                                                <li'.(($_GET['sl']==$sub['id'])?' class="active"':'').'>
                                                    <a rel="sublokacija'.$sub['id'].'" data-title="'.$sub['title_'._LNG].'">'.$sub['title_'._LNG].'</a>
                                                </li>';
                                            }
                                            $html .= '</ul>
                                        </div>
                                    </div>';
                                    echo $html;
                                }
                            ?>
                        </div>
                        <div id="sub2">
                             <?php 
                                $subsub_kategorija = Db::query_row('SELECT id,parent_id,title_'._LNG.' FROM city WHERE id='.$_GET['ssl']);
                                $subsub_cats =  Db::query('SELECT id,parent_id,title_'._LNG.' FROM city WHERE parent_id='.$_GET['sl'].' AND id IN('.$lokacije_i.')');
                                if($subsub_cats){
                                    if($_GET['ssl']!=''){
                                        $triggerTxt3 = $subsub_kategorija['title_'._LNG];
                                         if($_GET['l']==1){
                                            $triggerTxt3_def = 'Odaberite kvart';
                                        }else{
                                            $triggerTxt3_def = 'Odaberite grad';
                                        }
                                    }else{
                                         if($_GET['l']==1){
                                            $triggerTxt3 = 'Odaberite kvart';
                                        }else{
                                            $triggerTxt3 = 'Odaberite grad';
                                        }
                                    }
                                    $html2 .= '
                                    <span class="next-arrow"></span>
                                    <div class="select-frame search-frame select inactive">
                                        <input name="ssl" type="hidden" id="subsublokacija_data" value="'.$_GET['ssl'].'">
                                        <a href="javascript:;" class="select-trigger3"><span class="triggerText">'.$triggerTxt3.' <span class="arrow"></span></a>
                                        <select id="subsublokacija" class="hidden-select" onchange="sjx(\'addValue\',$(this).val());return false;">';
                                            $html .= '<option id="subsublokacija0" '.(($_GET['ssl']=='')?'selected':'').' value="">'.$triggerTxt3_def.'</option>';
                                            foreach ($subsub_cats as $subsub) {
                                                $html2 .= '<option id="subsublokacija'.$subsub['id'].'" '.(($_GET['sl']==$subsub['id'])?'selected':'').' value="'.$subsub['id'].'">'.$subsub['title_'._LNG].'</option>';
                                            }  
                                        $html2 .= '</select>
                                        <div class="select-frame-max">
                                            <ul class="select-ul">';
                                            $html .= '
                                                <li>
                                                    <a rel="subsublokacija0" data-title="'.$triggerTxt3_def.'">'.$triggerTxt3_def.'</a>
                                                </li>';
                                            foreach ($subsub_cats as $subsub) {
                                                $html2 .= '
                                                <li'.(($_GET['sl']==$subsub['id'])?' class="active"':'').'>
                                                    <a rel="subsublokacija'.$subsub['id'].'" data-title="'.$subsub['title_'._LNG].'">'.$subsub['title_'._LNG].'</a>
                                                </li>';
                                            }
                                            $html2 .= '</ul>
                                        </div>
                                    </div>';
                                echo $html2;
                                }
                            ?>
                        </div>
                    </div>
                <?php 
                 
                 
                ?>
                
        
                <div class="clearfix"></div>
                <div class="search-more">
                <div class="search-frame inactive select over s-open">
                <?php 
                    if($_GET['rooms']){
                        $names = '';
                        $i=1;
                        foreach ($_GET['rooms'] as $name) {
                            $names .= ($i==1)?$name:','.$name;
                        $i++;
                        }

                    }
                ?>
                    <a href="javascript:;" class="dropdown-title">
                        <span id="rooms" class="inside">
                            <input readonly placeholder="<?php echo _SOBNOST?><?php echo ($_GET['rooms'])?' - '.$names:'';?>" value="<?php echo ($_GET['rooms'] && $_GET['rooms'])? '':''._SOBNOST;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                    <div class="dropdown">
                        <div class="max">
                            <div class="chck-select">   
                                <input name="rooms[]" id="rooms-1" class="rooms" <?php echo(in_array(1, $_GET['rooms']))? "checked" : "";?> data-title="1" value="1" type="checkbox"/>
                                <label for="rooms-1">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label">1</span>
                                </label>
                            </div>
                            <div class="chck-select">   
                                <input name="rooms[]" id="rooms-2" class="rooms" <?php echo(in_array(2, $_GET['rooms']))? "checked" : "";?> data-title="2" value="2" type="checkbox"/>
                                <label for="rooms-2">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label">2</span>
                                </label>
                            </div>
                            <div class="chck-select">   
                                <input name="rooms[]" id="rooms-3" class="rooms" <?php echo(in_array(3, $_GET['rooms']))? "checked" : "";?> data-title="3" value="3" type="checkbox"/>
                                <label for="rooms-3">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label">3</span>
                                </label>
                            </div>
                            <div class="chck-select">   
                                <input name="rooms[]" id="rooms-4" class="rooms" <?php echo(in_array(4, $_GET['rooms']))? "checked" : "";?> data-title="4+" value="4" type="checkbox"/>
                                <label for="rooms-4">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label">4+</span>
                                </label>
                            </div>
                        
                        </div>
                    </div>
                </div>
                <div class="search-frame inactive select">
                <div class="dropdown-title">    
                <?php 
                    if($_GET['id']){
                        $placeholder = _ID_NEKRETNINE.' - '.$_GET['id'];
                    }else{
                        $placeholder = _ID_NEKRETNINE;
                    }
                    $id = ($_GET['id'])?' - '.$_GET['id']:' ';
                ?>
                    <input type="text" name="id" placeholder="<?php echo _ID_NEKRETNINE.' '.$id;?>">      
                    </div> 
                </div>


                    <?php 
                        $floor = Db::query("SELECT id,title_"._LNG." FROM katnost ORDER BY orderby DESC");
                        if($_GET['floor'] && $_GET['floor'] != ''){
                            $selected_f = Db::query("SELECT title_"._LNG." FROM katnost WHERE id IN(".implode(',',$_GET['floor']).") ORDER BY orderby ASC");
                            $name_list_floor = '';
                            $i=1;
                            foreach ($selected_f as $name) {
                                $name_list_floor .= ($i==1)?$name['title_'._LNG]:','.$name['title_'._LNG];
                                $i++;
                            }
                        }
                        if($floor){
                    ?>
                    <div class="search-frame inactive select over s-open">
                       
                        <a href="javascript:;" class="dropdown-title">
                        <span id="floor" class="inside">
                            <input readonly placeholder="<?php echo _KATNOST?>" value="<?php echo ($_GET['floor'] && $_GET['floor'])?_KATNOST.' - '.$name_list_floor:''._KATNOST;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                        <div class="dropdown">
                            <div class="max">
                            <?php 
                            foreach ($floor as $red) { ?>
                                <div class="chck-select">   
                                <input name="floor[]" id="floor-<?php echo $red['id'];?>" class="floor" <?php echo(in_array($red['id'], $_GET['floor']))? "checked" : "";?> data-title="<?php echo $red['title_'._LNG];?>" value="<?php echo $red['id'];?>" type="checkbox"/>
                                <label for="floor-<?php echo $red['id'];?>">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label"><?php echo $red['title_'._LNG];?></span>
                                </label>
                            </div>
                            <?php 
                            } ?>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                <?php 
                    $furnishings = Db::query("SELECT id,title_"._LNG." FROM namjestenost ORDER BY orderby DESC");
                    if($_GET['furnishings'] && $_GET['furnishings'] != ''){
                            $selected_f = Db::query("SELECT title_"._LNG." FROM namjestenost WHERE id IN(".implode(',',$_GET['furnishings']).") ORDER BY orderby ASC");
                            $name_list_furnishings = '';
                            $i=1;
                            foreach ($selected_f as $name) {
                                $name_list_furnishings .= ($i==1)?$name['title_'._LNG]:','.$name['title_'._LNG];
                                $i++;
                            }
                        }
                    if($furnishings){
                ?>
                <div class="search-frame inactive select over s-open">
                    
                    <a href="javascript:;" class="dropdown-title">
                        <span id="furnishings" class="inside"><input readonly placeholder="<?php echo _NAMJESTENOST?>" value="<?php echo ($_GET['furnishings'] && $_GET['furnishings'])?_NAMJESTENOST.' - '.$name_list_furnishings:''._NAMJESTENOST;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                    <div class="dropdown">
                        <div class="max">
                        <?php 
                        $i=1;
                        foreach ($furnishings as $red) { ?>
                            <div class="chck-select">   
                                <input name="furnishings[]" id="furnishings-<?php echo $i;?>" class="furnishings" <?php echo(in_array($i, $_GET['furnishings']))? "checked" : "";?> data-title="<?php echo $red['furnishings'];?>" value="<?php echo $red['furnishings'];?>" type="checkbox"/>

                                <input name="furnishings[]" id="furnishings-<?php echo $red['id'];?>" class="furnishings" <?php echo(in_array($red['id'], $_GET['furnishings']))? "checked" : "";?> data-title="<?php echo $red['title_'._LNG];?>" value="<?php echo $red['id'];?>" type="checkbox"/>
                                <label for="furnishings-<?php echo $red['id'];?>">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label"><?php echo $red['title_'._LNG];?></span>
                                </label>
                            </div>
                        <?php 
                            $i++;
                        } ?>
                        </div>
                    </div>
                </div>
                <?php }
                    $heating = Db::query("SELECT id,title_"._LNG." FROM grijanje ORDER BY orderby DESC");
                    if($_GET['heating'] && $_GET['heating'] != ''){
                            $selected_g = Db::query("SELECT title_"._LNG." FROM grijanje WHERE id IN(".implode(',',$_GET['heating']).") ORDER BY orderby ASC");
                            $name_list_heating = '';
                            $i=1;
                            foreach ($selected_g as $name2) {
                                $name_list_heating .= ($i==1)?$name2['title_'._LNG]:','.$name2['title_'._LNG];
                                $i++;
                            }
                        }
                    if($heating){
                ?>
                <div class="search-frame inactive select over s-open">
                    
                    <a href="javascript:;" class="dropdown-title">
                        <span id="heating" class="inside"><input readonly placeholder="<?php echo _GRIJANJE?>" value="<?php echo ($_GET['heating'] && $_GET['heating'])? _GRIJANJE.' - '.$name_list_heating:''._GRIJANJE;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                    <div class="dropdown">
                        <div class="max">
                        <?php 
                        $i=1;
                        foreach ($heating as $red) { ?>
                            <div class="chck-select">   
                                <input id="heating-<?php echo $i;?>" class="heating" <?php echo(in_array($i, $_GET['heating']))? "checked" : "";?> data-title="<?php echo $red['heating'];?>" value="<?php echo $red['heating'];?>" type="checkbox"/>

                                <input name="heating[]" id="heating-<?php echo $red['id'];?>" class="heating" <?php echo(in_array($red['id'], $_GET['heating']))? "checked" : "";?> data-title="<?php echo $red['title_'._LNG];?>" value="<?php echo $red['id'];?>" type="checkbox"/>
                                <label for="heating-<?php echo $red['id'];?>">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label"><?php echo $red['title_'._LNG];?></span>
                                </label>
                            </div>
                        <?php 
                            $i++;
                        } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

                  



                <?php 
                    $garage = Db::query("SELECT DISTINCT garage FROM items WHERE garage!=0 ORDER BY orderby DESC");
                    if($garage){
                ?>
                <div class="search-frame inactive select over s-open">
                    <a href="javascript:;" class="dropdown-title">
                        <span id="garage" class="inside"><input readonly placeholder="<?php echo _GARAZA?>" value="<?php echo ($_GET['garage'] && $_GET['garage'])? $name_list:''._GARAZA;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                    <div class="dropdown">
                        <div class="max">
                        <?php 
                        $i=1;
                        foreach ($garage as $red) { ?>
                            <div class="chck-select">   
                                <input name="garage[]" id="garage-<?php echo $i;?>" class="garage" data-title="<?php echo $red['garage'];?>" value="<?php echo $red['garage'];?>" type="checkbox"/>
                                <label for="garage-<?php echo $red['id'];?>">
                                    <span class="mark">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="8.886px" height="6.607px" viewBox="57.953 14.778 8.886 6.607" enable-background="new 57.953 14.778 8.886 6.607" xml:space="preserve">
                                        <polyline fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-miterlimit="10" points="58.501,17.833 60.834,20.333 
                                         66.334,15.333 "/>
                                     </svg>
                                     </span>
                                    <span class="label"><?php echo $red['garage'];?></span>
                                </label>
                            </div>
                        <?php 
                            $i++;
                        } ?>
                        </div>
                    </div>
                </div>
                <?php }?>




                  <?php 
                    $energy_cert = Db::query("SELECT id,title_"._LNG." FROM energetski_certifikat ORDER BY orderby DESC");

                    if($energy_cert){
                        if($_GET['energy_cert'] && $_GET['energy_cert'] != ''){
                            $selected_g = Db::query("SELECT title_"._LNG." FROM energetski_certifikat WHERE id IN(".implode(',',$_GET['energy_cert']).") ORDER BY orderby ASC");
                            $name_list_energy_cert = '';
                            $i=1;
                            foreach ($selected_g as $name2) {
                                $name_list_energy_cert .= ($i==1)?$name2['title_'._LNG]:','.$name2['title_'._LNG];
                                $i++;
                            }
                        }
                ?>
                <div class="search-frame inactive select over s-open">
                    
                    <a href="javascript:;" class="dropdown-title">
                        <span id="energy_cert" class="inside"><input readonly placeholder="<?php echo _ENERGETSKI_CERTIFIKAT?>" value="<?php echo ($_GET['energy_cert'] && $_GET['energy_cert'])? _ENERGETSKI_CERTIFIKAT.' - '.$name_list_energy_cert:''._ENERGETSKI_CERTIFIKAT;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                    <div class="dropdown">
                        <div class="max">
                        <?php 
                        $i=1;
                        foreach ($energy_cert as $red) { ?>
                            <div class="chck-select">   
                                <input id="energy_cert-<?php echo $i;?>" class="energy_cert" <?php echo(in_array($i, $_GET['energy_cert']))? "checked" : "";?> data-title="<?php echo $red['energy_cert'];?>" value="<?php echo $red['energy_cert'];?>" type="checkbox"/>

                                <input name="energy_cert[]" id="energy_cert-<?php echo $red['id'];?>" class="energy_cert" <?php echo(in_array($red['id'], $_GET['energy_cert']))? "checked" : "";?> data-title="<?php echo $red['title_'._LNG];?>" value="<?php echo $red['id'];?>" type="checkbox"/>
                                <label for="energy_cert-<?php echo $red['id'];?>">
                                    <span class="mark"><img src="images/check-mark.svg"></span>
                                    <span class="label"><?php echo $red['title_'._LNG];?></span>
                                </label>
                            </div>
                        <?php 
                            $i++;
                        } ?>
                        </div>
                    </div>
                </div>
                <?php }?>

                 <?php 
                    $year_built = Db::query("SELECT DISTINCT year_built FROM items WHERE year_built!='' ORDER BY year_built DESC");
                    if($year_built){
                        if($_GET['year_built'] && $_GET['year_built'] != ''){
                            $selected_g = Db::query("SELECT title_"._LNG." FROM energetski_certifikat WHERE id IN(".implode(',',$_GET['year_built']).") ORDER BY orderby ASC");
                            $name_list_year_built = '';
                            $i=1;
                            foreach ($_GET['year_built'] as $name2) {
                                $name_list_year_built .= ($i==1)?$name2:','.$name2;
                                $i++;
                            }
                        }
                ?>
                <div class="search-frame inactive select over s-open">
                    <a href="javascript:;" class="dropdown-title">
                        <span id="year_built" class="inside"><input readonly placeholder="<?php echo _GODINA_IZGRADNJE?>" value="<?php echo ($_GET['year_built'] && $_GET['year_built'])? $name_list_year_built:''._GODINA_IZGRADNJE;?>"/>
                        </span>
                        <span class="arrow"></span>
                    </a>
                    <div class="dropdown">
                        <div class="max">
                        <?php 
                        $i=1;
                        foreach ($year_built as $red) { ?>
                            <div class="chck-select">   
                                <input name="year_built[]" id="year_built-<?php echo $i;?>" class="year_built" <?php echo(in_array($i, $_GET['year_built']))? "checked" : "";?> data-title="<?php echo $red['year_built'];?>" value="<?php echo $red['year_built'];?>" type="checkbox"/>
                                <label for="year_built-<?php echo $red['id'];?>">
                                    <span class="mark">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="8.886px" height="6.607px" viewBox="57.953 14.778 8.886 6.607" enable-background="new 57.953 14.778 8.886 6.607" xml:space="preserve">
                                        <polyline fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-miterlimit="10" points="58.501,17.833 60.834,20.333 
                                         66.334,15.333 "/>
                                     </svg>
                                     </span>
                                    <span class="label"><?php echo $red['year_built'];?></span>
                                </label>
                            </div>
                        <?php 
                            $i++;
                        } ?>
                        </div>
                    </div>
                </div>
                <?php }?>

                </div>
                <div class="txt-center">
                    <a class="btn inline s-more" href="javascript:;"><?php echo _DETALJNIJA_TRAZILICA;?></a>
                    <input type="submit" value="<?php echo _PRETRAZITE;?>" name="search" class="search-btn inline" />
                </div>
            </div>
        </form>
    </div>
</div>