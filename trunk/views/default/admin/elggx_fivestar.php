<?php

	global $CONFIG;
	
	$tab = $vars['tab'];
	
	// set securitytoken
	echo elgg_view("input/securitytoken");
	
	switch($tab) {
		case 'settings':
			$settingsselect = 'class="selected"';
			break;
	}
	
?>
<div class="contentWrapper">
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<li <?php echo $settingsselect; ?>><a href="<?php echo $CONFIG->wwwroot . 'mod/elggx_fivestar/admin.php?tab=settings'; ?>"><?php echo elgg_echo('elggx_fivestar:settings'); ?></a></li>
		</ul>
	</div>
<?php
	switch($tab) {
		case 'settings':
			echo elgg_view("elggx_fivestar/settings");
			break;
	}
?>
</div>
