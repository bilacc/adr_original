<?php   
    if(_su2 != '_su2' && !(is_numeric(_su2))){
 		$our_team = explode('-', _su2); 
        $our_team_id = $our_team[count($our_team) - 1];
        $our_team = Db::query_row("SELECT * FROM our_team WHERE id = ".$our_team_id);

        $items =  Db::query('SELECT * FROM items WHERE aktivno = "da" AND agent_id = '.$our_team_id.' ORDER BY orderby DESC');
        $title = _NAS_TIM.' - '.$our_team['title_hr'];
    $bg = '<div class="top"><img class="top-img" src="images/bg.jpg" /><div class="center"><h1>'._NAS_TIM.'</h1></div></div>'; 
    $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "our_team" AND table_id = '.$our_team['id'].' ORDER BY orderby ASC LIMIT 1');
    $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
 }else{

	$o_nama = Db::query_row("SELECT * FROM single WHERE id=1 ");
	$title = _NAS_TIM;
    $description = ($o_nama['page_description_'._LNG] != '')?  $o_nama['page_description_'._LNG]:'Elion nekretnine';
    $keywords = ($o_nama['page_keywords_'._LNG] != '')?  $o_nama['page_keywords_'._LNG]:'Elion nekretnine';

    $nas_tim = Db::query("SELECT * FROM our_team ORDER BY orderby DESC");
   
    $bg = '<div class="top"><img class="top-img" src="images/contact.jpg" /><div class="center"><h1>'._NAS_TIM.'</h1></div></div>'; 
}
if(_su2 != '_su2' && !(is_numeric(_su2))){?>
<div class="row content">
    <div class="center">
    	<div class="team-box box team-details">
    	<h2><?php echo $our_team['title_hr']?></h2>
    		<span class="img-frame">
                <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=305&h=305&zc=1" />
           </span>
           <?php echo $our_team['text_'._LNG];?>
    	</div>
    </div>
</div>
<?php }else{
?>
<div class="row content">
    <div class="center">
        <?php if($nas_tim){?>
        <div class="team boxes">
            <?php 
                foreach ($nas_tim as $red) {
                $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "our_team" AND table_id = '.$red['id'].' ORDER BY orderby ASC LIMIT 1');
                $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
            ?>
                <a href="<?php echo _SITE_URL._LNG.'/'._URL_NAS_TIM.'/'.clean_uri($red['title_'._LNG]).'-'.$red['id']?>" class="team-box box">
                    <span class="img-frame">
                        <img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL.$slika;?>&w=305&h=305&zc=1" />
                    </span>
                    <span class="height">
                        <h4><?php echo $red['title_hr']?></strong></h4>
                        
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
                            <?php echo $red['tel']?>
                        </span>
                        <div class="clearfix"> </div>
                        <span class="mail">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 width="22px" height="24px" viewBox="5 4 22 24" enable-background="new 5 4 22 24" xml:space="preserve">
                                <g>
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
                                </g>
                            </svg>
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

