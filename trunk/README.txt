


echo elgg_view("fivestar/fivestar", array(
    'entity' => $vars['entity'],
    'min' => true,
    'subclass' => 'fivestar_subclass'
    'outerId' => 'fivestar_rating_list'
    'ratingTextClass' => 'fivestar_rating_text'
));
