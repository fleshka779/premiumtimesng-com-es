<? get_table_of_content(); ?>



<footer class="footer">
    <div class="footer_top">
        <div class="container flexbox">
            <div class="footer-info">
                <a href="<?= carbon_get_theme_option('logo_bottom_link'); ?>" class="logo"><img src="<?= carbon_get_theme_option('logo_bottom'); ?>" alt="<?= carbon_get_theme_option('logo_bottom_alt'); ?>" title="<?= carbon_get_theme_option('logo_bottom_title'); ?>" width="140" height="54"></a>
                <p class="copyright"><?= carbon_get_theme_option('footer_copywright') ?></p>
                <div class="contact">
                    <address class="contact__item contact-address"><?= carbon_get_theme_option('footer_address') ?></address>
                    <a href="tel:<?= carbon_get_theme_option('footer_tel') ?>" class="contact__item contact-tel"><?= carbon_get_theme_option('footer_tel_text') ?></a>
                    <a href="mailto:<?= carbon_get_theme_option('footer_email') ?>" class="contact__item contact-email"><?= carbon_get_theme_option('footer_email') ?></a>
                </div>
            </div>

            <nav class="footer-navigation">
                <div class="footer-heading"><?= carbon_get_theme_option('footer_heading') ?></div>
                <ul class="footer-list">
                    <? wp_nav_menu([
                        'theme_location'  => 'footer',
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
                        'walker'          => new footer_mainMenuWalker
                    ]);
                    ?>
                    <?/* ?>
                        <li class="footer-list__item"><a href="#" class="footer-list-link">Casinò non AAMS (Italia)</a></li>
                        <li class="footer-list__item"><a href="#" class="footer-list-link">Casino uden ROFUS (Danimarca)</a></li>
								<?/* */ ?>
                </ul>
            </nav>

            <nav class="footer-navigation">
                <div class="footer-heading"><?= carbon_get_theme_option('footer_heading_2') ?></div>
                <ul class="footer-list">
                    <? wp_nav_menu([
                        'theme_location'  => 'footer_2',
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
                        'walker'          => new footer_mainMenuWalker
                    ]);
                    ?>
                    <?/* ?>
                        <li class="footer-list__item"><a href="#" class="footer-list-link">Casinò non AAMS (Italia)</a></li>
                        <li class="footer-list__item"><a href="#" class="footer-list-link">Casino uden ROFUS (Danimarca)</a></li>
								<?/* */ ?>
                </ul>
            </nav>
        </div>
    </div>
    <div class="footer_bottom">
        <div class="container">
            <ul class="reliable flexbox">
                <? $items = carbon_get_theme_option('footer_secure');
                if ($items) foreach ($items as $item) {
                ?>
                    <li class="reliable__item">
                        <img src="<?= wp_get_attachment_url($item['img']) ?>" alt="<?= $item['alt'] ?>" width="<?= $item['width'] ?>" height="<?= $item['height'] ?>">
                    </li>

                <? } ?>

            </ul>
        </div>
    </div>
</footer>
<button class="btn btn-up" type="button">
    <svg width="14" height="20">
        <use href="<?= TEMPLATE_URL ?>/img/sprite.svg#sprite--arrow-long-none" />
    </svg>
</button>
<?
$banner = get_field('banner') ?: get_field('banner', get_option('page_on_front'));

$bonus_type = get_field('bonus_type');
$bonus = get_bonus_by_bonus_type($bonus_type, $banner);
$ref = $bonus['referral_link'] ?? '';
// var_dump($banner);
// var_dump($bonus_type);
//var_dump($bonus);

if ($banner) {
    $img = get_field('logo', $banner);
?>
    <div class="banner open">
        <button class="close" type="button"></button>
        <div class="banner-logo flexbox">
            <p class="banner-name"><?= get_field('name', $banner) ?></p>
            <img src="<?= $img['url'] ?>" alt="<?= $img['alt'] ?>" width="180" class="img">
        </div>
        <div class="banner-bonus">
            <div class="banner-bonus-text"><?= $bonus['bonus'] ?></div>
        </div>
        <div class="banner-play">
            <a href="<?= $ref ?>" rel="noindex, nofollow" target="_blank" class="btn btn-play"><span class="btn-span"><?= PLAY ?></span></a>
        </div>
    </div>
<? } ?>

<? if (carbon_get_theme_option('show_banner_3') == 'Yes') { ?>
    <div class="bonus">
        <div class="bonus-bg"></div>
        <div class="bonus-body">
            <div class="bonus-wrapper scroll">
                <button class="close" type="button"></button>
                <div class="bonus-heading"><?= carbon_get_theme_option('banner_title') ?></div>
                <div class="offer-list gridbox">
                    <? $ids = carbon_get_theme_option('banner_casinos');
                    if ($ids) foreach ($ids as $id) {
                        $id = $id["id"];
                        $img = get_field('logo', $id);
                        $bonus = get_bonus_by_bonus_type($bonus_type, $id);
                        $ref = $bonus['referral_link'] ?? '';
                        $bonus_type = carbon_get_theme_option('banner_bonus_type');
                    ?>
                        <div class="offer-card">
                            <div class="offer-logo">
                                <a href="<?= $ref ?>" target="_blank" rel="noindex nofollow" class="offer-casino">
                                    <p class="offer-casino-name"><?= get_field('name', $id); ?></p>
                                    <img src="<?= $img['url'] ?>" alt="<?= $img['alt'] ?>" height="92" class="img">
                                </a>
                            </div>
                            <div class="offer-bonus">
                                <p class="offer-bonus-text"><?= $bonus['bonus']; ?></p>
                            </div>
                            <a href="<?= $ref ?>" target="_blank" rel="noindex nofollow" class="btn btn-play"><span class="btn-span"><?= carbon_get_theme_option('play') ?></span></a>
                        </div>
                    <? } ?>
                </div>
                <form method="post" class="form form-sbscrb flexbox">
                    <div class="form-title"><?= carbon_get_theme_option('subscribe_title') ?></div>
                    <div class="form-group flexbox">
                        <div class="form-input">
                            <input type="email" name="email" placeholder="E-mail" required>
                        </div>
                        <button class="btn btn-dark" type="button"><?= carbon_get_theme_option('subscribe') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<? } ?>

<?php

$content = replace_dynamic_vars(ob_get_clean());
echo $content;
?>

<script src="<?= TEMPLATE_URL ?>/js/js.js"></script>
<script src="<?= TEMPLATE_URL ?>/js/custom.js"></script>
<? wp_footer(); ?>
</body>

</html>