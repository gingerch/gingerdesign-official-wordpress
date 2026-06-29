
<?php
    $category_slug = $args['category_slug'];
    $num = $args['num'];
    $more = $args['more'] ?? false;
    $category = get_category_by_slug($category_slug);
    $categoryLink = get_category_link($category->term_id);
    $postItemClass = 'col-12 col-lg-6';
    if ($num === 3 || $num === 6) $postItemClass = 'col-12 col-lg-4';
?>

<div class="" id="<?=$category_slug?>">
    <header class="header-page">
        <div class="container">
            <h2>
                <?=$category_slug?>
                <span><?=$category->name?></span>
            </h2>
            <p><?=$category->description?></p>
            <?php if($more): ?>
            <a href="<?=$categoryLink?>" class="more">
                <?php get_template_part('template-parts/svg/arrow-right');?>
            </a>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <?php
                $args = array( 'numberposts' => $num, 'category' => $category->cat_ID, 'post_status' => 'publish' );
                $recent_posts = wp_get_recent_posts( $args );
                foreach( $recent_posts as $key => $post): 
                    $link = get_permalink($post['ID']);
            ?>
                <div class="<?=$postItemClass?> post-item">
                    <a href="<?=$link?>">
                        <div class="mb-3">
                            <img src="<?=get_feature_image($post['ID'])?>" alt="<?=$post['post_title']?>" class="w-100">
                        </div>
                        <div class="post-text">
                            <h3 class="post-title max-two-lines"><?=$post['post_title']?></h3>
                            <p class="post-excerpt max-three-lines mb-0"><?=$post['post_excerpt']?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
