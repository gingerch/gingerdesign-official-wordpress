
<?php
    $category_slug = $args['category_slug'];
    $num = $args['num'];
    $category = get_category_by_slug($category_slug);
    $categoryLink = get_category_link($category->term_id);
    $postItemClass = 'col-12 col-lg-6';
    if ($num === 3 || $num === 6) $postItemClass = 'col-12 col-lg-4';
?>

<div class="box-list" id="<?=$category_slug?>">
    <div class="container">
        <header class="header-category">
            <h2>
                <?=$category->description?>
                <span><?=$category->name?></span>
            </h2>
            <a href="<?=$categoryLink?>" class="more" aria-label="查看更多<?=esc_attr($category->name)?>">
                <?php get_template_part('template-parts/svg/arrow-right');?>
            </a>
        </header>
        <div class="row gx-lg-5">
            <?php
                $args = array( 'numberposts' => $num, 'category' => $category->cat_ID, 'post_status' => 'publish' );
                $recent_posts = wp_get_recent_posts( $args );
                foreach( $recent_posts as $key => $post) {
                    $link = get_permalink($post['ID']);
                    $my_post_categories = get_the_category($post['ID']);
                    $my_post_child_cats = array();
                    foreach ( $my_post_categories as $post_cat ) {
                        if ( 0 != $post_cat->category_parent ) {
                            $my_post_child_cats[] = $post_cat->cat_name;
                        }
                    }
            ?>
                <div class="<?=$postItemClass?> post-item">
                    <a href="<?=$link?>">
                        <div class="post-img">
                            <img src="<?=get_feature_image($post['ID'])?>" alt="<?=$post['post_title']?>">
                        </div>
                        <div class="post-text">
                            <label class="post-sub-cat"><?=$my_post_child_cats[0]?></label>
                            <h3 class="post-title max-two-lines"><?=$post['post_title']?></h3>
                            <p class="post-excerpt max-three-lines"><?=$post['post_excerpt']?></p>
                        </div>
                    </a>
                </div>
            <?php }; ?>
        </div>
    </div>
</div>
