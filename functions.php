<?php
define('TEMPLATE_URL', get_template_directory_uri());
define('TEMPLATE_URL_DIR', get_stylesheet_directory());

include(__DIR__ . '/include/inc.php');
include(__DIR__ . '/include/carbon.php');

add_filter('xmlrpc_methods', 'sar_block_xmlrpc_attacks');

add_filter('wp_img_tag_add_auto_sizes', '__return_false');

// Или более точечно:
add_filter('wp_content_img_tag', function ($image) {
	return str_replace('sizes="auto"', '', $image);
});

add_shortcode('year', function ($attr, $content) {
	return date('Y');
});


add_shortcode('month', function ($attr, $content) {
	$month = carbon_get_theme_option('month');
	if (!empty($month) && isset($month[date('n') - 1])) {
		return $month[date('n') - 1]['name'];
	}
	return date('F');
});

add_filter('the_content', 'remove_empty_p_tags');

function remove_empty_p_tags($content)
{
	// Удаляем пустые параграфы
	$content = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $content);
	// Удаляем параграфы, содержащие только пробелы
	$content = preg_replace('/<p[^>]*>[\s&nbsp;]*<\/p>/i', '', $content);
	return $content;
}

/**
 * Unset XML-RPC Methods.
 *
 * @param array $methods Array of current XML-RPC methods.
 */
function sar_block_xmlrpc_attacks($methods)
{
	unset($methods['pingback.ping']);
	unset($methods['pingback.extensions.getPingbacks']);
	return $methods;
}


add_filter('wp_calculate_image_sizes', function ($sizes) {
	if (strpos($sizes, 'auto,') === 0) {
		$sizes = trim(str_replace('auto,', '', $sizes));
	}
	return $sizes;
});

add_action('wp', 'sar_remove_x_pingback_header_44', 9999);

/**
 * Remove X-Pingback from Header for WP 4.4+.
 */
function sar_remove_x_pingback_header_44()
{
	header_remove('X-Pingback');
}

add_action('after_setup_theme', function () {
	register_nav_menus(array(
		'menu' => 'menu',
		'footer' => 'footer',
		'footer_2' => 'footer_2',

	));
});
add_filter('Yoast\WP\SEO\enable_notification_post_trash', '__return_false');
add_filter('Yoast\WP\SEO\enable_notification_post_slug_change', '__return_false');
add_filter('Yoast\WP\SEO\enable_notification_term_delete', '__return_false');
add_filter('Yoast\WP\SEO\enable_notification_term_slug_change', '__return_false');

add_filter('redirect_canonical', 'no_redirect_on_404');
function no_redirect_on_404($redirect_url)
{
	if (is_404()) {
		return false;
	}
	return $redirect_url;
}


/* add_filter('redirect_canonical', 'no_redirect_on_404');
function no_redirect_on_404($redirect_url)
{
    if (is_404()) {
        return false;
    }
    return $redirect_url;
} */

function get_bonus_by_bonus_type($bonus_type, $id)
{
	//var_dump($bonus_type);
	//var_dump($id);

	$bonuses = get_field('table_bonusses_type_options', $id);
	//var_dump(array_column( $bonuses, 'type' ));

	if (!empty($bonuses)) {
		$key = array_search($bonus_type, array_column($bonuses, 'type'));
		// var_dump($id);
		// var_dump($key);
		// var_dump($bonuses[ $key ]);

		if ($key !== false && isset($bonuses[$key])) {
			//var_dump($bonuses[ $key ]);
			//var_dump($key);
			$res = $bonuses[$key];

			if (empty($res['referral_link'])) {
				$res['referral_link'] = get_field('referral_link_default', $id);
			}

			return $res;
		}
	}

	$res = [
		"bonus" => '',
		"benefits" => '',
		"referral_link" => '',
	];

	return $res;
}

add_shortcode('blockquote', function ($attr, $content) {

	return '</section><blockquote class="blockquote">' . do_shortcode($content) . '<cite class="cite">' . $attr['cite'] . '</cite></blockquote><section class="section section_gray">';
});

add_shortcode('gray', function ($attr, $content) {

	return '</section><section class="section section_gray">' . do_shortcode($content) . '</section><section class="section section_gray">';
});

add_shortcode('yellow', function ($attr, $content) {

	return '</section><section class="section section_yellow">' . do_shortcode($content) . '</section><section class="section section_gray">';
});

add_shortcode('faq', function ($attr, $content) {
	$block = get_field('faq');
	$res = '</section>
		<section class="section section_yellow">
                <h2 class="h2">' . $block['title'] . '</h2>
                <div class="faq">';
	if ($block['items']) foreach ($block['items'] as $item) {
		$res .= '
						  <details class="faq__item" open>
                        <summary class="faq-heading flexbox">
                            <span class="heading">' . $item['title'] . '</span>
                            <svg class="faq-icon" width="14" height="8">
                                <use href="' . TEMPLATE_URL . 'img/sprite.svg#sprite--arrow-none" />
                            </svg>
                        </summary>
                        <div class="text">
									<div>' . $item['text'] . '</div>                            
                        </div>
                    </details>';
	}
	$res .= '
					 </div>
            </section>
				<section class="section section_gray">
				';
	return $res;
});
add_shortcode('best', function ($attr, $content) {

	return str_ireplace('<ol>', '<ol class="list-best">', $content);
});

add_shortcode('pros_cons', function ($attr, $content) {
	$block = get_field('pros_cons');
	$id = $attr['id'] ?: 1;
	$id--;
	if (!isset($block['items'][$id])) {
		return '';
	}
	$block = $block['items'][$id];

	$res = '<div class="side flexbox">
                    <div class="side__item side-pros">
                        <div class="side-header">' . $block['pros_title'] . '</div>
								' . str_ireplace('<ul>', '<ul class="list-check">', $block['pros_text']) . '                        
                    </div>
                    <div class="side__item side-cons">
                        <div class="side-header">' . $block['cons_title'] . '</div>
								' . str_ireplace('<ul>', '<ul class="list-not">', $block['cons_text']) . '                        
                    </div>
                </div>';
	return $res;
});

add_filter('the_content', function ($content) {
	$content = str_replace('<table>', '<div class="table_scroll scroll"><table class="table">', $content);
	$content = str_replace('</table>', '</table></div>', $content);

	return $content;
});

// свой класс построения меню:
class mainMenuWalker extends Walker_Nav_Menu
{

	// add classes to ul sub-menus
	function start_lvl(&$output, $depth = 0, $args = NULL)
	{
		// depth dependent classes
		$indent = ($depth > 0  ? str_repeat("\t", $depth) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0

		// build html
		$output .= "\n" . $indent . '
									<button type="button" class="menu-btn" aria-expanded="false" aria-controls="menu__downmenu' . $this->current_item_id . '" aria-label="open downmenu">
                                <svg width="14" height="8">
                                    <use href="' . TEMPLATE_URL . '/img/sprite.svg#sprite--arrow-none" />
                                </svg>
                            </button>
                            <div class="downmenu" id="menu__downmenu' . $this->current_item_id . '">
                                <ul class="submenu">' . "\n";
	}
	function end_lvl(&$output, $depth = 0, $args = array())
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat($t, $depth);
		$output .= "$indent</ul></div>{$n}";
	}
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth = 0, $args = NULL, $id = 0)
	{
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat("\t", $depth) : ''); // code indent
		$this->current_item_id = $item->ID;
		// depth dependent classes
		$depth_classes = array(
			($depth == 0 ? 'menu__item' : 'submenu__item'),
			($depth >= 2 ? 'sub-sub-menu-item' : ''),
			($depth % 2 ? 'menu-item-odd' : 'menu-item-even'),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr(implode(' ', $depth_classes));

		// passed classes
		$classes = empty($item->classes) ? array() : (array) $item->classes; //var_dump($classes);
		$class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item)));
		$class_names = str_replace('menu-item-has-children', 'menu-arr', $class_names);
		// build html
		$output .= $indent . '<li class="' . $depth_class_names . ' ' . $class_names . '">';

		// link attributes
		$attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';
		$attributes .= ' class="' . 'link__' . $item->ID . ' ' . ($depth > 0 ? 'submenu-link' : 'menu-link') . '"';
		//var_dump($item);die;
		$item_output = sprintf(
			'%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters('the_title', $item->title, $item->ID),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
}


// свой класс построения меню:
class mobile_mainMenuWalker extends Walker_Nav_Menu
{

	// add classes to ul sub-menus
	function start_lvl(&$output, $depth = 0, $args = NULL)
	{
		// depth dependent classes
		$indent = ($depth > 0  ? str_repeat("\t", $depth) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'submenu',
			($display_depth % 2  ? 'menu-odd' : 'menu-even'),
			($display_depth >= 2 ? 'sub-sub-menu' : ''),
			'menu-depth-' . $display_depth
		);
		$class_names = implode(' ', $classes);

		$r = rand(0, 999);
		// build html
		$output .= "\n" . $indent . '<button type="button" class="downmenu-btn-open" aria-expanded="false" aria-controls="menu__downmenu' . $r . '" aria-label="open downmenu"></button> <div class="downmenu" id="menu__downmenu' . $r . '"><ul class=" submenu ' . $class_names . '">' . "\n";
	}
	function end_lvl(&$output, $depth = 0, $args = array())
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat($t, $depth);
		$output .= "$indent</ul></div>{$n}";
	}
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth = 0, $args = NULL, $id = 0)
	{
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat("\t", $depth) : ''); // code indent

		// depth dependent classes
		$depth_classes = array(
			($depth == 0 ? 'menu__item' : 'submenu__item'),
			($depth >= 2 ? 'sub-sub-menu-item' : ''),
			($depth % 2 ? 'menu-item-odd' : 'menu-item-even'),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr(implode(' ', $depth_classes));

		// passed classes
		$classes = empty($item->classes) ? array() : (array) $item->classes; //var_dump($classes);
		$class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item)));
		$class_names = str_replace('menu-item-has-children', 'menu-downmenu flexbox', $class_names);
		// build html
		$output .= $indent . '<li class="' . $depth_class_names . ' ' . $class_names . '">';

		// link attributes
		$attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';
		$attributes .= ' class="' . 'link__' . $item->ID . ' ' . ($depth > 0 ? 'submenu-link' : 'menu-link') . '"';
		//var_dump($item);die;
		$item_output = sprintf(
			'%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters('the_title', $item->title, $item->ID),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
}

// свой класс построения меню:
class footer_mainMenuWalker extends Walker_Nav_Menu
{

	// add classes to ul sub-menus
	function start_lvl(&$output, $depth = 0, $args = NULL)
	{
		// depth dependent classes
		$indent = ($depth > 0  ? str_repeat("\t", $depth) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'submenu',
			($display_depth % 2  ? 'menu-odd' : 'menu-even'),
			($display_depth >= 2 ? 'sub-sub-menu' : ''),
			'menu-depth-' . $display_depth
		);
		$class_names = implode(' ', $classes);

		// build html
		$output .= "\n" . $indent . '<div class="downmenu"><ul class=" submenu ' . $class_names . '">' . "\n";
	}
	function end_lvl(&$output, $depth = 0, $args = array())
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat($t, $depth);
		$output .= "$indent</ul></div>{$n}";
	}
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth = 0, $args = NULL, $id = 0)
	{
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat("\t", $depth) : ''); // code indent

		// depth dependent classes
		$depth_classes = array(
			($depth == 0 ? 'footer-list__item' : 'sub-menu-item'),
			($depth >= 2 ? 'sub-sub-menu-item' : ''),
			($depth % 2 ? 'menu-item-odd' : 'menu-item-even'),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr(implode(' ', $depth_classes));

		// passed classes
		$classes = empty($item->classes) ? array() : (array) $item->classes; //var_dump($classes);
		$class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item)));

		// build html
		$output .= $indent . '<li class="' . $depth_class_names . ' ' . $class_names . '">';

		// link attributes
		$attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';
		$attributes .= ' class="' . 'link__' . $item->ID . ' ' . ($depth > 0 ? 'sub-menu-link' : 'footer-list-link') . '"';
		//var_dump($item);die;
		$item_output = sprintf(
			'%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters('the_title', $item->title, $item->ID),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
}



function get_table_of_content()
{

	$pafe_content = ob_get_contents();
	ob_get_clean();
	$new_toc = '
	<div class="tablecontent">
                    <button type="button" aria-expanded="false" aria-controls="tablecontent" class="heading" aria-label="open tablecontent">' . carbon_get_theme_option('table_of_content') . '</button>
                    <ol class="tablecontent-list" id="tablecontent">

                                    ';
	preg_match_all('/(<h([2]{1})([^>]*>)).*<\/h\2>/msuU', $pafe_content, $matches, PREG_SET_ORDER);
	$last_level = 0;

	foreach ($matches as $h_row) {
		$anchor = url_anchor_target($h_row['0']);
		$level = intval($h_row[2]);
		if ($level >= 3) $level_step = 1;
		else $level_step = 0;

		if ($level_step > $last_level)
			$new_toc .= '<ol>';
		else {
			$new_toc .= str_repeat('</ol>', $last_level - $level_step);
			$new_toc .= '</li>';
		}
		$new_toc .= "<li class='tablecontent-list__item'><a href='" . get_the_permalink() . "#" . $anchor . "' class='tablecontent-list-link'>" . trim(strip_tags($h_row['0'])) . "</a>\n";
		$new_h = str_replace($h_row[1], '<h' . $h_row[2] . ' id="' . $anchor . '"' . $h_row[3], $h_row[0]);
		$pafe_content = str_replace($h_row[0], $new_h, $pafe_content);
		$last_level = $level_step;
	}
	/* 
					<li class="tablecontent-list__item"><a href="#" class="tablecontent-list-link">Mit über 25 Jahren Erfahrung vergleichen unsere Experten alle Casinos</a></li>
					<li class="tablecontent-list__item"><a href="#" class="tablecontent-list-link">Minimum deposit casinos are online parlours that accept very</a></li>
					<li class="tablecontent-list__item"><a href="#" class="tablecontent-list-link">Games for betting and winning, even if they have $5 or $10 on their balance</a></li> */
	$new_toc .= "
				</ol>
		</div>
		";
	//$new_toc .= '<button type="button" class="close__content"><span class="fas fa-chevron-circle-down"></span></button>';

	//var_dump(get_field('table_of_contents', get_the_ID()));
	//if(get_field('table_of_contents', get_the_ID())==''){}else{$new_toc=htmlspecialchars_decode(get_field('table_of_contents', get_the_ID()));}

	$pafe_content = preg_replace('/<section\s+class="section section_gray">\s*<\/section>/', '', $pafe_content);

	//$pafe_content = preg_replace('/(<h2[^>]*>.*?)<br>(.*?<\/h2>)/is', '', $pafe_content);

	//$pafe_content = preg_replace('/(<h2[^>]*>.*?)<br \/>(.*?<\/h2>)/is', '', $pafe_content);

	//$pafe_content = str_ireplace('<p></p>','',$pafe_content);
	//$pafe_content = preg_replace('/<p>\s*<\/p>/','',$pafe_content);

	$pafe_content = str_replace('[table_of_content]', $new_toc, $pafe_content);


	$pafe_content = str_replace('<style>img:is([sizes="auto" i], [sizes^="auto," i]) { contain-intrinsic-size: 3000px 1500px }</style>', '', $pafe_content);
	$pafe_content = str_replace('sizes="auto,', 'sizes="', $pafe_content);
	$pafe_content = str_replace('targetl="_blank"', 'target="_blank"', $pafe_content);
	$pafe_content = str_replace('<section class="section section_gray"></p>', '<section class="section section_gray">', $pafe_content);
	$pafe_content = str_replace('<section class="section section_yellow"></p>', '<section class="section section_yellow">', $pafe_content);
	//$pafe_content = str_replace('<table','<div class="table_scroll scroll"><table class="table" ',$pafe_content);	
	//$pafe_content = str_replace('</table>','</table></div>',$pafe_content); 

	echo $pafe_content;
}


function url_anchor_target($title)
{
	$return = false;
	if ($title) {
		$return = trim(strip_tags($title));
		$return = remove_accents($return);
		$return = str_replace(array("\r", "\n", "\n\r", "\r\n"), ' ', $return);
		$return = str_replace('&amp;', '', $return);
		$return = preg_replace('/[^a-zA-Z0-9 \-_]*/', '', $return);
		$return = str_replace(
			array('  ', ' '),
			'_',
			$return
		);
		$return = rtrim($return, '-_');
		$return = strtolower($return);
		$return = str_replace('_', '-', $return);
		$return = str_replace('--', '-', $return);
	}
	return $return;
}

function dimox_breadcrumbs()
{
	if (get_field('hide_breadcrumbs')) {
		return;
	}

	/* === ОПЦИИ === */
	$text['home']     = carbon_get_theme_option('breadcrumbs_home'); // текст ссылки "Главная"
	$text['category'] = '%s'; // текст для страницы рубрики
	$text['search']   = 'Search "%s"'; // текст для страницы с результатами поиска
	$text['tag']      = 'Tag "%s"'; // текст для страницы тега
	$text['author']   = 'Author %s'; // текст для страницы автора
	$text['404']      = 'Error 404'; // текст для страницы 404
	$text['page']     = 'Page %s'; // текст 'Страница N'
	$text['cpage']    = 'Page comments %s'; // текст 'Страница комментариев N'

	$wrap_before    = '<ul class="breadcrumbs flexbox">'; // открывающий тег обертки
	$wrap_after     = '</ul><!-- .breadcrumbs -->'; // закрывающий тег обертки
	$sep            = ''; // разделитель между "крошками"
	$before         = '<li class="breadcrumbs__item">'; // тег перед текущей "крошкой"
	$after          = '</li>'; // тег после текущей "крошки"

	$show_on_home   = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
	$show_home_link = 0; // 1 - показывать ссылку "Главная", 0 - не показывать
	$show_current   = 1; // 1 - показывать название текущей страницы, 0 - не показывать
	$show_last_sep  = 1; // 1 - показывать последний разделитель, когда название текущей страницы не отображается, 0 - не показывать
	/* === КОНЕЦ ОПЦИЙ === */

	global $post;
	$home_url       = home_url('/');
	$link           = '<li class="breadcrumbs__item">';
	$link          .= '<a class="breadcrumbs-link" href="%1$s"><span>%2$s</span></a>';
	$link          .= '<meta content="%3$s" />';
	$link          .= '</li>';
	$parent_id      = ($post) ? $post->post_parent : '';
	$home_link      = sprintf($link, $home_url, $text['home'], 1);

	$position = 0;

	$br = get_field('home_breadcrumb');
	if (!empty($br['link'])) {
		$position++;
		echo $wrap_before . sprintf($link, $br['link'], $br['title'], $position);
	}

	if (is_home() || is_front_page()) {

		if ($show_on_home) echo $wrap_before . $home_link . $wrap_after;
	} else {

		if (empty($br['link'])) {
			echo $wrap_before;
		}

		if ($show_home_link) {
			$position += 1;
			echo $home_link;
		}

		if (is_category()) {
			$parents = get_ancestors(get_query_var('cat'), 'category');
			foreach (array_reverse($parents) as $cat) {
				$position += 1;
				if ($position > 1) echo $sep;
				echo sprintf($link, get_category_link($cat), get_cat_name($cat), $position);
			}
			if (get_query_var('paged')) {
				$position += 1;
				$cat = get_query_var('cat');
				echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat), $position);
				echo $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
			} else {
				if ($show_current) {
					if ($position >= 1) echo $sep;
					echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
				} elseif ($show_last_sep) echo $sep;
			}
		} elseif (is_search()) {
			if (get_query_var('paged')) {
				$position += 1;
				if ($show_home_link) echo $sep;
				echo sprintf($link, $home_url . '?s=' . get_search_query(), sprintf($text['search'], get_search_query()), $position);
				echo $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
			} else {
				if ($show_current) {
					if ($position >= 1) echo $sep;
					echo $before . sprintf($text['search'], get_search_query()) . $after;
				} elseif ($show_last_sep) echo $sep;
			}
		} elseif (is_year()) {
			if ($show_home_link && $show_current) echo $sep;
			if ($show_current) echo $before . get_the_time('Y') . $after;
			elseif ($show_home_link && $show_last_sep) echo $sep;
		} elseif (is_month()) {
			if ($show_home_link) echo $sep;
			$position += 1;
			echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);
			if ($show_current) echo $sep . $before . get_the_time('F') . $after;
			elseif ($show_last_sep) echo $sep;
		} elseif (is_day()) {
			if ($show_home_link) echo $sep;
			$position += 1;
			echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'), $position) . $sep;
			$position += 1;
			echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'), $position);
			if ($show_current) echo $sep . $before . get_the_time('d') . $after;
			elseif ($show_last_sep) echo $sep;
		} elseif (is_single() && ! is_attachment()) {
			if (get_post_type() != 'post') {
				$position += 1;
				$post_type = get_post_type_object(get_post_type());
				if ($position > 1) echo $sep;
				echo sprintf($link, get_post_type_archive_link($post_type->name), $post_type->labels->name, $position);
				if ($show_current) echo $sep . $before . get_the_title() . $after;
				elseif ($show_last_sep) echo $sep;
			} else {
				$cat = get_the_category();
				$catID = $cat[0]->cat_ID;
				$parents = get_ancestors($catID, 'category');
				$parents = array_reverse($parents);
				$parents[] = $catID;
				foreach ($parents as $cat) {
					$position += 1;
					if ($position > 1) echo $sep;
					echo sprintf($link, get_category_link($cat), get_cat_name($cat), $position);
				}
				if (get_query_var('cpage')) {
					$position += 1;
					echo $sep . sprintf($link, get_permalink(), get_the_title(), $position);
					echo $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
				} else {
					if ($show_current) echo $sep . $before . get_the_title() . $after;
					elseif ($show_last_sep) echo $sep;
				}
			}
		} elseif (is_post_type_archive()) {
			$post_type = get_post_type_object(get_post_type());
			if (get_query_var('paged')) {
				$position += 1;
				if ($position > 1) echo $sep;
				echo sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label, $position);
				echo $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
			} else {
				if ($show_home_link && $show_current) echo $sep;
				if ($show_current) echo $before . $post_type->label . $after;
				elseif ($show_home_link && $show_last_sep) echo $sep;
			}
		} elseif (is_attachment()) {
			$parent = get_post($parent_id);
			$cat = get_the_category($parent->ID);
			$catID = $cat[0]->cat_ID;
			$parents = get_ancestors($catID, 'category');
			$parents = array_reverse($parents);
			$parents[] = $catID;
			foreach ($parents as $cat) {
				$position += 1;
				if ($position > 1) echo $sep;
				echo sprintf($link, get_category_link($cat), get_cat_name($cat), $position);
			}
			$position += 1;
			echo $sep . sprintf($link, get_permalink($parent), $parent->post_title, $position);
			if ($show_current) echo $sep . $before . get_the_title() . $after;
			elseif ($show_last_sep) echo $sep;
		} elseif (is_page() && ! $parent_id) {
			if ($show_home_link && $show_current) echo $sep;
			if ($show_current) echo $before . (get_field('breadcrumb') ?: get_the_title()) . $after;
			elseif ($show_home_link && $show_last_sep) echo $sep;
		} elseif (is_page() && $parent_id) {
			$parents = get_post_ancestors(get_the_ID());
			foreach (array_reverse($parents) as $pageID) {
				$position += 1;
				if ($position > 1) echo $sep;
				echo sprintf($link, get_page_link($pageID), (get_field('breadcrumb', $pageID) ?: get_the_title($pageID)), $position);
			}
			if ($show_current) echo $sep . $before . (get_field('breadcrumb') ?: get_the_title()) . $after;
			elseif ($show_last_sep) echo $sep;
		} elseif (is_tag()) {
			if (get_query_var('paged')) {
				$position += 1;
				$tagID = get_query_var('tag_id');
				echo $sep . sprintf($link, get_tag_link($tagID), single_tag_title('', false), $position);
				echo $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
			} else {
				if ($show_home_link && $show_current) echo $sep;
				if ($show_current) echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
				elseif ($show_home_link && $show_last_sep) echo $sep;
			}
		} elseif (is_author()) {
			$author = get_userdata(get_query_var('author'));
			if (get_query_var('paged')) {
				$position += 1;
				echo $sep . sprintf($link, get_author_posts_url($author->ID), sprintf($text['author'], $author->display_name), $position);
				echo $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
			} else {
				if ($show_home_link && $show_current) echo $sep;
				if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
				elseif ($show_home_link && $show_last_sep) echo $sep;
			}
		} elseif (is_404()) {
			if ($show_home_link && $show_current) echo $sep;
			if ($show_current) echo $before . $text['404'] . $after;
			elseif ($show_last_sep) echo $sep;
		} elseif (has_post_format() && ! is_singular()) {
			if ($show_home_link && $show_current) echo $sep;
			echo get_post_format_string(get_post_format());
		}

		echo $wrap_after;
	}
} // end of dimox_breadcrumbs()
function get_img_payment($payment)
{
	$items = carbon_get_theme_option('payments');
	if ($items) foreach ($items as $item) {
		if ($item['name'] == $payment) {
			return $item['img'];
		}
	}
}

add_filter('acf/load_field/name=payment', function ($field) {
	$items = carbon_get_theme_option('payments');
	$field['choices'] = [];
	if ($items) foreach ($items as $item) {

		$field['choices'][] = [$item['name'] => $item['name']];
	}
	return $field;
});

add_action('acf/render_field_settings/type=image', 'add_default_value_to_image_field');
function add_default_value_to_image_field($field)
{
	acf_render_field_setting($field, array(
		'label'			=> 'Default Image',
		'instructions'		=> 'Appears when creating a new post',
		'type'			=> 'image',
		'name'			=> 'default_value',
	));
}

// old something 
add_action('wp_ajax_send_form', 'send_form');
add_action('wp_ajax_nopriv_send_form', 'send_form');

// send_form();
function send_form()
{
	//var_dump($_POST);
	if (empty(trim($_POST['email']))) {
		die;
	}
	// let's start with some variables
	$api_key = MAIL_API_CHIMP;
	$email = $_POST['email']; // the user we are going to subscribe
	$status = 'subscribed'; // we are going to talk about it in just a little bit
	//$merge_fields = array( 'FNAME' => 'Misha' ); // FNAME, LNAME or something else
	$list_id = 'a5c15a2b59'; // List / Audience ID

	// start our Mailchimp connection
	$connection = curl_init();
	curl_setopt(
		$connection,
		CURLOPT_URL,
		'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($email))
	);
	curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . base64_encode('user:' . $api_key)));
	curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($connection, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($connection, CURLOPT_POST, true);
	curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt(
		$connection,
		CURLOPT_POSTFIELDS,
		json_encode(array(
			'apikey'        => $api_key,
			'email_address' => $email,
			'status'        => $status,
			//'merge_fields'  => $merge_fields,
			//'tags' => array( 'Coffee', 'Snowboard' ) // you can specify some tags here as well
		))
	);

	$result = curl_exec($connection);
	//var_dump($result);
}


function pagenavi($before = '', $after = '', $query = null)
{
	global $wpdb, $wp_query;
	if (!$query) {
		$query = $wp_query;
	}

	$pagenavi_options = array();
	$pagenavi_options['pages_text'] = ''; //Страница %CURRENT_PAGE% из %TOTAL_PAGES%';
	$pagenavi_options['current_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['page_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['first_text'] = '1';
	$pagenavi_options['last_text'] = '&raquo;';
	$pagenavi_options['next_text'] = '';
	$pagenavi_options['prev_text'] = '';
	$pagenavi_options['dotright_text'] = '...';
	$pagenavi_options['dotleft_text'] = '...';
	$pagenavi_options['style'] = 1;
	$pagenavi_options['num_pages'] = 3;
	$pagenavi_options['always_show'] = 0;
	$pagenavi_options['num_larger_page_numbers'] = 10;
	$pagenavi_options['larger_page_numbers_multiple'] = 100;


	if (!is_single()) {
		$request = $query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$numposts = $query->found_posts;
		$max_page = $query->max_num_pages;
		if (empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = intval($pagenavi_options['num_pages']);
		$larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
		$larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start = floor($pages_to_show_minus_1 / 2);
		$half_page_end = ceil($pages_to_show_minus_1 / 2);
		$start_page = $paged - $half_page_start;
		if ($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if (($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if ($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if ($start_page <= 0) {
			$start_page = 1;
		}
		$larger_per_page = $larger_page_to_show * $larger_page_multiple;
		$larger_start_page_start = (n_round($start_page, 10) + $larger_page_multiple) - $larger_per_page;
		$larger_start_page_end = n_round($start_page, 10) + $larger_page_multiple;
		$larger_end_page_start = n_round($end_page, 10) + $larger_page_multiple;
		$larger_end_page_end = n_round($end_page, 10) + ($larger_per_page);
		if ($larger_start_page_end - $larger_page_multiple == $start_page) {
			$larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
			$larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
		}
		if ($larger_start_page_start <= 0) {
			$larger_start_page_start = $larger_page_multiple;
		}
		if ($larger_start_page_end > $max_page) {
			$larger_start_page_end = $max_page;
		}
		if ($larger_end_page_end > $max_page) {
			$larger_end_page_end = $max_page;
		}
		if ($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			echo $before . '<ul class="pagination flexbox">' . "\n";
			if (!empty($pages_text)) {
				echo '<span class="pages">' . $pages_text . '</span>';
			}

			if ($paged >= 2) {
				echo '
						<li class="pagination__item">
							  <a href="' . clean_url(get_previous_posts_page_link()) . '" class="pagination-arr pagination-arr-prev pagination-link" type="button">
									<svg width="6" height="10">
										 <use href="' . TEMPLATE_URL . '/img/sprite.svg#sprite--arr" />
									</svg>
							  </a>
						</li>';
			}


			if ($start_page >= 2 && $pages_to_show < $max_page) {
				$first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
				echo '<li class="pagination__item"><a href="' . clean_url(get_pagenum_link()) . '" class="pagination-link" title="' . $first_page_text . '">' . $first_page_text . '</a></li>';
			}
			if ($start_page >= 3 && $pages_to_show < $max_page) {
				if (!empty($pagenavi_options['dotleft_text'])) {
					echo '<li class="pagination__item"><span class="pagination-link pagination-more">' . $pagenavi_options['dotleft_text'] . '</span></li>';
				}
			}
			if ($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
				for ($i = $larger_start_page_start; $i < $larger_start_page_end; $i += $larger_page_multiple) {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<li class="pagination__item"><a href="' . clean_url(get_pagenum_link($i)) . '" class="pagination-link" title="' . $page_text . '">' . $page_text . '</a></li>';
				}
			}
			for ($i = $start_page; $i  <= $end_page; $i++) {
				if ($i == $paged) {
					$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
					echo '<li class="pagination__item"><a class="pagination-link active">' . $current_page_text . '</a></li>';
				} else {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<li class="pagination__item"><a href="' . clean_url(get_pagenum_link($i)) . '" class="pagination-link" title="' . $page_text . '">' . $page_text . '</a></li>';
				}
			}
			if ($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
				for ($i = $larger_end_page_start; $i <= $larger_end_page_end; $i += $larger_page_multiple) {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<li class="pagination__item"><a href="' . clean_url(get_pagenum_link($i)) . '" class="pagination-link" title="' . $page_text . '">' . $page_text . '</a></li>';
				}
			}
			if ($end_page < $max_page - 1) {
				if (!empty($pagenavi_options['dotright_text'])) {
					echo '<li class="pagination__item"><span class="pagination-link pagination-more">' . $pagenavi_options['dotright_text'] . '</span></li>';
				}
			}
			if ($end_page < $max_page) {
				$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), number_format_i18n($max_page));
				echo '<li class="pagination__item"><a href="' . clean_url(get_pagenum_link($max_page)) . '" class="pagination-link" title="' . $last_page_text . '">' . $last_page_text . '</a></li>';
			}
			if ($paged < $max_page) {
				echo '<li class="pagination__item">
                                <a href=' . clean_url(get_next_posts_page_link()) . ' class="pagination-arr pagination-next pagination-link" type="button">
                                    <svg width="6" height="10">
                                        <use href="' . TEMPLATE_URL . '/img/sprite.svg#sprite--arr" />
                                    </svg>
                                </a>
                            </li>';
			}



			echo '</ul>' . $after . "\n";
		}
	}
}
### Function: Round To The Nearest Value
function n_round($num, $tonearest)
{
	return floor($num / $tonearest) * $tonearest;
}


/**
 * Return breadcrumbs as array of ['name' => '', 'url' => ''] using dimox logic.
 * Put into theme's functions.php or in an mu-plugin.
 */
function theme_get_breadcrumbs()
{
	if (function_exists('get_field') && get_field('hide_breadcrumbs')) {
		return array();
	}

	// options from dimox (we keep only what we need)
	$text_home = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('breadcrumbs_home') : 'Home';
	if (empty($text_home)) $text_home = get_bloginfo('name');

	// helper to read home_breadcrumb custom field (if present)
	$home_breadcrumb = get_field('home_breadcrumb');
	$br_link = !empty($home_breadcrumb['link']) ? $home_breadcrumb['link'] : '';
	$br_title = !empty($home_breadcrumb['title']) ? $home_breadcrumb['title'] : '';

	$items = array();

	// If theme puts a special home breadcrumb
	if (!empty($br_link)) {
		$items[] = array('name' => $br_title ?: $text_home, 'url' => $br_link);
	}

	// If front page/home
	if (is_home() || is_front_page()) {
		// if you want to show something on home, include it; otherwise return empty (as original does)
		if (empty($br_link)) {
			// show site title as single crumb
			$items[] = array('name' => $text_home, 'url' => home_url('/'));
		}
		return $items;
	}

	// If no custom home_breadcrumb printed, include home if configured by original function's $show_home_link = 0,
	// original dimox doesn't show home link by default ($show_home_link=0). If you want home included, uncomment:
	// $items[] = array('name'=>$text_home, 'url'=>home_url('/'));

	// Branches similar to dimox generation (we only build items array)
	if (is_category()) {
		$parents = get_ancestors(get_query_var('cat'), 'category');
		foreach (array_reverse($parents) as $cat) {
			$items[] = array('name' => get_cat_name($cat), 'url' => get_category_link($cat));
		}
		// current category
		$items[] = array('name' => single_cat_title('', false), 'url' => '');
		return $items;
	}

	if (is_search()) {
		$items[] = array('name' => sprintf('Search "%s"', get_search_query()), 'url' => home_url('?s=' . rawurlencode(get_search_query())));
		return $items;
	}

	if (is_year()) {
		$items[] = array('name' => get_the_time('Y'), 'url' => get_year_link(get_the_time('Y')));
		return $items;
	}

	if (is_month()) {
		$items[] = array('name' => get_the_time('Y'), 'url' => get_year_link(get_the_time('Y')));
		$items[] = array('name' => get_the_time('F'), 'url' => get_month_link(get_the_time('Y'), get_the_time('m')));
		return $items;
	}

	if (is_day()) {
		$items[] = array('name' => get_the_time('Y'), 'url' => get_year_link(get_the_time('Y')));
		$items[] = array('name' => get_the_time('F'), 'url' => get_month_link(get_the_time('Y'), get_the_time('m')));
		$items[] = array('name' => get_the_time('d'), 'url' => '');
		return $items;
	}

	if (is_single() && ! is_attachment()) {
		if (get_post_type() != 'post') {
			$post_type = get_post_type_object(get_post_type());
			if ($post_type) {
				$items[] = array('name' => $post_type->labels->name, 'url' => get_post_type_archive_link($post_type->name));
			}
			$items[] = array('name' => get_the_title(), 'url' => '');
		} else {
			$cats = get_the_category();
			if (!empty($cats)) {
				$catID = $cats[0]->cat_ID;
				$parents = get_ancestors($catID, 'category');
				$parents = array_reverse($parents);
				$parents[] = $catID;
				foreach ($parents as $cat) {
					$items[] = array('name' => get_cat_name($cat), 'url' => get_category_link($cat));
				}
			}
			$items[] = array('name' => get_the_title(), 'url' => '');
		}
		return $items;
	}

	if (is_post_type_archive()) {
		$post_type = get_post_type_object(get_post_type());
		if ($post_type) {
			$items[] = array('name' => $post_type->label, 'url' => get_post_type_archive_link($post_type->name));
		}
		return $items;
	}

	if (is_attachment()) {
		$parent = get_post(get_post()->post_parent);
		if ($parent) {
			$cats = get_the_category($parent->ID);
			if (!empty($cats)) {
				$catID = $cats[0]->cat_ID;
				$parents = get_ancestors($catID, 'category');
				$parents = array_reverse($parents);
				$parents[] = $catID;
				foreach ($parents as $cat) {
					$items[] = array('name' => get_cat_name($cat), 'url' => get_category_link($cat));
				}
			}
			$items[] = array('name' => $parent->post_title, 'url' => get_permalink($parent));
		}
		$items[] = array('name' => get_the_title(), 'url' => '');
		return $items;
	}

	if (is_page()) {
		$parent_id = (get_post()) ? get_post()->post_parent : 0;
		if ($parent_id) {
			$parents = get_post_ancestors(get_the_ID());
			foreach (array_reverse($parents) as $pageID) {
				$items[] = array(
					'name' => (get_field('breadcrumb', $pageID) ? get_field('breadcrumb', $pageID) : get_the_title($pageID)),
					'url'  => get_page_link($pageID)
				);
			}
		}
		$items[] = array(
			'name' => (get_field('breadcrumb') ? get_field('breadcrumb') : get_the_title()),
			'url'  => ''
		);
		return $items;
	}

	if (is_tag()) {
		$items[] = array('name' => sprintf('Tag "%s"', single_tag_title('', false)), 'url' => get_tag_link(get_query_var('tag_id')));
		return $items;
	}

	if (is_author()) {
		$author = get_userdata(get_query_var('author'));
		if ($author) {
			$items[] = array('name' => sprintf('Author %s', $author->display_name), 'url' => get_author_posts_url($author->ID));
		}
		return $items;
	}

	if (is_404()) {
		$items[] = array('name' => 'Error 404', 'url' => '');
		return $items;
	}

	// last fallback: return site name
	$items[] = array('name' => get_bloginfo('name'), 'url' => home_url('/'));
	return $items;
}

// Add hreflangs
add_action('wp_head', function () {

	if (is_admin()) {
		return;
	}

	$base = 'https://www.premiumtimesng.com/casino/hu';
	$uri = $_SERVER['REQUEST_URI'] ?? '/';
	$uri = strtok($uri, '?');
	$uri = preg_replace('#^/(?:casino/)?hu/#i', '/', $uri);

	$uri = '/' . ltrim($uri, '/');

	$current_url = trailingslashit(rtrim($base, '/') . $uri);

	echo "\n<link rel=\"alternate\" hreflang=\"hu-HU\" href=\"" . esc_url($current_url) . "\" />\n";
	echo "<link rel=\"alternate\" hreflang=\"hu\" href=\"" . esc_url($current_url) . "\" />\n";
	echo "<link rel=\"alternate\" hreflang=\"x-default\" href=\"" . esc_url($current_url) . "\" />\n";
}, 5);


function get_current_month(): string
{
	return wp_date('F');
}

function get_current_year(): string
{
	return wp_date('Y');
}

function replace_dynamic_vars(string $text): string
{
	return str_replace(
		['[year]', '[month]'],
		[get_current_year(), get_current_month()],
		$text
	);
}

add_filter('wpseo_title', 'replace_dynamic_vars');
add_filter('wpseo_metadesc', 'replace_dynamic_vars');
