<?php 
    if(_su2 != '_su2' && !(is_numeric(_su2))){
        $blog = explode('-', _su2); 
        $blog_id = $blog[count($blog) - 1];
        $blog = Db::query_row("SELECT * FROM blog WHERE id = ".$blog_id);
        $mjeseci = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $mjesec = date('n', strtotime($blog['created']));
        $godina = date('Y', strtotime($blog['created']));
        $dan = date('d', strtotime($blog['created']));

        $prva_slika = Db::query_row('SELECT id,photo_name FROM site_photos WHERE table_name = "blog" AND table_id = '.$blog['id'].' ORDER BY orderby ASC LIMIT 1');
        $slike = Db::query('SELECT * FROM site_photos WHERE table_name = "blog" AND table_id = '.$blog['id'].' AND table_id != '.$prva_slika['id'].' ORDER BY orderby ASC');

        $title = $blog['title_'._LNG].' - Blog';
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

        $svi = Db::query('SELECT id FROM blog');
        $page = (is_numeric(_su2)) ? _su2 : 1;
        $start = $on_page * ($page-1);

        $blog = Db::query('SELECT * FROM blog ORDER BY '.$order_date.$order.' LIMIT '.$start.', '.$on_page);
        $ukupno = count($sav_smjestaj);
        $stranica = ceil($ukupno/$on_page);
        
        $sort_url_date = _SITE_URL._LNG.'/'._URL_BLOG.'/'.$remove_date.'&order-date=';
        $sort_date = ($_GET['order-date'] == 'date1')? 'date2':'date1';
        $sort_date_link = $sort_url_date.$sort_date;

        $title = _BLOG;
    // end detalji
        $blogs = Db::query('SELECT * FROM blog');
    }
$bg = '<div class="top"><img class="top-img" src="images/blog.jpg" /><div class="center"><h1>BLOG</h1></div></div>';

?>

<?php 
    if(_su2 != '_su2' && !(is_numeric(_su2))){
?>
<div class="row content xs">
    <div class="center blog">
        <div class="top-blog">
            <h1 class="txt-center"><?php echo $blog['title_'._LNG];?></h1>
            <?php 
            if($blog['pozicija']=='c'){
                echo generate_slider_gallery($blog_id,'blog');
            }elseif($blog['pozicija']=='l'){
                echo generate_gallery_l($blog_id,'blog');
            }elseif($blog['pozicija']=='r'){
                echo generate_gallery_r($blog_id,'blog');
            }
            ?>
        
            
                <div class="autor">
                    <span class="date"><?php echo $dan; ?>. <?php echo $mjeseci[$mjesec - 1]; ?> <?php echo $godina; ?>.</span>
                    <?php if($blog['autor_hr']) echo ''._AUTOR.': '.$blog['autor_hr'].'';?>
                </div>
                
           
            <?php 
                echo $blog['text_'._LNG];
            ?>
        </div>
    

  </div>  
</div>









<?php }else{ ?>
<div class="bc row">
    <div class="center txt-center">
        <div class="sort-range">
            <span class="sort-title"><?php echo _SORTIRAJ_PO;?></span>
            <a href="<?php echo $sort_date_link;?>" class="range">
            <svg version="1.1" id="range_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="7.683px" height="10.309px" viewBox="0 0 7.683 10.309" enable-background="new 0 0 7.683 10.309" xml:space="preserve">
                <path fill-rule="evenodd" clip-rule="evenodd" fill="#BC9B6A" d="M0.022,6.771c-0.047-0.334,0.3-0.323,0.726-0.323
                c1.721,0,3.43,0,5.077,0c0.523,0,1.742-0.133,1.833,0.202c0.085,0.308-0.553,0.794-0.745,0.987C6.265,8.286,5.761,8.789,5.14,9.41
                c-0.25,0.25-0.618,0.739-0.927,0.846c-0.18,0.063-0.522,0.077-0.746,0c-0.25-0.086-0.631-0.551-0.907-0.826
                C1.84,8.708,1.176,8.024,0.425,7.274C0.29,7.138,0.053,6.986,0.022,6.771z"/>
                <path fill-rule="evenodd" clip-rule="evenodd" fill="#BC9B6A" d="M3.709,0c0.087,0,0.175,0,0.262,0
                C4.395,0.063,4.47,0.237,4.737,0.504C5.437,1.203,6.204,1.951,6.914,2.66c0.149,0.149,0.827,0.677,0.766,0.987
                C7.608,4.002,6.356,3.869,5.866,3.869c-1.466,0-2.595,0-4.07,0c-0.521,0-1.741,0.13-1.793-0.242
                C-0.026,3.419,0.21,3.238,0.365,3.083c0.792-0.792,1.489-1.51,2.256-2.277C2.921,0.507,3.273,0.052,3.709,0z"/>
            </svg>
            <?php echo _PO_DATUMU;?>
            </a>
        </div>
        <div class="on-page">
            <?php echo _REZULTATA_PO_STRANICI;?>
            <div class="on-page-frame"> 
                <select name="on-page" onchange="window.location.href='<?php echo _SITE_URL._LNG.'/'._URL_BLOG.''.$remove_on_page.'&on-page=';?>'+this.value">
                    <option value="16" <?php echo($_GET['on-page'] == '16')? 'selected' : '';?>>16</option>
                    <option value="32" <?php echo($_GET['on-page'] == '32')? 'selected' : '';?>>32</option>
                    <option value="48" <?php echo($_GET['on-page'] == '48')? 'selected' : '';?>>48</option>
                    <option value="64" <?php echo($_GET['on-page'] == '64')? 'selected' : '';?>>64</option>
                    <option value="78" <?php echo($_GET['on-page'] == '78')? 'selected' : '';?>>78</option>
                    <option value="96" <?php echo($_GET['on-page'] == '96')? 'selected' : '';?>>96</option>
                </select>
            </div>
        </div>
    </div>
</div>  
<div class="row content">
    <div class="center txt-center blog">
        <div class="list">  
        <?php 
            $i=1;
            foreach ($blog as $red) {
            $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "blog" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
            $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
            $mjeseci = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
            $mjesec = date('n', strtotime($red['created']));
            $godina = date('Y', strtotime($red['created']));
            $dan = date('d', strtotime($red['created']));
        ?>

            
                    <div class="w1">
                         <a href="<?php echo _SITE_URL._LNG.'/'._URL_BLOG.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']; ?>">
                            <span class="img-frame">
                                <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=450&h=280&zc=1" /> 
                            </span>
                            <div class="autor">
                                <span class="date"><?php echo $dan; ?>. <?php echo $mjeseci[$mjesec - 1]; ?> <?php echo $godina; ?>.</span>
                                <?php if($red['autor_hr']) echo ''._AUTOR.': '.$red['autor_hr'].'';?>
                            </div>
                            <h3><?php echo $red['title_'._LNG];?></h3>
                        </a>
                        <?php echo '<p>'.cut_paragraph(strip_tags($red['text_'._LNG]), 200).'<a href="'._SITE_URL._LNG.'/'._URL_BLOG.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id'].'" class="more">'._DETALJNIJE.'</a></p>';?>
                       
                    </div>
            <?php 
                    
                $i++;
                } 
            ?>
        </div>

  </div>  
</div>

<?php } ?>