
<?php
    $category_slug = $args['category_slug'];
    $num = $args['num'];
    $category = get_category_by_slug($category_slug);
    $categoryLink = get_category_link($category->term_id);
    $postItemClass = 'col-12 col-lg-6';
    if ($num === 3) $postItemClass = 'col-12 col-lg-4';
?>

<div class="" id="<?=$category_slug?>">
    <header class="header-page">
        <div class="container">
            <h2>
                <?=$category_slug?>
                <span><?=$category->name?></span>
            </h2>
            <p><?=$category->description?></p>
            <a href="<?=$categoryLink?>" class="more" aria-label="查看更多<?=esc_attr($category->name)?>">
                <?php get_template_part('template-parts/svg/arrow-right');?>
            </a>
        </div>
    </header>
    <div class="container">
        <div class="row gx-lg-5">
            <?php
                $args = array( 'numberposts' => $num, 'category' => $category->cat_ID, 'post_status' => 'publish' );
                $recent_posts = wp_get_recent_posts( $args );
                foreach( $recent_posts as $key => $post):
                    $link = get_permalink($post['ID']);
                    $categories = get_the_category($post['ID']);
                    $term = get_queried_object();
            ?>
                <div class="<?=$postItemClass?> post-item">
                    <a href="<?=$link?>">
                        <div class="post-img">
                            <img src="<?=get_feature_image($post['ID'])?>" alt="<?=$post['post_title']?>">
                        </div>
                    </a>
                    <div class="post-text">
                        <h3 class="post-title max-two-lines">
                            <a href="<?=$link?>"><?=$post['post_title']?></a>
                        </h3>
                    </div>
                    <div class="list-categories">
                    <?php foreach($categories as $category):
                        if ($category->slug !== 'projects'):
                        $category_link = get_category_link($category);
                    ?>
                        <a href="<?=$category_link?>"><?=$category->cat_name?></a>
                    <?php endif; endforeach; ?>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
