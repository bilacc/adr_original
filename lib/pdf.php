<?php
require_once('functions.php');
include('plugins/mpdf/mpdf.php'); 
$lng = 'hr';

  $item = Db::query_row('SELECT * FROM items WHERE id = '.$_GET['id'].'');  
  $cat = Db::query_row("SELECT id,title_".$lng." FROM categories WHERE id =".$item['categories_id']);
  $lokacija = Db::query_one("SELECT title_".$lng." FROM city WHERE id =".$item['city_id']);
  $namjestenost = Db::query_row("SELECT id,title_".$lng." FROM namjestenost WHERE id =".$item['namjestenost_id']);
  $grijanje = Db::query_row("SELECT id,title_".$lng." FROM grijanje WHERE id =".$item['heating_id']);
  $agent = Db::query_row("SELECT id,title_".$lng.",tel,mail FROM our_team WHERE id =".$item['agent_id']);
  $prva_slika = Db::query_row('SELECT id,photo_name FROM site_photos WHERE table_name = "items" AND table_id = '.$item['id'].' AND tlocrt="ne" ORDER BY orderby ASC LIMIT 1');
  $slike = Db::query('SELECT * FROM site_photos WHERE table_name = "items" AND table_id = '.$item['id'].' AND table_id != '.$prva_slika['id'].' ORDER BY orderby ASC LIMIT 4');


      // FAVORITII
  $is_favorit = Db::query_one('SELECT id FROM favoriti WHERE cookie="'.$_COOKIE[_STORE_COOKIE_NAME].'" AND items_id='.$item['id']);
    if($is_favorit){
        $wish_txt = _DODANO_U_FAVORITE;
        $class=' added';
    }else{
        $wish_txt = _DODAJ_U_FAVORITE;
        $class=' ';
    }
  $function = "sjx('favorits', ".$item['id'].",'items','".$lng."', 1);return false";
  $title = $item['title_'.$lng];
  
  if($cat['title_'.$lng] && $lokacija){
      $naslov = $cat['title_'.$lng].', '.$lokacija;
  }else{
      $naslov = $item['title_'.$lng];
  }


$html = '<body>
  <div class="row">
      <div class="center">
          <h1 class="main-title">'.$item['title_'.$lng].'';
          if($item['address']!=''){
            $html .= '<div class="clearfix"></div><span class="title-sub">'.$item['address'].'</span>';
          }
          
          $html .='</h1><div class="w5">';
          if($lokacija){
            $html .= '<span><strong>'.get_constant('_LOKACIJA', $lng).'</strong>: '.$lokacija.'</span><div class="clearfix line"></div>';
          }
          if($cat['title_'.$lng]){
            $html .= '<span><strong>'.get_constant('_TIP_NEKRETNINE', $lng).'</strong>: '.$cat['title_'.$lng].'</span><div class="clearfix line"></div>';
          }
          if($item['quadrature1']!=''){
            $html .= '<span><strong>'.get_constant('_POVRSINA', $lng).'</strong>: '.$item['quadrature1'].'</span><div class="clearfix line"></div>';
          }
    if($item['floor']!=''){
            $html .= '<span><strong>'.get_constant('_KATNOST', $lng).'</strong>: '.$item['floor'].'</span><div class="clearfix line"></div>';
          }
          if($item['rooms']!=''){
            $html .= '<span><strong>'.get_constant('_BROJ_SOBA', $lng).'</strong>: '.$item['rooms'].'</span><div class="clearfix line"></div>';
          }
          if($item['bathrooms']!=''){
            $html .= '<span><strong>'.get_constant('_BROJ_KUPAONICA', $lng).'</strong>: '.$item['bathrooms'].'</span><div class="clearfix line"></div>';
          }
          if($namjestenost['title_'.$lng]){
            $html .= '<span><strong>'.get_constant('_NAMJESTENOST', $lng).'</strong>: '.$namjestenost['title_'.$lng].'</span><div class="clearfix line"></div>';
          }
          if($grijanje['title_'.$lng]){
            $html .= '<span><strong>'.get_constant('_GRIJANJE', $lng).'</strong>: '.$grijanje['title_'.$lng].'</span><div class="clearfix line"></div>';
          }
          if($item['year_built']!=''){
            $html .= '<span><strong>'.get_constant('_GODINA_IZGRADNJE', $lng).'</strong>: '.$item['year_built'].'</span><div class="clearfix line"></div>';
          }
           if($item['adaptacija']!=''){
            $html .= '<span><strong>'.get_constant('_GODINA_ADAPTACIJE', $lng).'</strong>: '.$item['adaptacija'].'</span><div class="clearfix line"></div>';
          }
          if($item['energy_cert']!=''){
            $html .= '<span><strong>'.get_constant('_ENERGETSKI_CERTIFIKAT', $lng).'</strong>: '.$item['energy_cert'].'</span><div class="clearfix line"></div>';
          }


          $html .='</div>
          <div class="w5 aright"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/'.$prva_slika['photo_name'].'&w=970&h=640&zc=1" /></div>
          <div class="opis">'.$item['text_'.$lng].'</div><div class="line clearfix"></div>';
// if($slike){
//       foreach($slike as $red){    
//         $html .='<td style="width:24%;margin-right:1%;"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/'.$red['photo_name'].'&w=400&h=300&zc=1" /></td>';
       
//       }
                         
// }

        if($agent){
            $slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "our_team" AND table_id = '.$agent['id'].' ORDER BY orderby ASC LIMIT 1');
            $slika = ($slika) ? 'upload_data/site_photos/'.$slika : 'images/default.jpg';
            $html .='<table><tbody>
                        <tr>
                        <td>
                            <img style="width:145px; margin-right:25px;" src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.$slika.'&w=145&h=145&zc=1" />
                        </td>
                        <td style="vertical-align:top;">
                          <h4>'.get_constant('_AGENT', $lng).': </h4>
                          <h3>'.$agent['title_hr'].'</strong></h3>
                          <span class="mail">
                              '.$agent['mail'].'
                          </span><br>
                          <span class="tel">
                              '.$agent['tel'].'
                          </span>
                        </td>
                        </tr>
                        </tbody>
                    </table>';
        } 
      $html .='</div>
  </div>
</body>
';





// var_dump($agent);exit;
$mpdf=new mPDF('','', 0, '', 5, 5, '', '', '', '', '');
$stylesheet = file_get_contents('../css/pdf_css.css');

$mpdfObj = new mPDF('', '', 'relvej');
$mpdfObj->SetFont('relvej');

$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html,2);
ob_end_clean();
$mpdf->Output('Nekretnina.pdf', 'D');

exit;
?>


