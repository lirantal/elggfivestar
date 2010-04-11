<?php

	include_once dirname(dirname(dirname(__FILE__))) . "/engine/start.php";

	global $CONFIG;

	admin_gatekeeper();
	set_context('admin');
	set_page_owner($_SESSION['guid']);
	
	$tab = get_input('tab') ? get_input('tab') : 'settings';

	$body = elgg_view_title(elgg_echo('elggx_fivestar:admin'));
	
	$body .= elgg_view("admin/elggx_fivestar", array('tab' => $tab));
	
	page_draw(elgg_echo('elggx_fivestar:admin'), elgg_view_layout("two_column_left_sidebar", '', $body));

?>
