 /*!
 * jQuery UI Stars v2.0.3
 * http://plugins.jquery.com/project/Star_Rating_widget
 *
 * Copyright (c) 2009 Orkan (orkans@gmail.com)
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
 *
 * $Rev: 102 $
 * $Date:: 2009-06-07 #$
 * $Build: 23 (2009-06-07)
 *
 * Theme: Crystal
 *
 */
.ui-stars-star,
.ui-stars-cancel {
	float: left;
	display: block;
	overflow: hidden;
	text-indent: -999em;
	cursor: pointer;
}
.ui-stars-star a,
.ui-stars-cancel a {
	width: 28px;
	height: 26px;
	display: block;
	position: relative;
	background: url('<?php echo $vars['url']; ?>mod/elggx_fivestar/_graphics/crystal-stars.png') no-repeat 0 0;
}
.ui-stars-star a {
	background-position: 0 -56px;
}
.ui-stars-star-on a {
	background-position: 0 -84px;
}
.ui-stars-star-hover a {
	background-position: 0 -112px;
}
.ui-stars-cancel-hover a {
	background-position: 0 -28px;
}
.ui-stars-star-disabled,
.ui-stars-star-disabled a,
.ui-stars-cancel-disabled a {
	cursor: default !important;
}

.fivestar-messages {
    margin-left: 1em;
    float: left;
    line-height: 15px;
    color: #fd1c24
}

