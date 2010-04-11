<?php

    $ts = time ();
    $token = generate_action_token ($ts);

    $guid = isset($vars['fivestar_guid']) ? $vars['fivestar_guid'] : $vars['entity']->guid;

    if (!$guid) { return; }

    $rating = elggx_fivestar_getRating($guid);
    $stars = (int)get_plugin_setting('stars', 'elggx_fivestar');

    $pps = 100 / $stars;

    $checked = '';

    $disabled = '';
    if (!isloggedin()) {
        $disabled = 'disabled="disabled"';
    }

    if (!(int)get_plugin_setting('change_cancel', 'elggx_fivestar')) {
        if (elggx_fivestar_hasVoted($guid)) {
            $disabled = 'disabled="disabled"';
        }
    }
    
    $subclass = $vars['subclass'] ? ' ' . $vars['subclass'] : '';
    $outerId = $vars['outerId'] ? 'id="' . $vars['outerId'] . '"' : '';
    $ratingText = $vars['ratingTextClass'] ? 'class="' . $vars['ratingTextClass'] . '"' : '';
?>

    <div <?php echo $outerId; ?> class="fivestar-ratings-<?php echo $guid . $subclass; ?>">
        <form id="fivestar-form-<?php echo $guid; ?>" style="width: 200px" action="<?php echo $vars['url']; ?>action/elggx_fivestar/rate" method="post">
            <?php for ($i = 1; $i <= $stars; $i++) { ?>
                <?php if (round($rating['rating']) == $i) { $checked = 'checked="checked"'; } ?>
                    <input type="radio" name="rate_avg" <?php echo $checked; ?> <?php echo $disabled; ?> value="<?php echo $pps * $i; ?>" />
                    <?php $checked = ''; ?>
            <?php } ?>
                <input type="hidden" name="id" value="<?php echo $guid; ?>" />
                <input type="hidden" name="__elgg_token" value="<?php echo $token; ?>" />
                <input type="hidden" name="__elgg_ts" value="<?php echo $ts; ?>" />
                <input type="submit" value="Rate it!" />
        </form>

        <?php if (!$vars['min']): ?>
            <br />
            <p <?php echo $ratingText; ?>>
                <span id="fivestar-rating-<?php echo $guid; ?>"><?php echo $rating['rating']; ?></span>/<?php echo $stars; ?> stars (<span id="fivestar-votes-<?php echo $guid; ?>"><?php echo $rating['votes']; ?></span> votes)
            </p>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
        jQuery(
            fivestar(<?php echo $guid; ?>, '<?php echo $token; ?>', '<?php echo $ts; ?>')
        );
    </script>



