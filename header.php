<!DOCTYPE html>
<html lang="<?= carbon_get_theme_option('htmlang'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <!-- css -->
    <link rel="stylesheet" href="<?= TEMPLATE_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= TEMPLATE_URL ?>/css/custom3.css">
    <?php
    $deploy_ver = '';

    if (empty($deploy_ver)) {
        $theme = wp_get_theme();
        $deploy_ver = $theme->get('Version');
    }

    if (empty($deploy_ver)) {
        $style_path = get_stylesheet_directory() . '/css/style.css';
        if (file_exists($style_path)) {
            $deploy_ver = date('Ymd-His', filemtime($style_path));
        } else {
            $deploy_ver = date('Ymd-His', time());
        }
    }

    $deploy_ver = preg_replace('/\s+/', '-', trim($deploy_ver));
    $deploy_ver_attr = esc_attr($deploy_ver);

    echo "\n<!-- site-version: {$deploy_ver_attr} -->\n";
    echo '<meta name="site-version" content="' . $deploy_ver_attr . '">' . "\n";

    $GLOBALS['THEME_DEPLOY_VERSION'] = $deploy_ver_attr;
    ?>

    <?php wp_head(); ?>
    <link rel="icon" href="<?= carbon_get_theme_option('favicon'); ?>" type="image/x-icon" />
    <link rel="shortcut icon" href="<?= carbon_get_theme_option('favicon'); ?>" type="image/x-icon" />
    <?= get_field('schema'); ?>
    <?/* ?>
<link rel="alternate" href="<?=get_the_permalink()?>" hreflang="it-IT" />
<link rel="alternate" href="<?=get_the_permalink()?>" hreflang="x-default" /><?/* */ ?>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header_top flexbox">
                <button type="button" class="hamburger" aria-expanded="false" aria-controls="menu__list" aria-label="open navigation"></button>

                <ul class="soc flexbox">
                    <? if ($res = carbon_get_theme_option('soc_fb')) { ?>
                        <li class="soc">
                            <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                <img src="<?= TEMPLATE_URL ?>/img/facebook.svg" alt="img" width="8" height="14">
                            </a>
                        </li>
                    <? } ?>
                    <? if ($res = carbon_get_theme_option('soc_ln')) { ?>
                        <li class="soc">
                            <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                <img src="<?= TEMPLATE_URL ?>/img/linkedin.svg" alt="img" width="14" height="14">
                            </a>
                        </li>
                    <? } ?>
                    <? if ($res = carbon_get_theme_option('soc_in')) { ?>
                        <li class="soc">
                            <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                <img src="<?= TEMPLATE_URL ?>/img/instagram.svg" alt="img" width="14" height="14">
                            </a>
                        </li>
                    <? } ?>
                    <? if ($res = carbon_get_theme_option('soc_yt')) { ?>
                        <li class="soc">
                            <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                <img src="<?= TEMPLATE_URL ?>/img/youtube.svg" alt="img" width="14" height="14">
                            </a>
                        </li>
                    <? } ?>
                    <? if ($res = carbon_get_theme_option('soc_tw')) { ?>
                        <li class="soc">
                            <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                <img src="<?= TEMPLATE_URL ?>/img/twitter.svg" alt="img" width="14" height="14">
                            </a>
                        </li>
                    <? } ?>

                </ul>

                <a href="<?= carbon_get_theme_option('logo_link'); ?>" class="logo">
                    <img src="<?= carbon_get_theme_option('logo'); ?>" alt="<?= carbon_get_theme_option('logo_alt'); ?>" title="<?= carbon_get_theme_option('logo_title'); ?>" width="150" height="58">
                </a>
                <? $banner = get_field('top_casino') ?: get_field('top_casino', get_option('page_on_front'));

                $bonus_type = get_field('bonus_type');
                $bonus = get_bonus_by_bonus_type($bonus_type, $banner);
                //var_dump($bonus);
                $ref = $bonus['referral_link'] ?? '';

                if ($banner) {
                    $img = get_field('logo', $banner); ?>
                    <a href="<?= $ref; ?>" rel="noindex nofollow" target="_blank" class="bonus-btn flexbox">
                        <img src="<?= $img['url'] ?>" alt="<?= $img['alt'] ?>" width="180" class="img">
                        <span class="bonus-btn-text"><?= carbon_get_theme_option('top_banner_text') ?></span>
                    </a>
                <? } ?>
            </div>

            <nav class="navigation" id="menu__list">
                <button class="close" type="button"></button>
                <div class="navigation-body scroll">
                    <a href="<?= carbon_get_theme_option('logo_link'); ?>" class="logo">
                        <img src="<?= carbon_get_theme_option('logo'); ?>" alt="<?= carbon_get_theme_option('logo_alt'); ?>" title="<?= carbon_get_theme_option('logo_title'); ?>" width="120" height="46">
                    </a>
                    <ul class="menu">
                        <? wp_nav_menu([
                            'theme_location'  => 'menu',
                            'menu'            => '',
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => '',
                            'menu_id'         => '',
                            'echo'            => true,
                            'fallback_cb'     => 'wp_page_menu',
                            'before'          => '',
                            'after'           => '',
                            'link_before'     => '',
                            'link_after'      => '',
                            'items_wrap'      => '%3$s',
                            'depth'           => 0,
                            'walker'          => new mainMenuWalker
                        ]);
                        ?>
                        <?/* ?>
                        <li class="menu__item"><a href="#" class="menu-link">Home</a></li>
                        <li class="menu__item menu-arr">
                            <a href="#" class="menu-link">Elections</a>
                            <button type="button" class="menu-btn" aria-expanded="false" aria-controls="menu__downmenu1" aria-label="open downmenu">
                                <svg width="14" height="8">
                                    <use href="<?=TEMPLATE_URL?>/img/sprite.svg#sprite--arrow-none" />
                                </svg>
                            </button>
                            <div class="downmenu" id="menu__downmenu1">
                                <ul class="submenu">
                                    <li class="submenu__item"><a href="#" class="submenu-link">Real Money Casinos</a></li>
                                    <li class="submenu__item"><a href="#" class="submenu-link">Fast Withdrawal Casinos</a></li>
                                    <li class="submenu__item"><a href="#" class="submenu-link">Best Payout Casinos</a></li>
                                </ul>
                            </div>
                        </li>        <?/* */ ?>
                    </ul>
                    <ul class="soc flexbox">

                        <? if ($res = carbon_get_theme_option('soc_fb')) { ?>
                            <li class="soc__item">
                                <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                    <img src="<?= TEMPLATE_URL ?>/img/facebook.svg" alt="img" width="8" height="14">
                                </a>
                            </li>
                        <? } ?>
                        <? if ($res = carbon_get_theme_option('soc_ln')) { ?>
                            <li class="soc__item">
                                <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                    <img src="<?= TEMPLATE_URL ?>/img/linkedin.svg" alt="img" width="14" height="14">
                                </a>
                            </li>
                        <? } ?>
                        <? if ($res = carbon_get_theme_option('soc_in')) { ?>
                            <li class="soc__item">
                                <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                    <img src="<?= TEMPLATE_URL ?>/img/instagram.svg" alt="img" width="14" height="14">
                                </a>
                            </li>
                        <? } ?>
                        <? if ($res = carbon_get_theme_option('soc_yt')) { ?>
                            <li class="soc__item">
                                <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                    <img src="<?= TEMPLATE_URL ?>/img/youtube.svg" alt="img" width="14" height="14">
                                </a>
                            </li>
                        <? } ?>
                        <? if ($res = carbon_get_theme_option('soc_tw')) { ?>
                            <li class="soc__item">
                                <a href="<?= $res ?>" rel="noindex nofollow" target="_blank" class="soc-link">
                                    <img src="<?= TEMPLATE_URL ?>/img/twitter.svg" alt="img" width="14" height="14">
                                </a>
                            </li>
                        <? } ?>

                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <div class="overly"></div>

    <?php ob_start(); ?>