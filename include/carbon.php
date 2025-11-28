<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

require_once __DIR__ . '/carbon-fields/carbon-fields-plugin.php';

add_action('init', function () {
	define('PLAY', carbon_get_theme_option('play'));
	define('SLOT_PLAY', carbon_get_theme_option('slot_play'));
});

add_action('after_setup_theme', 'crb_load');
function crb_load()
{
	require_once('carbon-fields/vendor/autoload.php');
	\Carbon_Fields\Carbon_Fields::boot();
}

add_action('carbon_fields_register_fields', 'crb_attach_theme_options'); // Для версии 1.6 и ниже
function crb_attach_theme_options()
{
	Container::make('theme_options', __('Theme Options', 'crb'))
		->add_fields([
			Field::make('text', 'htmlang', 'htmlang')->set_width(25)->set_default_value('en'),

			Field::make('image', 'favicon', 'favicon')->set_value_type('url')->set_width(25),

			Field::make('image', 'logo', 'Logo')->set_value_type('url')->set_width(25),
			Field::make('text', 'logo_link', 'logo_link')->set_width(25),
			Field::make('text', 'logo_alt', 'logo_alt')->set_width(25),
			Field::make('text', 'logo_title', 'logo_title')->set_width(25),

			Field::make('image', 'logo_bottom', 'Logo Bottom')->set_value_type('url')->set_width(25),
			Field::make('text', 'logo_bottom_link', 'logo_bottom_link')->set_width(25),
			Field::make('text', 'logo_bottom_alt', 'logo_bottom_alt')->set_width(25),
			Field::make('text', 'logo_bottom_title', 'logo_bottom_title')->set_width(25),

			Field::make('text', 'soc_fb', 'soc_fb')->set_width(25),
			Field::make('text', 'soc_yt', 'soc_yt')->set_width(25),
			Field::make('text', 'soc_ln', 'soc_ln')->set_width(25),
			Field::make('text', 'soc_tw', 'soc_tw')->set_width(25),
			Field::make('text', 'soc_in', 'soc_in')->set_width(25),

			Field::make('text', 'footer_copywright', 'footer_copywright')->set_width(50)->set_default_value('Copyright © 2025 The Premium Times'),
			Field::make('text', 'footer_address', 'footer_address')->set_width(50)->set_default_value('Pääkonttori: 2 Vanguard Avenue, Kirikiri Canal, Apapa Lagos, P.M.B. 1007 Apapa Lagos'),
			Field::make('text', 'footer_tel', 'footer_tel')->set_width(25)->set_default_value('2348135998444'),
			Field::make('text', 'footer_tel_text', 'footer_tel_text')->set_width(25)->set_default_value('Tel. +234 8135998444'),
			Field::make('text', 'footer_email', 'footer_email')->set_width(25)->set_default_value('info@premiumtimes.com'),
			Field::make('text', 'footer_heading', 'footer_heading')->set_width(50)->set_default_value('Hyödyllisiä sivuja kansainvälisille matkailijoille'),
			Field::make('text', 'footer_heading_2', 'footer_heading_2')->set_width(50)->set_default_value('Hyödyllisiä sivuja kansainvälisille matkailijoille'),

			Field::make('text', 'table_head_logo', 'table_head_logo')->set_width(20)->set_default_value('Kasinot'),
			Field::make('text', 'table_head_bonus', 'table_head_bonus')->set_width(20)->set_default_value('Bonus'),
			Field::make('text', 'table_head_feature', 'table_head_feature')->set_width(20)->set_default_value('Pääominaisuudet'),
			Field::make('text', 'table_head_rating', 'table_head_rating')->set_width(20)->set_default_value('Luokitus'),
			Field::make('text', 'table_head_play', 'table_head_play')->set_width(20)->set_default_value('Turvallinen linkki'),
			Field::make('text', 'top_banner_text', 'top_banner_text')->set_width(50)->set_default_value('Eksklusiivinen bonus'),
			Field::make('text', 'play', 'play')->set_width(20)->set_default_value('Pelaa nyt'),
			Field::make('text', 'reading_time', 'reading_time')->set_width(20)->set_default_value('Lukuaika:'),
			Field::make('text', 'last_update', 'last_update')->set_width(20)->set_default_value('Viimeksi päivitetty:'),
			Field::make('text', 'by', 'by')->set_width(20)->set_default_value('Tekijä:'),
			Field::make('text', 'casino_update', 'casino_update')->set_width(20)->set_default_value('Päivitetty:'),

			Field::make('text', 'related_article', 'related_article')->set_default_value('Aiheeseen liittyvät artikkelit')->set_width(25),
			Field::make('text', 'footer_alt', 'footer_alt')->set_default_value('Premiumtimesng')->set_width(25),

			Field::make("checkbox", "show_banner_3", "show_banner_3")->set_option_value('yes')->set_width(25),

			Field::make('text', 'banner_title', 'banner_title')->set_default_value('<span class="heading-span">Top Bonus</span> Del Messe')->set_width(50),
			Field::make('text', 'banner_bonus_type', 'banner_bonus_type')->set_default_value('')->set_width(25),

			Field::make('text', 'subscribe_title', 'subscribe_title')->set_width(25)->set_default_value('Subscribe to the newsletter to receive current offers:'),
			Field::make('text', 'subscribe', 'subscribe')->set_width(25)->set_default_value('Subscribe'),

			Field::make('complex', 'banner_casinos', 'banner_casinos')
				->add_fields(array(
					Field::make('text', 'id', 'id')->set_width(50),
				)),


			// Field::make('image', 'crb_logo', 'Logo')->set_value_type('url')->set_width(25),
			// Field::make('image', 'crb_logo_bottom', 'Logo Bottom')->set_value_type('url')->set_width(25),	
			// Field::make('image', 'crb_favicon', 'favicon')->set_value_type('url')->set_width(25),	
			// Field::make('rich_text', 'crb_footer_text', 'Footer Text'),
			// Field::make('text', 'crb_footer', 'Footer')->set_width(25),
			// Field::make('text', 'table_of_content', 'table_of_content')->set_width(25)->set_default_value('Inhaltsverzeichnis'),
			// Field::make('text', 'play', 'play')->set_width(25)->set_default_value('Besuchen'),
			// Field::make('text', 'slot_play', 'slot_play')->set_width(25)->set_default_value('Demo Spiel'),
			Field::make('complex', 'payments', 'payments')
				->add_fields(array(
					Field::make('text', 'name', 'name')->set_width(50),
					Field::make('image', 'img', 'img')->set_width(50)->set_value_type('url'),
				)),
			Field::make('complex', 'footer_secure', 'footer_secure')
				->add_fields(array(
					Field::make('image', 'img', 'img')->set_width(25),
					Field::make('text', 'alt', 'alt')->set_width(25),
					Field::make('text', 'width', 'width')->set_width(25),
					Field::make('text', 'height', 'height')->set_width(25),
				)),
			Field::make('complex', 'month', 'month')
				->add_fields(array(
					Field::make('text', 'name', 'name')->set_width(50),
				)),

			// Field::make('complex', 'json-ld-settings', 'JSON-LD Settings')
			// 	->add_fields(array()),
			Field::make('text', 'proxy_base_url', 'Proxy Base URL')->set_width(25)->set_default_value('https://www.premiumtimesng.com/'),
			Field::make('text', 'proxy_title', 'Proxy Title')->set_width(25)->set_default_value('Premium Times - Nigeria leading newspaper for news, investigations'),
			Field::make('text', 'proxy_url', 'Proxy URL With Folder')->set_width(25)->set_default_value('https://www.premiumtimesng.com/casino/hu/'),

			Field::make('text', 'ld_street', 'Street address')
				->set_width(25)
				->set_help_text('Example: Vándor Utca 12.')
				->set_default_value('Vándor Utca 12.'),

			Field::make('text', 'ld_city', 'City (addressLocality)')
				->set_width(25)
				->set_default_value('Budapest'),

			Field::make('text', 'ld_postal_code', 'Postal code')
				->set_width(25)
				->set_default_value('1051'),

			Field::make('text', 'ld_country', 'Country (ISO code)')
				->set_width(25)
				->set_default_value('HU')
				->set_help_text('Use 2-letter country code, e.g. HU'),

			Field::make('text', 'ld_phone', 'Telephone')
				->set_width(25)
				->set_default_value('+36 1 234 5678'),

			Field::make('text', 'ld_email', 'Email')
				->set_width(25)
				->set_default_value('premiumtimesng.hungary@gmail.com'),

			Field::make('text', 'ld_in_language', 'JSON-LD language (inLanguage)')
				->set_width(25)
				->set_default_value('hu')
				->set_help_text('Two-letter language code, e.g. "en", "hu".'),
		]);
}



/* 
if (!function_exists('carbon_get_post_meta')) {
	function carbon_get_post_meta($id, $name, $type = null)
	{
		return false;
	}
}

if (!function_exists('carbon_get_the_post_meta')) {
	function carbon_get_the_post_meta($name, $type = null)
	{
		return false;
	}
}

if (!function_exists('carbon_get_theme_option')) {
	function carbon_get_theme_option($name, $type = null)
	{
		return false;
	}
}

if (!function_exists('carbon_get_term_meta')) {
	function carbon_get_term_meta($id, $name, $type = null)
	{
		return false;
	}
}

if (!function_exists('carbon_get_user_meta')) {
	function carbon_get_user_meta($id, $name, $type = null)
	{
		return false;
	}
}

if (!function_exists('carbon_get_comment_meta')) {
	function carbon_get_comment_meta($id, $name, $type = null)
	{
		return false;
	}
} */