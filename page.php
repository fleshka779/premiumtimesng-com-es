<? get_header(); ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

        <main>
            <div class="hero">
                <div class="container">
                    <? dimox_breadcrumbs() ?>
                    <? $author_id = get_the_author_ID(); ?>
                    <h1 class="h1"><?= get_field('h1') ?: get_the_title() ?></h1>
                    <div class="update flexbox">
                        <div class="update__item flexbox">
                            <div class="update-title"><?= carbon_get_theme_option('by') ?></div>
                            <span class="update-descr"><?= get_author_name($author_id); ?></span>
                        </div>
                        <div class="update__item flexbox">
                            <div class="update-title"><?= carbon_get_theme_option('last_update') ?></div>
                            <time datetime="<? the_modified_date('Y-m-d') ?>" class="update-descr"><? the_modified_date('d.m.Y') ?></time>

                        </div>
                        <? if ($res = get_field('reading_time')) { ?>
                            <div class="update__item flexbox">
                                <div class="update-title"><?= carbon_get_theme_option('reading_time') ?></div>
                                <div class="update-descr"><?= $res ?></div>
                            </div><? } ?>
                    </div>
                    <?= get_field('top_text') ?>
                </div>
            </div>

            <div class="container">
                <div class="offer">
                    <? if ($h2 = get_field('h2')) { ?>
                        <h2 class="h2"><?= $h2 ?></h2>
                    <? } ?>

                    <? if ($offers = get_field('offers_items')) {
                    ?>
                        <table class="offer-list">
                            <thead class="offer-head">
                                <tr class="offer-tr flexbox">
                                    <th class="offer-th offer-logo"><?= get_field('logo_head') ?: carbon_get_theme_option('table_head_logo') ?></th>
                                    <th class="offer-th offer-bonus"><?= carbon_get_theme_option('table_head_bonus') ?></th>
                                    <th class="offer-th offer-feature"><?= carbon_get_theme_option('table_head_feature') ?></th>
                                    <th class="offer-th offer-rating"><?= carbon_get_theme_option('table_head_rating') ?></th>
                                    <th class="offer-th offer-play"><?= carbon_get_theme_option('table_head_play') ?></th>
                                </tr>
                            </thead>
                            <tbody class="offer-body">
                                <?
                                $bonus_type = get_field('bonus_type');
                                foreach ($offers as $k => $item) {
                                    $id = $item;
                                    $img = get_field('logo', $id);
                                    //$ref = get_field('ref', $id);
                                    $bonus = get_bonus_by_bonus_type($bonus_type, $id);
                                    //var_dump($bonus);
                                    $ref = $bonus['referral_link'] ?? '';
                                    //var_dump($bonus);
                                    if ($bonus["border-color"]) {
                                        echo '<style>
									.offer-list:not(.gridbox) .offer-card.offer_' . $k . ' {
										border: 2px solid ' . $bonus["border-color"] . ';
									}
									</style>';
                                    }
                                    if ($bonus["badge_text"]) { //badge_color
                                        echo '<style>
									.offer-list:not(.gridbox) .offer-card.offer_' . $k . ' .offer-bonus {
										position: relative;
									}
									.offer-list:not(.gridbox) .offer-card.offer_' . $k . ' .offer-bonus:before {
										display: inline-block;
										background: ' . $bonus["badge_color"] . ';
										content: "' . $bonus["badge_text"] . '";
									}
									</style>';
                                    }
                                ?>
                                    <?php
                                    $casino_name = get_field('name', $id); // Casino Name
                                    $casino_id = sanitize_title($casino_name); // Convert to id-friendly slug
                                    ?>
                                    <tr id="<?= $casino_id ?>" class="offer-card flexbox offer_<?= $k ?>">
                                        <td class="offer-td offer-logo">
                                            <a href="<?= $ref ?>" target="_blank" rel="noindex nofollow" class="offer-casino">
                                                <p class="offer-casino-name"><?= get_field('name', $id); ?></p>
                                                <img src="<?= $img['url'] ?>" alt="<?= $img['alt'] ?>" width="180" class="img">
                                            </a>
                                        </td>
                                        <td class="offer-td offer-bonus">
                                            <p class="offer-bonus-text"><?= $bonus['bonus']; ?></p>
                                        </td>
                                        <td class="offer-td offer-feature">
                                            <?
                                            if (isset($bonus['benefits'])) {
                                                if (is_string($bonus['benefits'])) {
                                                    echo str_replace('<ul>', '<ul class="list-check">', $bonus['benefits']);
                                                } elseif (is_array($bonus['benefits'])) {
                                                    echo '<ul class="list-check">';
                                                    foreach ($bonus['benefits'] as $benefit) {
                                                        echo '<li class="list-check">' . $benefit['text'] . '</li>';
                                                    }
                                                    echo '</ul>';
                                                }
                                            }
                                            ?>

                                        </td>
                                        <td class="offer-td offer-rating">
                                            <div class="rating">
                                                <div class="rating-total"><span class="rating-total-span"><?= get_field('rating', $id); ?>/</span>10</div>
                                                <div class="rating-star">
                                                    <span style="width: 0%" class="rating-span"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="offer-td offer-play">
                                            <a href="<?= $ref ?>" target="_blank" rel="noindex nofollow" class="btn btn-play"><span class="btn-span"><?= PLAY ?></span></a>
                                            <ul class="payment flexbox">
                                                <? $payments = get_field('payment', $id);
                                                //get_img_payment
                                                if ($payments) foreach ($payments as $pay) {
                                                ?>
                                                    <li class="payment__item">
                                                        <img src="<?= get_img_payment($pay) ?>" alt="img" width="23">
                                                    </li>
                                                <? } ?>
                                            </ul>
                                        </td>
                                        <td class="offer-td offer-update flexbox">
                                            <?/* ?><time datetime="<?=date('Y-m-d',strtotime(get_field('date', $id)))?>" class="offer-update__item flexbox"><?=carbon_get_theme_option('casino_update')?> <span class="offer-update-time"><?=get_field('date', $id)?></span></time><?/* */ ?>
                                            <time datetime="<?= get_the_modified_date('Y-m-d') ?>" class="offer-update__item flexbox"><?= carbon_get_theme_option('casino_update') ?> <span class="offer-update-time"><?= get_the_modified_date('d.m.Y') ?></span></time>
                                            <div class="offer-update__item"><?= get_field('offer', $id) ?></div>
                                        </td>
                                    </tr>
                                <? } ?>

                            <? } ?>
                            </tbody>
                        </table>

                        <?/* ?><button class="btn btn-more" type="button">Show more</button><?/* */ ?>
                </div>

                <section class="section section_gray">
                    <? the_content() ?>
                </section>



                <? //$block = get_field('articles');
                $block = get_posts([
                    'numberposts' => 4, // Все посты
                    'post_type'   => 'page', // Тип записи (обычные посты)
                    'post_status' => 'publish',
                    'exclude' => get_the_ID()
                ]);
                if ($block) { ?>
                    <section class="latest">
                        <h4 class="h4"><?= carbon_get_theme_option('related_article') ?></h4>
                        <div class="note gridbox">
                            <? foreach ($block as $item) {
                                //$id = $block['article'];
                                $id = $item->ID;
                            ?>
                                <div class="note__item">
                                    <img src="<?= get_the_post_thumbnail_url($id); ?>" alt="<?= esc_attr(strip_tags(get_the_title($id))); ?>" width="210" height="130" class="img">
                                    <div class="note-body">
                                        <a href="<?= get_the_permalink($id); ?>" class="note-heading"><span><?= strip_tags(get_the_title($id)); ?></span></a>
                                        <time datetime="<?= get_the_modified_date('Y-m-d', $id) ?>" class="note-time"><?= get_the_modified_date('d.m.Y', $id) ?></time>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    </section>
                <? } ?>

                <?/* ?>
            <form action="#" method="post" class="form form-sbscrb flexbox">
                <div class="form-title">Subscribe to the newsletter to receive current offers:</div>
                <div class="form-group flexbox">
                    <div class="form-input">
                        <input type="email" name="email" placeholder="E-mail" required>
                    </div>
                    <button class="btn btn-dark" type="button">Subscribe</button>
                </div>
            </form><?/* */ ?>
            </div>
        </main>

    <?php endwhile; ?>
<? endif; ?>
<? get_footer(); ?>