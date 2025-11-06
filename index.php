<?php
	require_once 'lib/functions.php';
	
	if( ! get_conf('production') )
		$page_stats = new PageStats;
	
	$page = new ShotPage;
	
	include 'include/'.$page->data['header'].'.php';
	print $page->data['content'];
	include 'include/'.$page->data['footer'].'.php';
	
	if( ! get_conf('production') )
		print $page_stats->output_result();
?>
