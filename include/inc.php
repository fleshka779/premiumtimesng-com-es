<?php

add_filter('big_image_size_threshold', '__return_false');
// Allow SVG

add_filter('disable_wpseo_json_ld_search', '__return_true');

// убираем feed
function fb_disable_feed()
{
	wp_redirect(get_option('siteurl'));
}
add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'fb_disable_feed', 1);
add_action('do_feed_atom_comments', 'fb_disable_feed', 1);

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

	global $wp_version;
	if ($wp_version !== '4.7.1') {
		return $data;
	}

	$filetype = wp_check_filetype($filename, $mimes);

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];
}, 10, 4);

function sar_attachment_redirect()
{

	global $post;

	if (is_attachment()) {
		wp_redirect(home_url('/') . '404/');
		exit;
		/*
		status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        //die();
		//wp_redirect( home_url( '/' ).'404/' );
		exit;/**/
	}
}
add_action('template_redirect', 'sar_attachment_redirect', 1);

function itsme_disable_feed()
{
	//wp_die( __( 'No feed available, please visit the <a href="'. home_url( '/' ) .'">homepage</a>!' ) );
	//wp_404();
	//wp_redirect(get_option('siteurl')); 
	if (is_feed()) {
		wp_redirect(home_url('/') . '404/');
		exit;
	}
}

add_action('do_feed', 'itsme_disable_feed', 1);
add_action('do_feed_rdf', 'itsme_disable_feed', 1);
add_action('do_feed_rss', 'itsme_disable_feed', 1);
add_action('do_feed_rss2', 'itsme_disable_feed', 1);
add_action('do_feed_atom', 'itsme_disable_feed', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed', 1);




remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');




add_action('do_feed', 'itsme_disable_feed', 1);
add_action('do_feed_rdf', 'itsme_disable_feed', 1);
add_action('do_feed_rss', 'itsme_disable_feed', 1);
add_action('do_feed_rss2', 'itsme_disable_feed', 1);
add_action('do_feed_atom', 'itsme_disable_feed', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed', 1);


add_action('wp_print_styles', 'wps_deregister_styles2', 100, 0);

function wps_deregister_styles2()
{
	wp_dequeue_style('wpforms');
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('ess_load_css');
	wp_dequeue_style('ratings_scripts');
	wp_dequeue_style('wp-syntax-css');
	wp_dequeue_style('easy_social_share_buttons-frontend');
	wp_dequeue_style('wp-postratings');
	wp_dequeue_style('wpml-legacy-dropdown-0');
	//easy-social-share-buttons/assets/css/frontend.min
}
function my_deregister_scripts()
{
	wp_dequeue_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

add_filter('wpcf7_load_css', '__return_false');

function crunchify_stop_loading_wp_embed_and_jquery()
{
	if (!is_admin()) {
		wp_deregister_script('wp-embed');
		wp_deregister_script('jquery');
		//wp_register_script('jquery', '/assets/js/libs.min.js', false, null);
		//wp_enqueue_script('jquery');
	}
}
add_action('init', 'crunchify_stop_loading_wp_embed_and_jquery');

function disable_emojis()
{
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
add_action('init', 'disable_emojis');

function disable_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');

function disable_emojis1()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	//add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	//add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action('init', 'disable_emojis1');

remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);

function itsme_disable_feed2()
{
	wp_redirect(home_url('/404/'));
	exit();
}

add_action('do_feed', 'itsme_disable_feed2', 1);
add_action('do_feed_rdf', 'itsme_disable_feed2', 1);
add_action('do_feed_rss', 'itsme_disable_feed2', 1);
add_action('do_feed_rss2', 'itsme_disable_feed2', 1);
add_action('do_feed_atom', 'itsme_disable_feed2', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed2', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed2', 1);

remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');


remove_action('wp_head',                    'rest_output_link_wp_head', 10);
remove_action('template_redirect',          'rest_output_link_header', 11);
remove_action('auth_cookie_malformed',      'rest_cookie_collect_status');
remove_action('auth_cookie_expired',        'rest_cookie_collect_status');
remove_action('auth_cookie_bad_username',   'rest_cookie_collect_status');
remove_action('auth_cookie_bad_hash',       'rest_cookie_collect_status');
remove_action('auth_cookie_valid',          'rest_cookie_collect_status');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
// Удаляем информацию о REST API из заголовков HTTP и секции head
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'rest_output_link_header', 11);
//remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action('auth_cookie_malformed', 'rest_cookie_collect_status');
remove_action('auth_cookie_expired', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_username', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_hash', 'rest_cookie_collect_status');
remove_action('auth_cookie_valid', 'rest_cookie_collect_status');
remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);
// Отключаем события REST API
remove_action( 'init', 'rest_api_init' );
remove_action( 'rest_api_init', 'rest_api_default_filters', 10, 1 );
remove_action( 'parse_request', 'rest_api_loaded' );
// Отключаем Embeds связанные с REST API
remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 ); 
// Убираем oembed ссылки в секции head
remove_action('wp_head', 'wp_oembed_add_discovery_links');
// Если собираетесь выводить oembed из других сайтов на своём, то закомментируйте следующую строку
remove_action('wp_head', 'wp_oembed_add_host_js');

// Редиректим со страницы /wp-json/ на главную
add_action( 'template_redirect', function() {
   if ( preg_match( '#\/wp-json\/.*?#', $_SERVER['REQUEST_URI'] ) ) {
       wp_redirect( get_option( 'siteurl' ), 301 );
       die();
   }
} );


add_action( 'rss_head', '__return_false' );
add_action( 'rss2_head', '__return_false' );
add_action( 'commentsrss2_head', '__return_false' );

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');

remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('template_redirect', 'wp_shortlink_header', 11);
remove_action('wp_head', 'feed_links_extra', 3); // убирает ссылки на rss категорий
remove_action('wp_head', 'feed_links', 2); // минус ссылки на основной rss и комментарии
remove_action('wp_head', 'rsd_link');  // сервис Really Simple Discovery
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'wp_generator');  // скрыть версию wordpress
remove_action('wp_head', 'dns-prefetch');  // скрыть версию wordpress
remove_action('wp_head', 'wp_resource_hints', 2);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 10);
//add_filter( 'wpseo_next_rel_link', '__return_false' );
//add_filter( 'wpseo_prev_rel_link', '__return_false' );


add_filter('wpseo_separator_options', function ($separators) {
	return ['✘', '✗', '✖', '✔'];
}, 10, 1);


add_action('wp_print_styles', 'wps_deregister_styles', 100, 0);
function wps_deregister_styles()
{
	wp_dequeue_style('wpforms');
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('ess_load_css');
	wp_dequeue_style('ratings_scripts');
	wp_dequeue_style('wp-syntax-css');
	wp_dequeue_style('easy_social_share_buttons-frontend');
	wp_dequeue_style('wp-postratings');
	wp_dequeue_style('lwptoc-main');
	wp_dequeue_style('dgwt-jg-swipebox');
	wp_dequeue_style('wpsm-comptable-styles');
	//easy-social-share-buttons/assets/css/frontend.min
}
//add_filter( 'wpseo_json_ld_output', '__return_false' );
/**/
function my_deregister_styles_and_scripts()
{
	wp_dequeue_style('wp-block-library');
}
add_action('wp_print_styles', 'my_deregister_styles_and_scripts', 100);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

function disable_wp_emojicons()
{

	// all actions related to emojis
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');

	// filter to remove TinyMCE emojis
	add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
}
add_action('init', 'disable_wp_emojicons');

/* //отключение архивов по автору start
function wph_disable_author_archive($false)
{
	if (is_author()) { 
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		nocache_headers();
		return true;
	}
	return $false;
}
//удаление ссылки на архив автора
function wph_remove_author_link($content)
{
	return home_url();
} */

/* add_action('pre_handle_404', 'wph_disable_author_archive');
add_filter('author_link', 'wph_remove_author_link'); */
//отключение архивов по автору end

add_filter('emoji_svg_url', '__return_empty_string');

function wph_remove_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}
add_filter('tiny_mce_plugins', 'wph_remove_emojis_tinymce');


function my_scripts_method()
{
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('contact-form-7-css');
	wp_dequeue_style('wp-postratings-css');
	wp_deregister_script('jquery');
	wp_dequeue_style('wpsm-comptable-styles');
	//wp_deregister_script('lwptoc-main');
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme'); // WordPress core
	//  wp_dequeue_style( 'wc-block-style' ); // WooCommerce
	wp_dequeue_style('storefront-gutenberg-blocks');
}
add_action('wp_enqueue_scripts', 'my_scripts_method');

add_action('wp_print_scripts', 'de_script', 100);
function de_script()
{
	wp_dequeue_script('disqus_embed');
	wp_deregister_script('disqus_embed');
	wp_dequeue_script('disqus_count');
	wp_deregister_script('disqus_count');
}

function disable_emojicons_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}


add_theme_support( 'post-thumbnails' );

// Allow SVG
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

	global $wp_version;
	if ($wp_version !== '4.7.1') {
		return $data;
	}

	$filetype = wp_check_filetype($filename, $mimes);

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];
}, 10, 4);

function cc_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
// Allow SVG end

function fix_svg()
{
	echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action('admin_head', 'fix_svg');