<?php
/*
  <style><?include __DIR__.'/css/style.min.css'?></style>
  
// можно добавить
//https://idiallo.com/blog/css-minifier-in-php
//https://stackoverflow.com/questions/5389822/how-to-minify-js-or-css-on-the-fly
//https://stackoverflow.com/questions/67838616/combine-and-minify-css-with-php

	  <style><? echo_css_mini();?></style>
/**/

/* function echo_css_mini()
{
	$url = __DIR__ . '/css/style.min.css';
	if (file_exists(__DIR__ . '/css/style.min.css')) {
		include $url;
	} else {
		include_once('include/css_minify.php');
		$minifier = new CSSmini();
		$minifier->minify_css(get_template_directory_uri() . '/css/style.css', get_template_directory_uri(), __DIR__ . "/css/style.min.css");
		include __DIR__ . '/css/style.css';
	}
} */

add_filter('the_content', function ($content) {

	$content = str_ireplace('<img ', '<img loading="lazy" ', $content);
	$content = str_ireplace('<iframe ', '<iframe loading="lazy" ', $content);
	return $content;
});


function catch_that_image($getImage = false, $post_2 = '')
{
	global $post, $posts;

	$first_img = '';
	ob_start();
	if ($post_2 == '') {
		$output = preg_match_all("#<img.+src=\"(.+)\".*>#iU", $post->post_content, $matches);
	} else {
		$output = preg_match_all("#<img.+src=\"(.+)\".*>#iU", $post_2->post_content, $matches);
		//var_dump($output);
		//var_dump($matches);
	}
	$first_img = $matches[1][0];
	//var_dump($first_img);
	if ($getImage == false) return empty($first_img) ? 0 : 1;
	return $first_img;
}

function the_content_limit_post($content, $max_char)
{
	//$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content, '<br>');

	if ((strlen($_GET['p']) > 0) && ($espacio = strpos($content, " ", $max_char))) {
		$content = substr($content, 0, $espacio);
		$content = $content;
		echo "<p class='get-more'>";
		echo $content;
		//      echo "&nbsp;<a href='";
		//     the_permalink();
		//    echo "'>" . $more_link_text . "</a>";
		echo "</p>";
	} else if ((strlen($content) > $max_char) && ($espacio = strpos($content, " ", $max_char))) {
		$content = substr($content, 0, $espacio);
		$content = $content;
		echo "<p class='get-more'>";
		echo $content;
		echo "...";
		//        echo "&nbsp;<a href='";
		//       the_permalink();
		//      echo "'>" . $more_link_text . "</a>";
		echo "</p>";
	} else {
		echo "<p class='get-more'>";
		echo $content;
		//      echo "&nbsp;<a href='";
		//     the_permalink();
		//    echo "'>" . $more_link_text . "</a>";
		echo "</p>";
	}
}

function leServerPush()
{
	if (!isset($_COOKIE["h2pushes"])) {
		header("Link: </wp-content/themes/detect/css/style.css?12>; rel=preload; as=style");
		setcookie("h2pushes", "h2pushes", 0, 2592000, "", ".detecthistory.com", true);
	}
}
//add_action('init','leServerPush');

function get_attachment_id_from_src($image_src)
{
	global $wpdb;
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	$id = $wpdb->get_var($query);
	return $id;
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


function get_table_of_content()
{
	/*if(get_field('not_echo_table_of_content')=='not echo'){
		$pafe_content = ob_get_contents();
		ob_end_clean();
		$pafe_content = str_replace('[table_of_content]','',$pafe_content);
		echo $pafe_content;
	}else/**/ {
		$pafe_content = ob_get_contents();
		ob_end_clean();
		$new_toc = "<div class=\"heading\">Contents</div>\n<ul>\n";
		preg_match_all('/(<h([2-3]{1})([^>]*>)).*<\/h\2>/msuU', $pafe_content, $matches, PREG_SET_ORDER);
		$last_level = 0;

		foreach ($matches as $h_row) {
			$anchor = url_anchor_target($h_row['0']);
			$level = intval($h_row[2]);
			if ($level >= 3) $level_step = 1;
			else $level_step = 0;

			if ($level_step > $last_level)
				$new_toc .= '<ul>';
			else {
				$new_toc .= str_repeat('</ul>', $last_level - $level_step);
				$new_toc .= '</li>';
			}
			$new_toc .= "<li><a href='" . get_the_permalink() . "#" . $anchor . "'>" . trim(strip_tags($h_row['0'])) . "</a>\n";
			$new_h = str_replace($h_row[1], '<h' . $h_row[2] . ' id="' . $anchor . '"' . $h_row[3], $h_row[0]);
			$pafe_content = str_replace($h_row[0], $new_h, $pafe_content);
			$last_level = $level_step;
		}
		$new_toc .= "</ul>";
		//$new_toc .= '<button type="button" class="close__content"><span class="fas fa-chevron-circle-down"></span></button>';

		//var_dump(get_field('table_of_contents', get_the_ID()));
		//if(get_field('table_of_contents', get_the_ID())==''){}else{$new_toc=htmlspecialchars_decode(get_field('table_of_contents', get_the_ID()));}

		//if(strpos($pafe_content,'[table_of_content]')){
		$pafe_content = str_replace('[table_of_content]', '' . $new_toc . '', $pafe_content);
		//}

		echo $pafe_content;
	}
}


add_action('after_setup_theme', function () {
	register_nav_menus(array(
		'menu' => 'Main Menu',
		'lang_menu' => 'Lang Menu',
		'menu_footer' => 'menu_footer'
	));
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
			($depth == 0 ? 'menu__item' : 'sub-menu-item'),
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
		$attributes .= ' class="' . 'link__' . $item->ID . ' ' . ($depth > 0 ? 'sub-menu-link' : 'menu__link') . '"';
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


function pagenavi($before = '', $after = '')
{
	global $wpdb, $wp_query;

	$pagenavi_options = array();
	$pagenavi_options['pages_text'] = ''; //Страница %CURRENT_PAGE% из %TOTAL_PAGES%';
	$pagenavi_options['current_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['page_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['first_text'] = '1';
	$pagenavi_options['last_text'] = '&raquo;';
	$pagenavi_options['next_text'] = '';
	$pagenavi_options['prev_text'] = '';
	$pagenavi_options['dotright_text'] = '';
	$pagenavi_options['dotleft_text'] = '';
	$pagenavi_options['style'] = 1;
	$pagenavi_options['num_pages'] = 8;
	$pagenavi_options['always_show'] = 0;
	$pagenavi_options['num_larger_page_numbers'] = 10;
	$pagenavi_options['larger_page_numbers_multiple'] = 10;


	if (!is_single()) {
		$request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;
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
			if ($start_page >= 2 && $pages_to_show < $max_page) {
				$first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
				echo '<li><a href="' . clean_url(get_pagenum_link()) . '" class="first" title="' . $first_page_text . '">' . $first_page_text . '</a></li>';
				if (!empty($pagenavi_options['dotleft_text'])) {
					echo '<span class="extend">' . $pagenavi_options['dotleft_text'] . '</span>';
				}
			}
			if ($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
				for ($i = $larger_start_page_start; $i < $larger_start_page_end; $i += $larger_page_multiple) {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<li><a href="' . clean_url(get_pagenum_link($i)) . '" class="number" title="' . $page_text . '">' . $page_text . '</a></li>';
				}
			}
			//					previous_posts_link($pagenavi_options['prev_text']);
			for ($i = $start_page; $i  <= $end_page; $i++) {
				if ($i == $paged) {
					$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
					echo '<li><a class="number active">' . $current_page_text . '</a></li>';
				} else {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<li><a href="' . clean_url(get_pagenum_link($i)) . '" class="number" title="' . $page_text . '">' . $page_text . '</a></li>';
				}
			}
			//					next_posts_link($pagenavi_options['next_text'], $max_page);
			if ($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
				for ($i = $larger_end_page_start; $i <= $larger_end_page_end; $i += $larger_page_multiple) {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<li><a href="' . clean_url(get_pagenum_link($i)) . '" class="number" title="' . $page_text . '">' . $page_text . '</a></li>';
				}
			}
			if ($end_page < $max_page) {
				if (!empty($pagenavi_options['dotright_text'])) {
					echo '<span class="extend">' . $pagenavi_options['dotright_text'] . '</span>';
				}
				$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
				echo '<li><a href="' . clean_url(get_pagenum_link($max_page)) . '" class="last" title="' . $last_page_text . '">' . $last_page_text . '</a></li>';
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
