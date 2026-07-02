<?php
/**
 * Plugin Name: Ginger: Cache-Control headers for front-end
 * Description: 對匿名前台 GET 送 public Cache-Control（給 CF/瀏覽器 cache HTML 用），
 *   後台/預覽/AJAX/REST/POST/query-string 頁面則 no-cache。
 *   注意：Cache Enabler HIT 走 advanced-cache.php 早期 exit，此 hook 不會執行，
 *   相關 Cache-Control 由 Cloudflare Cache Rule 掌控（見 docs/perf-2026-07/CF-cache-rule.md）。
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'send_headers', function () {
    if ( is_admin() ) return;
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) return;
    if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) return;
    if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] !== 'GET' ) return;

    if ( ! empty( $_GET ) ) {
        header( 'Cache-Control: private, no-cache, max-age=0' );
        return;
    }
    if ( is_user_logged_in() || is_preview() || post_password_required() ) {
        header( 'Cache-Control: private, no-cache, max-age=0' );
        return;
    }
    header( 'Cache-Control: public, s-maxage=3600, max-age=600' );
}, 99 );
