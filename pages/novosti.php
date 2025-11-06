<?php 
    if(_su2 != '_su2' && !(is_numeric(_su2))){
        $news = explode('-', _su2); 
        $news_id = $news[count($news) - 1];
        $news = Db::query_row("SELECT * FROM news WHERE id = ".$news_id);
        $mjeseci = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $mjesec = date('n', strtotime($news['created']));
        $godina = date('Y', strtotime($news['created']));
        $dan = date('d', strtotime($news['created']));

        $prva_slika = Db::query_row('SELECT id,photo_name FROM site_photos WHERE table_name = "news" AND table_id = '.$news['id'].' ORDER BY orderby ASC LIMIT 1');
        $slike = Db::query('SELECT * FROM site_photos WHERE table_name = "news" AND table_id = '.$news['id'].' AND table_id != '.$prva_slika['id'].' ORDER BY orderby ASC');
        $broj_slika = count($slike);

        $title =  $news['title_'._LNG];

    // end detalji
    }else{
        if (strpos($_SERVER['QUERY_STRING'], '&') !== false) {
            $nastavak = substr($_SERVER['QUERY_STRING'], strpos($_SERVER['QUERY_STRING'], "&") + 1); 
            $nastavak = ($nastavak)? '?'.$nastavak : '';
        }
        if($_GET['order-date']=='date1'){
            $order_date = ' created ASC, ';
        }elseif($_GET['order-date']=='date2'){
            $order_date = ' created DESC, ';
        }
        $order = ' orderby DESC';
        $on_page = ($_GET['on-page'])? $_GET['on-page'] : 16;
        $order_d = ($_GET['order-date'])? $_GET['order-date'] : 'date1';

        $remove_on_page = remove_url_action($nastavak, 'on-page');
        $remove_date = remove_url_action($nastavak, 'order-date');
        $nastavak .= '&order-date='.$order_d;
        $nastavak .= '&on-page='.$on_page;

        $svi = Db::query('SELECT id FROM news');
        $page = (is_numeric(_su2)) ? _su2 : 1;
        $start = $on_page * ($page-1);

        $news = Db::query('SELECT * FROM news ORDER BY '.$order_date.$order.' LIMIT '.$start.', '.$on_page);
        $ukupno = count($sav_smjestaj);
        $stranica = ceil($ukupno/$on_page);
        
        $sort_url_date = _SITE_URL._LNG.'/'._URL_NEWS.'/'.$remove_date.'&order-date=';
        $sort_date = ($_GET['order-date'] == 'date1')? 'date2':'date1';
        $sort_date_link = $sort_url_date.$sort_date;

        $title = _NOVOSTI;
    // end detalji
        $news = Db::query('SELECT * FROM news');
    }
$bg = '<div class="top"><img class="top-img" src="images/blog.jpg" /><div class="center"><h1>'._NOVOSTI.'</h1></div></div>';

?>
<div class="bc row xs">
    <div class="center"><a href=""><?php echo _HOME;?></a><span> | </span><span><?php echo NOVOSTI;?></span></div>
</div>
<?php 
    if(_su2 != '_su2' && !(is_numeric(_su2))){
?>
<div class="row content xs txt">
    <div class="center top-blog">

            <h1 class="txt-center"><?php echo $news['title_'._LNG];?></h1>
           <?php 
            if($news['pozicija']=='c'){
                echo generate_slider_gallery($news_id,'news');
            }elseif($news['pozicija']=='l'){
                echo generate_gallery_l($news_id,'news');
            }elseif($news['pozicija']=='r'){
                echo generate_gallery_r($news_id,'news');
            }
            ?>
                
                
           
            <?php 
                echo $news['text_'._LNG];
            ?>
 
    

  </div>  
</div>









<?php }else{ ?>

<div class="row content">
    <div class="center txt-center news">
        <div class="list">  
        <?php 
            $i=1;
            foreach ($news as $red) {
            $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "news" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
            $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
            $mjeseci = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
            $mjesec = date('n', strtotime($red['created']));
            $godina = date('Y', strtotime($red['created']));
            $dan = date('d', strtotime($red['created']));
        ?>

            
                    <div class="w1">
                         <a href="<?php echo _SITE_URL._LNG.'/'._URL_NEWS.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']; ?>">
                            <span class="img-frame">
                                <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=450&h=280&zc=1" /> 
                            </span>
                            <div class="autor">
                                <span class="date"><?php echo $dan; ?>. <?php echo $mjeseci[$mjesec - 1]; ?> <?php echo $godina; ?>.</span>
                               
                            </div>
                            <h3><?php echo $red['title_'._LNG];?></h3>
                        </a>
                        <?php echo '<p>'.cut_paragraph(strip_tags($red['text_'._LNG]), 200).'<a href="" class="more">'._DETALJNIJE.'</a></p>';?>
                       
                    </div>
            <?php 
                    
                $i++;
                } 
            ?>
        </div>

  </div>  
</div>

<?php } ?>