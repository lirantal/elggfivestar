<?php

	/**
	 * Save Userpoints settings
	 * 
	 */

	global $CONFIG;

	gatekeeper();
	action_gatekeeper();


	// Params array (text boxes and drop downs)
	$params = get_input('params');
	foreach ($params as $k => $v) {
		if (!set_plugin_setting($k, $v, 'elggx_fivestar')) {
			register_error(sprintf(elgg_echo('plugins:settings:save:fail'), 'elggx_fivestar'));
			forward($_SERVER['HTTP_REFERER']);
		}
	}

    if (is_array(get_input('change_vote'))) {
        set_plugin_setting('change_vote', 1, 'elggx_fivestar');
    } else {
        set_plugin_setting('change_vote', 0, 'elggx_fivestar');
    }

    if (is_array(get_input('legacy'))) {
        set_plugin_setting('legacy', 1, 'elggx_fivestar');
    } else {
        set_plugin_setting('legacy', 0, 'elggx_fivestar');
    }


    $view = '';

    foreach ($_POST['views'] as $value) {
        $view .= $value . "\n";
    }

    set_plugin_setting('view', $view, 'elggx_fivestar');
    //set_plugin_setting('view', 0, 'elggx_fivestar');

	system_message(elgg_echo('elggx_fivestar:settings:save:ok'));
	
	forward($_SERVER['HTTP_REFERER']);
?>
