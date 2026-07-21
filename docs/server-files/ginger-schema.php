<?php
/* Plugin Name: Ginger Schema (Organization + LocalBusiness) */
add_action('wp_head', function () {
    if (!is_front_page() && !is_home()) return;
    $logo_id = get_theme_mod('custom_logo');
    $logo = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : 'https://gingerdesign.com.tw/wp-content/uploads/2022/08/og-img.png';
    $social = array(
        'https://www.facebook.com/GingerDesignUIUX',
        'https://www.instagram.com/gingerdesigntaiwan/',
        'https://page.line.me/ksp9425f',
    );
    $data = array(
        '@context' => 'https://schema.org',
        '@graph' => array(
            array(
                '@type' => 'Organization',
                '@id'   => 'https://gingerdesign.com.tw/#organization',
                'name'  => '野薑設計 GingerDesign',
                'url'   => 'https://gingerdesign.com.tw/',
                'logo'  => $logo,
                'email' => 'contact@gingerdesign.com.tw',
                'sameAs' => $social,
            ),
            array(
                '@type' => 'ProfessionalService',
                '@id'   => 'https://gingerdesign.com.tw/#localbusiness',
                'name'  => '野薑設計 GingerDesign',
                'image' => $logo,
                'url'   => 'https://gingerdesign.com.tw/',
                'email' => 'contact@gingerdesign.com.tw',
                'address' => array(
                    '@type' => 'PostalAddress',
                    'streetAddress' => '西區民權路219號2樓',
                    'addressLocality' => '台中市',
                    'addressCountry' => 'TW',
                ),
                'areaServed' => array('Taiwan', 'Japan', 'Australia', 'Europe', 'North America'),
                'openingHoursSpecification' => array(array(
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => array('Monday','Tuesday','Wednesday','Thursday','Friday'),
                    'opens' => '10:00',
                    'closes' => '18:00',
                )),
                'sameAs' => $social,
            ),
        ),
    );
    echo "\n<script type=\"application/ld+json\">" . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}, 20);
