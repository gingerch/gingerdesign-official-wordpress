<?php
// remove admin bar
add_filter('show_admin_bar', '__return_false');

// remove wp_head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// remove emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// 移除 head dns-prefetch
remove_action( 'wp_head', 'wp_resource_hints', 2);

function my_theme_setup(){
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'my_theme_setup');

function remove_useless_source() {
	wp_deregister_script('jquery');

	// 匿名前台不需要 WP 內建的區塊/後台 CSS
	if ( is_admin() || is_user_logged_in() ) {
		return;
	}
	wp_dequeue_style( 'dashicons' );                // 37KB，只 admin bar 需要
	wp_dequeue_style( 'wp-block-library' );         // Gutenberg block library
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-themes' );           // classic theme fallback
	wp_dequeue_style( 'global-styles' );            // 6.1+ theme.json inline styles
	wp_dequeue_style( 'convertkit-admin-quicktags' ); // ConvertKit 後台的 quicktags CSS 誤跑到前台
}
add_action('wp_enqueue_scripts', 'remove_useless_source', 100);

add_theme_support('editor-styles');
$editor_stylesheet_path = './style.css';
add_editor_style($editor_stylesheet_path);

function mytheme_custom_excerpt_length( $length ) {
    return 999;
}
add_filter('excerpt_length', 'mytheme_custom_excerpt_length', 999);
remove_filter('excerpt_more', 'new_excerpt_more');

// add_action('template_redirect','remove_wpseo');
function remove_wpseo() {
    if ( is_home() ) {
        $front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
        remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
    }
}

function get_default_image() {
    $num = rand(1,3);
    return esc_url( get_template_directory_uri()). '/img/default-'.$num.'.svg';
}

function get_first_tag($postID) {
    $tags = get_the_tags($postID);
    $firstTagName = '大陰百科';
    if ($tags[0]->name) $firstTagName = $tags[0]->name;
    return $firstTagName;
}

function get_feature_image($postID) {
    $img = get_default_image();
    $images = wp_get_attachment_image_src( get_post_thumbnail_id($postID), 'single-post-thumbnail' );
    if (!empty($images[0])) $img = $images[0];
    return $img;
}

function theme() {  // add class to <body> tag
    global $wp_query;
    $theme = 'light-theme';
    $page = $wp_query->query_vars['pagename'];
    $darkPages = ['service'];
    if (is_home() || in_array($page, $darkPages) || isServiceCategory()) $theme = 'dark-theme';
    return $theme;
}

function getFBPosts($limit, $offset) {
    $token = 'EAAOu6CbZCrnIBO8HXLv07MPjPKqphH3UztA3N8Buv0zO1QHi4qBXdvQUxZAyX6EXZCFoDgL3o7jNm3DDRTdTuxgjVvag21DX6J5V2cmszQRP8bWAJo6WGa4DVYh1aqZA7RacFfhFJLJ2qvnxEFZClLJh6CAanoNG2pAvv9sqz3d2UydqQ94ECTvWZBOs8uZCmgZD';
    $apiUrl = "https://graph.facebook.com/me/published_posts?access_token={$token}&offset={$offset}&limit={$limit}&fields=shares,message_tags,id,full_picture,created_time,message,comments.summary(true),likes.summary(true)";
    $response = wp_remote_get($apiUrl);
    $body = wp_remote_retrieve_body( $response );
    return $body;
}

function getFBPost($id) {
    $token = 'EAAOu6CbZCrnIBO8HXLv07MPjPKqphH3UztA3N8Buv0zO1QHi4qBXdvQUxZAyX6EXZCFoDgL3o7jNm3DDRTdTuxgjVvag21DX6J5V2cmszQRP8bWAJo6WGa4DVYh1aqZA7RacFfhFJLJ2qvnxEFZClLJh6CAanoNG2pAvv9sqz3d2UydqQ94ECTvWZBOs8uZCmgZD';
    $apiUrl = "https://graph.facebook.com/{$id}?access_token={$token}&fields=shares,message_tags,id,full_picture,created_time,message,comments.summary(true),likes.summary(true)";
    $response = wp_remote_get($apiUrl);
    $body = wp_remote_retrieve_body( $response );
    return $body;
}

function isServiceCategory() {
    $projects_category = get_category_by_slug('projects');
    $taxonomies = array(
        'category',
    );
    $args = array(
        'child_of' => $projects_category->term_id,
    );
    $terms = get_terms($taxonomies, $args);
    $current_category = get_queried_object();
    $current_cat_id = $current_category->term_id ?? null;
    return in_array($current_cat_id, array_column($terms, 'term_id'));
}

function get_faq() {
    $faqCat = get_category_by_slug('faq');
    $args = array(
        'category' => $faqCat->term_id,
        'order' => 'ASC',
        'numberposts' => -1,
    );
    $list = get_posts( $args );
    $data = array(
        'name' => $faqCat->name,
        'list' => $list,
    );
    return $data;
}

// 全域圖片優化：
// 1) 所有 <img> 加 loading="lazy" + decoding="async"（首屏用 data-no-lazy 排除）
// 2) uploads/ 內的 png/jpg 若有對應 WebP 檔就包 <picture> 加 WebP source
//    （不用 htaccess Accept 條件，避免 Cloudflare 因不完全支援 Vary: Accept 造成
//     邊緣 cache 供錯格式的問題）
function ginger_lazyload_start() {
    ob_start( 'ginger_lazyload_transform' );
}
add_action( 'template_redirect', 'ginger_lazyload_start', 1 );

function ginger_lazyload_transform( $html ) {
    $upload      = wp_upload_dir();
    $uploads_dir = $upload['basedir'];  // e.g. /var/www/html/wp-content/uploads
    $webpc_dir   = str_replace( '/uploads', '/uploads-webpc/uploads', $uploads_dir );

    return preg_replace_callback(
        '/<img\b([^>]*)>/i',
        function ( $m ) use ( $uploads_dir, $webpc_dir ) {
            $attrs = $m[1];
            $skip  = ( strpos( $attrs, 'data-no-lazy' ) !== false );

            if ( ! $skip ) {
                if ( strpos( $attrs, 'loading=' ) === false ) {
                    $attrs .= ' loading="lazy"';
                }
                if ( strpos( $attrs, 'decoding=' ) === false ) {
                    $attrs .= ' decoding="async"';
                }
            }

            if ( ! preg_match( '/\bsrc="([^"]+)"/i', $attrs, $sm ) ) {
                return '<img' . $attrs . '>';
            }
            $src = $sm[1];
            if ( ! preg_match( '/\.(png|jpe?g)(\?|$)/i', $src ) ) {
                return '<img' . $attrs . '>';
            }
            // 抓 /wp-content/uploads/... 的 path 部分（不管 scheme / host，
            // 避免 wp_upload_dir baseurl 與實際頁面 scheme 不同時 strpos 失敗）
            if ( ! preg_match( '#/wp-content/uploads/([^"?]+)#', $src, $pm ) ) {
                return '<img' . $attrs . '>';
            }
            $rel_path = $pm[1];  // e.g. 2026/04/01-Cover.png

            $webp_file = $webpc_dir . '/' . $rel_path . '.webp';
            if ( ! file_exists( $webp_file ) ) {
                return '<img' . $attrs . '>';
            }

            $webp_url = preg_replace(
                '#/wp-content/uploads/#',
                '/wp-content/uploads-webpc/uploads/',
                preg_replace( '/\?.*$/', '', $src ),
                1
            ) . '.webp';

            return sprintf(
                '<picture><source type="image/webp" srcset="%s"><img%s></picture>',
                esc_url( $webp_url ),
                $attrs
            );
        },
        $html
    );
}
