<?php
$term = get_queried_object();
$projects_category = get_category_by_slug('projects');
get_header();

if ($term->category_parent === $projects_category->term_id) {
    get_template_part( 'template-parts/post/cat-service', get_post_format() );
} else {
    if ( have_posts() ) {
        ?>
        <header class="header-page">
            <div class="container">
                <h1>
                    <?=$term->slug;?>
                    <span><?=$term->name?></span>
                </h1>
                <p><?=$term->description?></p>
            </div>
        </header>
        <?php
        // 成功案例頂層頁：header 之後、卡片格之前插入 Hero 輪播（ACF 沒資料就不輸出）
        if ( $term->slug === 'projects' ) {
            get_template_part( 'template-parts/post/project-hero', null, array( 'slides' => ginger_get_project_hero( $term ) ) );
        }
        ?>
        <div class="container gx-4">
            <div class="row">
            <?php
                while ( have_posts() ) {
                    the_post();
                    get_template_part( 'template-parts/post/category', get_post_format());
                }
            ?>
            </div>
            <div>
                <?=the_posts_pagination(array(
                    'prev_text' => __( '<span class="material-icons">arrow_back</span>', 'textdomain' ),
                    'next_text' => __( '<span class="material-icons">arrow_forward</span>', 'textdomain' ),));?>
            </div>
        </div>
        <?php
    } else {
        get_template_part( 'template-parts/content/content-none' );
    }
}


get_footer();

