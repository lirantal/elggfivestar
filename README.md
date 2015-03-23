
# Fivestar plugin for Elgg
Modified and fixed to support Elgg 1.7 by Liran Tal <liran.tal@gmail.com>

# Features

jQuery rollover effects and AJAX no-reload voting.
Graceful degradation to an HTML rating form when JavaScript? is turned off.
Configurable options to allow users to cancel or change their votes.
Insert the fivestar widget into any view via configuration options (requires an understanding of Elgg views and html) or by manually inserting the view code.


# How to Use

echo elgg_view("fivestar/fivestar", array(
    'entity' => $vars['entity'],
    'min' => true,
    'subclass' => 'fivestar_subclass'
    'outerId' => 'fivestar_rating_list'
    'ratingTextClass' => 'fivestar_rating_text'
));


* This Github project was automatically migrated from Google Code https://code.google.com/p/elggxuserpoints/
