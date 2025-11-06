<?php 
	$o_nama = explode('-', _su2); 
    $o_nama_id = $o_nama[count($o_nama) - 1];
    $o_nama = Db::query_row("SELECT * FROM o_nama WHERE id = ".$o_nama_id);
    $mjeseci = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $mjesec = date('n', strtotime($o_nama['created']));
    $godina = date('Y', strtotime($o_nama['created']));
    $dan = date('d', strtotime($o_nama['created']));

    $prva_slika = Db::query_row('SELECT id,photo_name FROM site_photos WHERE table_name = "o_nama" AND table_id = '.$o_nama['id'].' ORDER BY orderby ASC LIMIT 1');
    $slike = Db::query('SELECT * FROM site_photos WHERE table_name = "o_nama" AND table_id = '.$o_nama['id'].' AND table_id != '.$prva_slika['id'].' ORDER BY orderby ASC');

    $broj_slika = count($slike); 

    $bg = '<div class="top"><img class="top-img" src="images/bg.jpg" /><div class="center"></div></div>';
    $title = $o_nama['title_'._LNG];
    $clanci = explode(",", $o_nama['multi_categories']);
    $news = Db::query('SELECT * FROM news ORDER BY created DESC');
?>
<div class="bc row xs">
    <div class="center"><a href=""><?php echo _HOME;?></a><span> | </span><span><?php echo _O_NAMA;?></span></div>
</div>
<div class="row content xs txt">
    <div class="center top-blog">
            <h1 class="txt-center"><?php echo $o_nama['title_'._LNG];?></h1>

            <?php 
            if($o_nama['pozicija']=='c'){
            	echo generate_slider_gallery($o_nama_id,'o_nama');
            }elseif($o_nama['pozicija']=='l'){
            	echo generate_gallery_l($o_nama_id,'o_nama');
            }elseif($o_nama['pozicija']=='r'){
            	echo generate_gallery_r($o_nama_id,'o_nama');
            }
            echo $o_nama['text_'._LNG];

            if($o_nama['video_url']){
            ?>
            <div class="clearfix"></div>
            <h4><?php echo _POGLEDAJTE_VIDEO?></h4>
            <iframe class="youtube" width="100%" height="550" src="<?php echo generiraj_youtube_embed_link($o_nama['video_url']);?>" frameborder="0" allowfullscreen></iframe>
            <?php } ?>
    </div> 
    <?php 
    if($o_nama['id']==6){ //NOVOSTI?>

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
                        <?php echo '<p>'.cut_paragraph(strip_tags($red['text_'._LNG]), 200).'<a href="'._SITE_URL._LNG.'/'._URL_NEWS.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id'].'" class="more">'._DETALJNIJE.'</a></p>';?>
                       
                    </div>
            <?php 
                    
                $i++;
                } 
            ?>
        </div>
  </div>  

 <?php 
    }
  
    if($o_nama['id']==1){ //NAŠ TIM
        $uvod = Db::query_row("SELECT title_"._LNG.",text_"._LNG." FROM single WHERE id = 5");
        $nas_tim = Db::query("SELECT * FROM our_team ORDER BY orderby DESC");
        ?>
        	<div class=" center nas-tim txt-center">
        		<h2 class="txt-center"><?php echo $uvod['title_'._LNG];?></h2>
        		<?php echo $uvod['text_'._LNG];?>
        	</div>
        	<div class="center">
        		<div class="nas-tim-frame">
        	       <?php if($nas_tim){?>
                        <div class="team boxes">
                            <?php 
                                foreach ($nas_tim as $red) {
                                $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "our_team" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
                                $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
                            ?>
                            <a href="<?php echo _SITE_URL._LNG.'/'._URL_AGENT.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>" class="team-box box">
                                <span class="img-frame">
                                    <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=305&zc=2" />
                                </span>
                                <span class="height">
                                    <h4><?php echo $red['title_hr']?></h4>
                                    <span class="tel">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="5 4 22 24"><path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M20.84 24.368c-.268-.038-.537-.07-.804-.116-1.601-.272-3.071-.878-4.396-1.809-2.843-1.999-4.57-4.729-5.247-8.128-.16-.808-.238-1.625-.161-2.451.02-.211.075-.405.192-.584.417-.641.984-1.12 1.635-1.504.319-.188.673-.183 1.002-.037.288.127.569.282.823.466.126.091.205.266.27.416.267.633.402 1.302.533 1.972.051.262.079.528.13.79.047.235-.004.449-.146.63-.165.209-.346.407-.534.596-.214.213-.441.412-.67.609-.071.063-.086.113-.053.201.285.772.705 1.47 1.181 2.136.894 1.249 2.015 2.255 3.31 3.07.345.217.347.204.654-.056.302-.255.608-.507.921-.749.292-.225.611-.287.978-.177.805.243 1.604.496 2.366.86.349.167.612.396.751.772.122.331.273.656.208 1.016-.033.18-.096.374-.202.517-.408.542-.896 1.006-1.497 1.333-.176.096-.372.152-.56.227h-.684z"/></svg>
                                        <?php echo $red['tel']?>
                                    </span>
                                    <div class="clearfix"></div>
                                    <span class="mail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="5 4 22 24"><path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M8.975 12.375v-.336c.027-.116.047-.235.082-.349.102-.334.272-.62.583-.805.24-.143.508-.165.776-.165 2.567-.002 5.136-.001 7.703-.001 1.487 0 2.976-.001 4.464 0 .574 0 1.007.248 1.274.758.067.128.096.277.143.416v.361c-.013.062-.026.124-.038.186-.146.71-.559 1.26-1.111 1.686-.713.55-1.467 1.049-2.205 1.567-.595.416-1.191.832-1.79 1.242-.524.357-1.046.719-1.583 1.056-.512.321-1.047.315-1.565.005-.325-.194-.644-.4-.955-.615-.723-.5-1.438-1.008-2.158-1.511-.749-.524-1.505-1.04-2.249-1.569-.498-.355-.897-.804-1.172-1.354-.091-.179-.134-.381-.199-.572z"/><path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M8.975 14.535c.536.579 1.217.966 1.854 1.412.594.418 1.198.822 1.797 1.233.029.021.055.046.107.089-.182.174-.359.338-.531.509-.865.864-1.729 1.729-2.594 2.594-.209.208-.422.413-.633.62v-6.457z"/><path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M24 20.992c-.254-.25-.509-.496-.761-.747-.819-.818-1.638-1.639-2.458-2.458-.173-.171-.35-.338-.54-.522.123-.087.225-.159.328-.231.863-.59 1.732-1.172 2.586-1.775.301-.213.564-.48.845-.723v6.456z"/><path fill-rule="evenodd" clip-rule="evenodd" fill="#BB9B69" d="M23.508 22.207c-.163.162-.382.252-.62.295-.105.02-.216.023-.322.023-4.053.001-8.105.001-12.157.001-.307 0-.596-.059-.85-.242-.035-.025-.067-.055-.091-.074 1.418-1.417 2.832-2.829 4.254-4.249.299.211.617.446.944.666.472.314.974.565 1.542.656.441.072.855-.038 1.262-.202.652-.265 1.191-.708 1.76-1.106.014-.01.025-.018.028-.02 1.417 1.418 2.831 2.832 4.25 4.252z"/></svg>
                                        <?php echo $red['mail']?>
                                    </span>
                                </span>
                            </a>
                           
                        <?php 
                            }
                        ?>  
                    </div>
                    <?php   
                    } 
                    ?>
        		</div>
        	</div>
        <?php } ?>
        <?php 
            if($o_nama['multi_categories']){
                echo '<div class="center"><div class="others">';
                foreach ($clanci as $id) {
                $clanak = Db::query_row("SELECT * FROM o_nama WHERE id=".$id." ORDER BY orderby ASC");
                $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "o_nama" AND table_id = '.$clanak['id'].' ORDER BY orderby ASC LIMIT 1');
                $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
                $naslov = $clanak['title_'._LNG];
                $url = _SITE_URL._LNG.'/'._URL_O_NAMA.'/'.clean_uri($clanak['title_'._LNG]).'-'.$clanak['id'];
            ?>
                 <div class="w1">
                     <a href="<?php echo $url;?>">  
                        <h3><?php echo $clanak['title_'._LNG];?></h3>
                    </a>
                    <?php echo ($clanak['text_'._LNG])?'<p>'.cut_paragraph(strip_tags($clanak['text_'._LNG]), 200).'<a href="'.$url.'" class="more">'._DETALJNIJE.'</a></p>':'';?>
                </div>   
            <?php } 
                echo '</div></div>';
            }
        ?>
</div>