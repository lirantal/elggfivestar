<?php

	/**
	* Fivestar settings form
	*/

    $ts = time ();
    $token = generate_action_token ( $ts );
	$tokenRequest = "&__elgg_token=$token&__elgg_ts=$ts";

?>

<style>
    fieldset, fieldset.fivestar-collapsible {
        padding: 10px;
        border: 1px solid black;
        border-bottom-width: 1px;
        border-left-width: 1px;
        border-right-width: 1px;
        margin-bottom: 1em;
    }
    fieldset.fivestar-collapsed {
        border-bottom-width: 0;
        border-left-width: 0;
        border-right-width: 0;
        margin-bottom: 0;
        margin-left: 3px;
    }
    legend.fivestar-collapsible {
        color: blue;
    }
    legend.fivestar-collapsed {
        color:green;
    }
</style>

<script type="text/javascript">
    function addFormField() {
        var id = document.getElementById("id").value;
        $("#divTxt").append("<p id='row" + id + "'><input class='input-text' type='text' name='views[]' id='txt" + id + "'>&nbsp;&nbsp<a href='#' onClick='removeFormField(\"#row" + id + "\"); return false;'>Remove</a><p>");


        $('#row' + id).highlightFade({
            speed:1000
        });

        id = (id - 1) + 2;
        document.getElementById("id").value = id;
    }

    function removeFormField(id) {
        $(id).remove();
    }

    $.fn.collapse = function() {
        return this.each(function() {
            $(this).find("legend").addClass('fivestar-collapsible').click(function() {
                if ($(this).parent().hasClass('fivestar-collapsed'))
                    $(this).parent().removeClass('fivestar-collapsed').addClass('fivestar-collapsible');
    
                $(this).removeClass('fivestar-collapsed');
    
                $(this).parent().children().filter("p,img,table,ul,div,span,h1,h2,h3,h4,h5").toggle("slow", function() {
             
                    if ($(this).is(":visible"))
                        $(this).parent().find("legend").addClass('fivestar-collapsible');
                    else
                        $(this).parent().addClass('fivestar-collapsed').find("legend").addClass('fivestar-collapsed');
                });
            });
        });
    };

    $(document).ready(function() {
        $("fieldset.fivestar-collapsible").collapse();
    });

</script>


<?php

    $plugin = find_plugin_settings('elggx_fivestar');

    $form = "<br />" . elgg_echo('elggx_fivestar:numstars');
    $form .= elgg_view('input/pulldown', array(
                    'internalname' => 'params[stars]',
                    'options_values' => array(
                        '2'  => '2',
                        '3'  => '3',
                        '4'  => '4',
                        '5'  => '5',
                        '6'  => '6',
                        '7'  => '7',
                        '8'  => '8',
                        '9'  => '9',
                        '10' => '10'
                    ),
                    'value' => $plugin->stars
    ));
    $form .= "<br><br>";

    
    $form .= '<p class="admin_debug">';
    $form .= elgg_view("input/checkboxes", array(
        'options' => array(elgg_echo('elggx_fivestar:settings:change_cancel') => 1), 
        'internalname' => 'change_vote', 
        'value' => $plugin->change_vote )
    ); 
    $form .= "</p>";

    $form .= '<p class="admin_debug">';
    $form .= elgg_view("input/checkboxes", array(
        'options' => array(elgg_echo('elggx_fivestar:settings:legacy') => 1), 
        'internalname' => 'legacy', 
        'value' => $plugin->legacy )
    ); 
    $form .= "</p>";

    $form .= '<br /><h4>'.elgg_echo('elggx_fivestar:settings:view_heading').':</h4><br />';
    $form .= "<p><b>";
    $form .= elgg_view("output/confirmlink", array(
        'href' => $vars['url'] . "action/elggx_fivestar/reset?&__elgg_token=$token&__elgg_ts=$ts",
        'text' => elgg_echo('elggx_fivestar:settings:defaults'),
        'confirm' => elgg_echo('elggx_fivestar:settings:defaults:confirm')
    ));
    $form .= "</b></p><br />";

    $x = 1;
    $lines = explode("\n", get_plugin_setting('view', 'elggx_fivestar'));
    foreach ($lines as $line) {

        $options = array();
        $parms = explode(",", $line);
        foreach ($parms as $parameter) {
            preg_match("/^(\S+)=(.*)$/", trim($parameter), $match);
            $options[$match[1]] = $match[2];
        }

        $form .= '<fieldset id="row'.$x.'" class="fivestar-collapsible fivestar-collapsed">';
        $form .= '<legend id="row'.$x.'" class="fivestar-collapsible fivestar-collapsed" onmouseover="this.style.cursor=\'pointer\';"> '.$options['view'].' </legend>';

        $form .= '<p id="row'.$x.'" style="background-color: transparent; display: none;">';
        $form .= '<input id="txt'.$x.'" class="input-text" type="text" name="views[]" value="'.$line.'" />';
        $form .= '<a onclick=\'removeFormField("#row'.$x.'"); return false;\' href="#">Remove</a></p>';

        $form .= '</fieldset>';

        $x++;
    }


    $form .= '<input type="hidden" id="id" value="'.$x.'">';
    $form .= '<div id="divTxt"></div>';


    $form .= elgg_view("input/securitytoken");
    $form .= '<p><a onmouseover="this.style.cursor=\'pointer\';" onClick="addFormField(); return false;"><b>'.elgg_echo('elggx_fivestar:settings:add_view').'</b></a></p>';


    $form .= "<br><br>" . elgg_view('input/submit', array('value' => elgg_echo("save")));

	$action = $vars['url'] . 'action/elggx_fivestar/settings';
	echo elgg_view('input/form', array('action' => $action, 'body' => $form));

?>



