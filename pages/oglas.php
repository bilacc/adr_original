<?php 
	$current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


	$is_link = Db::query_row('SELECT * FROM items WHERE redirect_url = "'.$current_url.'"');
	if($is_link){
		$cat = Db::query_row("SELECT id,title_"._LNG." FROM categories WHERE id =".$is_link['categories_id']);
		$redirect_url = _SITE_URL._LNG.'/'._URL_DETALJI.'/'.clean_uri($cat['title_'._LNG]).'/'.clean_uri($is_link['title_'._LNG]).'-'.$is_link['id'];
		header('Location:'.$redirect_url);
		exit;
	}else{
		header('Location:'._SITE_URL);
		exit;
	}
?>