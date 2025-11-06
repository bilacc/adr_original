<?php 
    if(_su3 != '_su3' && !(is_numeric(_su3))){
        $usluge = explode('-', _su3); 
        $usluge_id = $usluge[count($usluge) - 1];
        $usluge = Db::query_row("SELECT * FROM services WHERE id = ".$usluge_id);
        $title = $usluge['title_'._LNG];
        $cat = Db::query_row("SELECT * FROM categories_usluge WHERE id = ".$usluge['categories_id']);
      
       if($usluge['prikazi']=='ne'){
            $broj_slika = 0;
       }else{
        $prva_slika = Db::query_row('SELECT id,photo_name FROM site_photos WHERE table_name = "services" AND table_id = '.$usluge['id'].' ORDER BY orderby ASC LIMIT 1');
        $slike = Db::query('SELECT * FROM site_photos WHERE table_name = "services" AND table_id = '.$usluge['id'].' AND table_id != '.$prva_slika['id'].' ORDER BY orderby ASC');
        $broj_slika = count($slike);
       }
        

        

        if($cat['id']==1){
        $bg = '<div class="top"><img class="top-img" src="images/usluge.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>'; 
        $class = ' bg-1'; 
       }elseif($cat['id']==2){
        $bg = '<div class="top"><img class="top-img" src="images/kupci.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>';
        $class = 'bg-2'; 
       }elseif($cat['id']==3){
        $bg = '<div class="top"><img class="top-img" src="images/najmodavci.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>';
        $class = 'bg-3'; 
       }else{
         $bg = '<div class="top"><img class="top-img" src="images/usluge.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>';
         $class = 'bg-1'; 
       }
       $clanci = explode(",", $usluge['multi_categories']);

    }elseif(_su2 != '_su2' && !(is_numeric(_su2))){
        $cat = explode('-', _su2); 
        $cat_id = $cat[count($cat) - 1];
        $cat = Db::query_row("SELECT * FROM categories_usluge WHERE id = ".$cat_id);
        if($cat_id==1){
        $bg = '<div class="top"><img class="top-img" src="images/usluge.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>'; 
       }elseif($cat_id==2){
        $bg = '<div class="top"><img class="top-img" src="images/kupci.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>';
       }elseif($cat_id==3){
        $bg = '<div class="top"><img class="top-img" src="images/najmodavci.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>';
       }else{
        $bg = '<div class="top"><img class="top-img" src="images/usluge.jpg" /><div class="center"><h1>'.$cat['title_'._LNG].'</h1></div></div>';
       }
        

        $usluge = Db::query("SELECT * FROM services WHERE categories_id = ".$cat_id." AND usluga_id=0 ORDER BY orderby DESC");
        $title = $cat['title_'._LNG];

        
    }else{
        $usluge = Db::query("SELECT * FROM categories_usluge ORDER BY orderby ASC");
        $title = 'Usluge';
        $bg = '<div class="top"><img class="top-img" src="images/usluge.jpg" /><div class="center"><h1>Usluge</h1></div></div>';
        $uvod = Db::query_row("SELECT title_"._LNG.",text_"._LNG." FROM single WHERE id = 4");
    }
    
    

?>

<?php 
    if(_su3 != '_su3' && !(is_numeric(_su3))){
    $sub_services = Db::query("SELECT * FROM services WHERE usluga_id = ".$usluge_id." ORDER BY orderby ASC");
?>
<div class="row bc">
<div class="center">    
    <a href=""><?php echo _HOME?></a><span>|</span>
    <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE?>"><?php echo _USLUGE?></a><span>|</span>
    <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($cat['title_'._LNG]).'-'.$cat['id']?>"><?php echo $cat['title_'._LNG]?></a><span>|</span></div>
</div>
<div class="row content <?php echo $class;?>"> 
<div class="center txt-center blog">
        <div class="top-blog">
            <h1 class="txt-center"><?php echo $usluge['title_'._LNG];?></h1>
             <?php 
             if($usluge['prikazi']=='da'){
                if($usluge['pozicija']=='c'){
                    echo generate_slider_gallery($usluge_id,'services');
                }elseif($usluge['pozicija']=='l'){
                    echo generate_gallery_l($usluge_id,'services');
                }elseif($usluge['pozicija']=='r'){
                    echo generate_gallery_r($usluge_id,'services');
                }

        }

            echo $usluge['subtitle_'._LNG];
            if($sub_services){
                echo '<div class="subservices">';
                foreach ($sub_services as $red) {
                    $naslov = $red['title_'._LNG];
                ?>
                    <div class="clearfix"> </div>
                    <a href="javascript:;" class="sub-service-title">
                        <h3 class="title"><?php echo $naslov;?></h3><span class="arrow"></span>
                    </a>
                    <div class="txt-h">
                        <?php echo $red['text_'._LNG]?>
                    </div>
                <?php }
            echo '</div>';
            }

            echo $usluge['text_'._LNG];
            if($usluge['multi_categories']){
                echo '<div class="others">';
                foreach ($clanci as $id) {
                $clanak = Db::query_row("SELECT * FROM services WHERE id=".$id." ORDER BY orderby ASC");
                $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "services" AND table_id = '.$clanak['id'].' ORDER BY orderby ASC LIMIT 1');
                $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
                $naslov = $clanak['title_'._LNG];
                $c_cat = Db::query_row("SELECT * FROM categories_usluge WHERE id = ".$clanak['categories_id']);
                $url = _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($c_cat['title_'._LNG]).'/'.clean_uri($clanak['title_'._LNG]).'-'.$clanak['id'];
            ?>
                 <div class="w1">
                     <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($c_cat['title_'._LNG]).'/'.clean_uri($clanak['title_'._LNG]).'-'.$clanak['id']; ?>">  
                        <h3><?php echo $clanak['title_'._LNG];?></h3>
                    </a>
                    <?php echo ($clanak['subtitle_'._LNG])?'<p>'.cut_paragraph(strip_tags($clanak['subtitle_'._LNG]), 200).'<a href="'.$url.'" class="more">'._DETALJNIJE.'</a></p>':'';?>
                </div>   
            <?php } 
                echo '</div>';
            }

            ?>
        </div>
<?php }elseif(_su2 != '_su2' && !(is_numeric(_su2))){?>
<div class="row bc">
<div class="center">    
    <a href=""><?php echo _HOME?></a><span>|</span>
    <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE?>"><?php echo _USLUGE?></a><span>|</span>
    <span><?php echo $cat['title_'._LNG]?></span></div>
</div>
<div class="row content <?php echo $class;?>"> 
<div class="center txt-center services">
<?php echo $cat['text_'._LNG];?>
<?php 
if($usluge){?>
        <div class="items">
            <?php foreach ($usluge as $red) {
                $cat = Db::query_row("SELECT id,title_"._LNG." FROM categories_usluge WHERE id =".$red['categories_id']);
                $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "services" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
                $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
                $naslov = $red['title_'._LNG];
                
            ?>
                <a href="<?php echo _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>" class="box h">
                    <span class="img-frame">    
                        <img alt="<?php echo $red['title_'._LNG];?>" src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika?>&w=350&h=350&zc=1" />
                    </span>
                    <span class="title"><?php echo $naslov;?></span>
                </a>
            <?php } ?>
        </div>
    <?php } }else{ ?>
    <div class="row bc">
<div class="center">    
    <a href=""><?php echo _HOME?></a><span>|</span><span><?php echo _USLUGE?></span>
   </div>
</div>
<div class="row content <?php echo $class;?>"> 
    <div class="center txt-center services">
    <?php echo ($uvod['title_'._LNG])?'<h1 class="txt-center">'.$uvod['title_'._LNG].'</h1>':'';?>
       
        <?php echo $uvod['text_'._LNG];?>
        <?php if($usluge){?>
        <div class="items">
            <?php foreach ($usluge as $red) {
                $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "categories_usluge" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
                $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
                $naslov = $red['title_'._LNG];
                $usluge = Db::query("SELECT id,title_"._LNG." FROM services WHERE categories_id=".$red['id']." ORDER BY orderby ASC");
                $br_s = count($usluge);
                if($br_s==1){
                   $usluge_row = Db::query_row("SELECT id,title_"._LNG." FROM services WHERE categories_id=".$red['id']." ORDER BY orderby ASC");
                   $href= _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($red['title_'._LNG]).'/'.clean_uri($usluge_row['title_'._LNG]).'-'.$usluge_row['id'];
               }elseif($br_s>1){
                    $href= _SITE_URL._LNG.'/'._URL_USLUGE.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id'];
               }
            ?>
                <a href="<?php echo $href?>" class="box h">
                    <span class="img-frame">    
                        <img alt="<?php echo $red['title_'._LNG];?>" src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika?>&w=350&h=350&zc=1" />
                    </span>
                    <span class="title"><?php echo $naslov;?></span>
                </a>
            <?php } ?>
        </div>
        <?php }?>
    <?php } ?>
    </div>
</div>


