<?php

    if (!function_exists('str_get_html')) {
        include dirname(__FILE__) . "/lib/simple_html_dom.php";
    }

    function elggx_fivestar_init() {

        elggx_fivestar_settings();

        $style = get_plugin_setting('style');

        if ($style == 'basic') {
            extend_view('css', 'elggx_fivestar/basic');
        }

        extend_view('metatags','elggx_fivestar/metatags');

        register_plugin_hook('display', 'view', 'elggx_fivestar_view');
    }

    /**
     * This method is called when the view plugin hook is triggered. 
     * If a matching view config is found then the fivestar widget is
     * called.
     * 
     * @param  integer  $hook The hook being called. 
     * @param  integer  $type The type of entity you're being called on. 
     * @param  string   $return The return value.
     * @param  array    $params An array of parameters for the current view
     * @return string   The html
     */
    function elggx_fivestar_view($hook, $entity_type, $returnvalue, $params) {

        global $is_admin;

        $lines = explode("\n", get_plugin_setting('view'));
        foreach ($lines as $line) {
            $options = array();
            $parms = explode(",", $line);
            foreach ($parms as $parameter) {
                preg_match("/^(\S+)=(.*)$/", trim($parameter), $match);
                $options[$match[1]] = $match[2];
            }

            if ($options['view'] == $params['view']) {
                list($status, $html) = elggx_fivestar_widget($returnvalue, $params, $options);
                if (!$status) {
                    continue;
                } else {
                    return($html);
                }
            }
        }
    }

    /**
     * Handles voting on an entity
     * 
     * @param  integer  $guid  The entity guid being voted on
     * @param  integer  $vote The vote
     * @return string   A status message to be returned to the client
     */
    function elggx_fivestar_vote($guid, $vote) {

        $entity = get_entity($guid);

        $msg = null;

        if ($annotation = get_annotations($entity->guid, $entity->type, $entity->subtype, 'fivestar', '', $_SESSION['user']->guid, 1)) {
            if ($vote == 0 && (int)get_plugin_setting('change_cancel')) {
                if (!trigger_plugin_hook('elggx_fivestar:cancel', 'all', array('entity' => $entity), false)) {
                    delete_annotation($annotation[0]->id);
                    $msg = elgg_echo('elggx_fivestar:deleted');
                }
            } else if (get_plugin_setting('change_cancel')) {
                update_annotation($annotation[0]->id, 'fivestar', $vote, 'integer', $_SESSION['user']->guid, 2);
                $msg = elgg_echo('elggx_fivestar:updated');
            } else {
                $msg = elgg_echo('elggx_fivestar:nodups');
            }
        } else if ($vote > 0) {
            if (!trigger_plugin_hook('elggx_fivestar:vote', 'all', array('entity' => $entity), false)) {
                $entity->annotate('fivestar', $vote, 2);
            }
        } else {
            $msg = elgg_echo('elggx_fivestar:novote');
        }

        elggx_fivestar_setRating($entity);

        return($msg);
    }

    /**
     * Set thecurrent rating for an entity
     * 
     * @param  object   $entity  The entity to set the rating on
     * @return array    Includes the current rating and number of votes
     */
    function elggx_fivestar_setRating($entity) {

        elggx_fivestar_su();

        $rating = elggx_fivestar_getRating($entity->guid);
        
        $entity->elggx_fivestar_rating = $rating['rating'];

        elggx_fivestar_su(true);
        
        return;
    }



    /**
     * Get an the current rating for an entity
     * 
     * @param  integer  $guid  The entity guid being voted on
     * @return array    Includes the current rating and number of votes
     */
    function elggx_fivestar_getRating($guid) {

        $rating = array('rating' => 0, 'votes' => 0);
        $entity = get_entity($guid);

        if (count($entity->getAnnotations('fivestar', 9999))) {
            $rating['rating'] = $entity->getAnnotationsAvg('fivestar');
            $rating['votes'] = count($entity->getAnnotations('fivestar', 9999));

            $modifier = 100 / (int)get_plugin_setting('stars');
            $rating['rating'] = round($rating['rating'] / $modifier, 1);
        }

        return($rating);
    }

    /**
     * Inserts the fivestar widget into the current view
     * 
     * @param  string   $returnvalue  The original html
     * @param  array    $params  An array of parameters for the current view
     * @param  array    $guid  The fivestar view configuration
     * @return string   The original view or the view with the fivestar widget inserted
     */
    function elggx_fivestar_widget($returnvalue, $params, $options) {

        $guid = $params['vars']['entity']->guid;

        if (!$guid) { return; }

        $widget = elgg_view("elggx_fivestar/elggx_fivestar", array(
            'fivestar_guid' => $guid
        ));

        // get the DOM
        $html = str_get_html($returnvalue);

        $match = 0;
        foreach ($html->find($options['tag']) as $element) {
            if ($element->$options['attribute'] == $options['attribute_value']) {
                $element->innertext .= $options['before_html'] . $widget . $options['after_html'];
                $match = 1;
                break;
            }
        }

        $returnvalue = $html;
        return(array($match, $returnvalue));
    }

    /**
     * Checks to see if the current user has already voted on the entity
     * 
     * @param  guid   The entity guid
     * @return bool   Returns true/false
     */
    function elggx_fivestar_hasVoted($guid) {

        $entity = get_entity($guid);

        $annotation = get_annotations($entity->guid, $entity->type, $entity->subtype, 'fivestar', '', $_SESSION['user']->guid, 1);

        if (is_object($annotation[0])) {
            return(true);
        }

        return(false);
    }

    /**
     * Elevate user to admin.
     *
     * @param  bool $unsu  Return to original permissions 
     * @return bool  is_admin true/false
     */
    function elggx_fivestar_su($unsu=false) {
        global $is_admin;
        static $is_admin_orig = null;

        if (is_null($is_admin_orig)) {
            $is_admin_orig = $is_admin;
        }

        if ($unsu) {
            return $is_admin = $is_admin_orig;
        } else {
            return $is_admin = true;
        }
    }

    /**
     * Set default settings
     * 
     */
    function elggx_fivestar_settings() {

        // Set plugin defaults

        if (!(int)get_plugin_setting('stars')) {
            set_plugin_setting('stars', '5');
        }

        $change_vote = (int)get_plugin_setting('change_vote');
        if ($change_vote == 0) {
            set_plugin_setting('change_cancel', 0);
        } else {
            set_plugin_setting('change_cancel', 1);
        }

        if (!get_plugin_setting('style')) {
            set_plugin_setting('style', 'basic');
        }

    }

    function elggx_fivestar_defaults() {

        $view = 'view=object/blog, tag=div, attribute=class, attribute_value=contentWrapper singleview, before_html=<br />
view=object/image, tag=div, attribute=id, attribute_value=tidypics_wrapper
view=object/groupforumtopic, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/poll, tag=div, attribute=class, attribute_value=contentWrapper singleview, before_html=<br /><br />
view=poll/listing, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/page_top, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=pages/pageprofile, tag=div, attribute=class, attribute_value=contentWrapper, before_html=<br />
view=object/file, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/file, tag=div, attribute=class, attribute_value=filerepo_controls';

        set_plugin_setting('view', $view);
    }

    register_elgg_event_handler('init','system','elggx_fivestar_init');
    register_action("elggx_fivestar/rate", false, $CONFIG->pluginspath . "elggx_fivestar/actions/rate.php");
    register_action("elggx_fivestar/settings", false, $CONFIG->pluginspath . "elggx_fivestar/actions/settings.php");
    register_action("elggx_fivestar/reset", false, $CONFIG->pluginspath . "elggx_fivestar/actions/reset.php", true);

?>
