<?php 
    $comments = Db::query("SELECT * FROM komentari WHERE front_page='da' ORDER BY orderby ASC");
?>     
     <div class="footer-top row content"><div class="line"></div>
        <div class="center<?php echo (!$comments)?' txt-center':''?>">
            <a href="<?php echo _SITE_URL._LNG.'/'._URL_KONTAKT?>" class="contact">
                <?php echo _PRODAJETE_NEKRETNINU;?><br>
                <span> <?php echo _KONTAKTIRAJTE_NAS;?> </span>
            </a>
            <a href="<?php echo _SITE_URL._LNG.'/'._URL_UNOS_KOMENTARA?>" class="comment">
                <?php echo _IMATE_POHVALU;?><br>
                <span class="pen"> <?php echo _OSTAVITE_KOMENTAR;?> </span>
            </a>
            <?php if($comments){?>
                <div class="client-comments">
                    <ul>
                        <?php foreach ($comments as $red) {?>
                            <li>
                                <span class="quote"><img src="images/quote.svg"></span>
                                <?php echo '<p>'.cut_paragraph(strip_tags($red['text_hr']), 160).'&nbsp;<a href="javascript:;" id="c'.$red['id'].'" class="c-more"> '._DETALJNIJE.'</a></p>';?>
                                <?php if($red['anoniman']!=1){
                                   echo '<strong>'.$red['autor_hr'].'</strong>';
                                } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } 
              
                    foreach ($comments as $red) {?>
                    <div class="comment_container" id="c<?php echo $red['id']?>-container">

                        <a href="javascript:;" class="close-c"><svg xmlns="http://www.w3.org/2000/svg" width="15.945" height="15.945" viewBox="9.276 -44.723 15.945 15.945"><path fill="none" stroke="#FFF" stroke-miterlimit="10" d="M9.632-44.37l15.236 15.24M24.868-44.37L9.63-29.13"></path></svg></a>
                        <div class="p">
                            <span class="quote"><img src="images/quote.svg"></span>
                            <?php echo $red['text_hr'];?>
                        </div>
                    </div>
                <?php } ?>
        </div>
    </div>
    <div class="row footer">
        <div class="center">
            <div class="contacts">
                <p>Adresar d.o.o.<br> Našička 20<br> 10000 Zagreb<br> +385 1 5812 462<br> info@adresar.net</p>
            </div>
            <div class="newsletter">
                <p>
                    <?php echo _NEWSLETTER_TXT;?><br>
                    <a href="<?php echo _SITE_URL._LNG.'/'._URL_NEWSLETTER_PRIJAVA?>" class="btn  newsletter-btn">Newsletter</a>
                </p>
              
            </div>
        </div>  
    </div>
    <?php 
    if(!$_COOKIE['cookieAlert'] == 'accepted'){
        echo '
        <div id="cookie" class="cookie-container">
            <div class="center">
                <p>'._COOK.' <a class="b" onclick="sjx(\'cookieAlert\',\'cookie\');" href="javascript:;">&nbsp;'._SLAZEM_SE_C.'</a></p>
            </div>
        </div>';
    }   
    ?>
    <style type="text/css">
    .cookie-container{position:fixed;bottom: 0;width: 100%;z-index:100000;display: block;left: 0;background: rgba(57,62,65,0.85);padding:20px;color:#fff;}.cookie-container p a{color:#7d7d7dff}.cookie-container p a{color:#ffffff}.cookie-container p{color:#fff;margin: 0;font-size: 13px;}.cookie-container p a.b{background: #7d7d7dff;display: inline-block;padding: 0 7px 0 4px;color: #fff;font-weight: 600;line-height: 17px;margin-left: 5px;}
    </style>
    <div class="row copy">
        <div class="center">
            <span class="aleft">
            ADRESAR nekretnine © <?php echo date('Y');?>. <?php echo _COPY;?>       
            </span>
            <span class="v_link">
                <a title="Adresar" href="https://adresar.net/" class="none">Adresar web</a> 
                <a target="_blank" href="https://adresar.net/" class="v" title="Adresar nekretnine"><img alt="Adresar nekretnine" src="images/virtus.svg"></a>
                <div class="clearfix"></div>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>  
    <script type="text/javascript">
    var siteLang = "<?php echo _LNG; ?>";
    var siteUrl = "<?php echo _SITE_URL; ?>";
    function loadScript(url, callback){
        var script = document.createElement("script")
        script.type = "text/javascript";
        script.async = true;
        if (script.readyState){ 
            script.onreadystatechange = function(){
                if (script.readyState == "loaded" ||
                        script.readyState == "complete"){
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {
            script.onload = function(){
                callback();
            };
        }
        script.src = url;
        document.body.appendChild(script);
    }
    loadScript(siteUrl+"js/jquery.min.js",
    function(){
        loadScript(siteUrl+"js/functions.js",function(){});
        loadScript(siteUrl+"js/sajax.js",function(){});
        loadScript(siteUrl+"js/respond.js",function(){});

        setTimeout(function(){
            $('body').removeClass("not-ready");
        }, 1);

        if (navigator.appName == 'Microsoft Internet Explorer' ||  !!(navigator.userAgent.match(/Trident/) || navigator.userAgent.match(/rv 11/)) || $.browser.msie == 1)
        {
            $('body').addClass('ie');
            (function(d) {
            var config = {
              kitId: 'ghk2uka',
              scriptTimeout: 3000,
              async: true
            },
            h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
            })(document);
        }
        <?php if(_su1 == '_su1') { // visina slidera ?>
            var viewportWidth = $(window).width();
    if (viewportWidth > 1079.99) {
            function resizeDiv(){vpw=$(window).width(),vph=$(window).height(),$(".slider-bg-frame li").css({height:vph+"px"}),$(".description-frame .center").css({height:vph+"px"}),$(".slider-bg-frame .bx-viewport").css({height:vph+"px"}),$(".slider-bg-frame").css({height:vph+"px"})}
            $(document).ready(function(){resizeDiv()}),
            window.onresize=function(a){resizeDiv()};
        }
        <?php }

        if(_su1 == _URL_KONTAKT || _su1 == _URL_UNOS_KOMENTARA || _su1 == _URL_NEWSLETTER_PRIJAVA){
        if($SEND_EMAIL['action']){?>
            $(document).ready(function(){
            $('html, body').animate({
                scrollTop: $(".kontakt-forma").offset().top - 180
            }, 'slow');
             });
        <?php 
            }else{
            if(isset($_POST['posalji_upit'])){?>
                $('html, body').animate({
                    scrollTop: $(".error").offset().top - 180
                }, 'slow');
            <?php }
            }
            }  
        if(_su1 == _URL_DETALJI){
        if($SEND_EMAIL['action']){?>
            $(document).ready(function(){
            $('.hidden-form').slideToggle();
            $('html, body').animate({
                scrollTop: $(".hidden-form").offset().top - 180
            }, 'slow');
             });
        <?php 
            }else{
            if(isset($_POST['posalji_upit'])){?>
                $('.hidden-form').show();
                $('html, body').animate({
                    scrollTop: $(".error").offset().top - 180
                }, 'slow');
            <?php }
            }
            }  
        ?>



    });

    </script>
    <div class="clearfix"></div>
    </body>
</html>