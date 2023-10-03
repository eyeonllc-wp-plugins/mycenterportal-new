<?php

// print array and variables for debugging 
function mcp_debug($item = array(), $die = true, $display = true) {
	if( is_array($item) || is_object($item) ) {
		echo '<pre style="padding-left:180px;'.($display?'':'display:none').'">'; print_r($item); echo '</pre>';
	} else {
		echo '<div style="padding-left:180px;'.($display?'':'display:none').'">'.$item.'</div>';
	}
	
	if( $die ) {
		die();
	}
}

function mcp_getScriptOutput($path, $shortcode_atts = array(), $print = false) {
	ob_start();
	$mcd_settings = get_option(MCD_REDUX_OPT_NAME);

	if( is_readable($path) && $path ) {
		include $path;
	} else {
		return false;
	}

	if( $print == false ) {
		return ob_get_clean();
	} else {
		echo ob_get_clean();
	}
}

// get URL version by file last updated timestamp
function mcd_get_version($url) {
	$file = MCD_PLUGIN_PATH.$url;
	$version = is_file($file) ? filemtime($file) : time();
	return $version;
}

// return a url with url_version
function mcd_version_url($url, $return = false) {
	$version_url = MCD_PLUGIN_URL.$url.'?v='.mcd_get_version($url);
	if( $return ) return $version_url;
	echo $version_url;
}

function mcd_image_url($url = '') {
	if( is_file(MCD_PLUGIN_PATH.$url) ) {
		return mcd_version_url($url, true);
	}
	return MCD_PLUGIN_URL.'assets/img/blank.gif';
}

function mcd_api_data($url) {
  global $mcd_settings;
	$url .= (strpos($url, '?')?'&':'?').'time='.time();
	$args = array(
		'sslverify' => false,
    'headers' => array(
      'center_id' => $mcd_settings['center_id'],
    ),
	);
	$req = wp_remote_get( $url, $args );
	$body = wp_remote_retrieve_body( $req );
	$data = json_decode( $body, true );
	return $data;
}

function mcd_get_file_content($file_path) {
	$output = '';
	$handle = @fopen($file_path, "r");
	if ($handle) {
		while (($buffer = fgets($handle, 4096)) !== false) {
			$output .= $buffer;
		}
		fclose($handle);
	}
	return $output;
}

function mcd_single_page_url($var) {
	$mcd_settings = get_option(MCD_REDUX_OPT_NAME);
	$url = get_site_url().'/';
	if( $var == 'mycenterdeal' ) {
		$url .= $mcd_settings['deals_single_page_slug'];
	} elseif( $var == 'mycenterstore' ) {
		$url .= $mcd_settings['stores_single_page_slug'];
	} elseif( $var == 'mycenterevent' ) {
		$url .= $mcd_settings['events_single_page_slug'];
	} elseif( $var == 'mycentercareer' ) {
		$url .= $mcd_settings['careers_single_page_slug'];
	} elseif( $var == 'mycenterblogpost' ) {
		$url .= $mcd_settings['blog_single_page_slug'];
	}
	$url .= '/';
	return $url;
}

function get_current_url() {
	return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function make_excerpt($post_content) {
	$text = strip_shortcodes( $post_content );
	$text = strip_tags( $text );
	// $text = apply_filters( 'the_content', $text );
	$text = str_replace(']]>', ']]&gt;', $text);
	$excerpt_length = apply_filters( 'excerpt_length', 20 );
	$excerpt_more = apply_filters( 'excerpt_more', ' ' . ' &hellip;' );
	$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	return $text;
}

function mcd_likes_number_format($number) {
	$output = $number;
	if( $number/1000 >= 1 ) {
		$number = $number/1000;
		$round = ($number>=10 ? 0 : 1);
		$output = round($number, $round)."K";
	}
	if( $number/1000 >= 1 ) {
		$number = $number/1000;
		$round = ($number>=10 ? 0 : 1);
		$output = round($number, $round)."M";
	}
	if( $number/1000 >= 1 ) {
		$number = $number/1000;
		$round = ($number>=10 ? 0 : 1);
		$output = round($number, $round)."B";
	}
	return $output;
}

function mcd_include_js($name, $url, $in_footer = false) {
	wp_enqueue_script(
		'mcd-'.$name,
		MCD_PLUGIN_URL.$url,
		array('jquery'),
		filemtime( MCD_PLUGIN_PATH.$url ),
		$in_footer
	);
}

function mcd_include_css($name, $url, $in_footer = false) {
	wp_enqueue_style(
		'mcd-'.$name,
		MCD_PLUGIN_URL.$url,
		array(),
		filemtime( MCD_PLUGIN_PATH.$url )
	);
}

function mcd_search_result_types($default = false) {
	$all_types_default = array();
	$wp_types = array();

	$portal_types = array(
		'portal_stores' => 'Stores',
		'portal_deals' => 'Deals',
		'portal_events' => 'Events',
	);
	foreach ($portal_types as $key => $type) {
		$portal_types[$key] = $type.' - Portal';
		if( $default ) $all_types_default[$key] = true;
	}

	$args = array(
		'public' => true,
		'_builtin' => true,
	);
	$post_types = get_post_types($args, 'objects');
	foreach ($post_types as $key => $type) {
		$wp_types['wp_'.$key] = $type->label.' - Post type';
		if( $default ) $all_types_default['wp_'.$key] = false;
	}

	if( $default ) return $all_types_default;

	$all_types = array_merge($portal_types, $wp_types);
	return $all_types;
}

function mcd_current_theme_name() {
	$theme_name = wp_get_theme()->get('Name');
	$theme_name = strtolower($theme_name);
	$theme_name = str_replace(' ', '-', $theme_name);
	return $theme_name;
}

function mcd_opening_hour_time($time) {
	$time = strtoupper(date('g:i a', strtotime($time)));
	return $time;
}

function getFriendlyURL($string, $separator='-') {
	$string = strtolower($string); // convert to lower case
	$string = preg_replace('/\'/', '', $string); // remove special chars
	$string = preg_replace('/’/', '', $string); // remove special chars
	$string = preg_replace('/[^a-z0-9\-]/', '-', $string); // remove special chars
	$string = preg_replace('/-+/', '-', $string); // replace multiple hyphens with one hyphen
	$string = trim($string, '-'); // trim hyphens
	return $string;
}

function load_404() {
	global $wp_query;
	$wp_query->set_404();
	status_header(404);
	get_template_part(404);
	exit();	
}

function mcp_page_title($title) {
	return $title.' - '.get_bloginfo('name');
}

