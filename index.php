<?php
$homeDataPage = get_page_by_path('home-data');
$feedbacks = get_field('feedbacks', $homeDataPage->ID);
$title = get_field('title', $homeDataPage->ID);
$desc = get_field('desc', $homeDataPage->ID);
$clientsImgDesktop = get_field('clients_img_desktop', $homeDataPage->ID);
$clientsImgMobile = get_field('clients_img_mobile', $homeDataPage->ID);
get_header();
?>

<div class="index-wrap">
    <div class="index-welcome">
        <div class="container px-4">
            <div class="row">
                <div class="col-12 col-lg-4 order-lg-2 offset-lg-1 mb-5 mb-lg-0">
                    <img src="<?= esc_url( get_template_directory_uri() ) ?>/img/index-welcome.svg" alt="歡迎來到野薑設計工作室">
                </div>
                <div class="col-12 col-lg-7 order-lg-1">
                    <h1 class="index-welcome-h1">結合商業美感的網站設計、內容系統與 AI 工具</h1>
                    <h2><pre><?=$title?></pre></h2>
                    <div class="desc"><pre><?=$desc?></pre></div>
                </div>
            </div>
        </div>
    </div>

    <div class="index-featured index-box">
        <?php get_template_part('template-parts/post/list-process', 'featured', array( 'category_slug' => 'featured', 'num' => 4, 'more' => true )); ?>
    </div>

    <div class="index-service index-box">
        <div class="container">
            <?php get_template_part('template-parts/content/content-service');?>
        </div>
    </div>

    <div class="index-highlights index-box">
        <?php get_template_part('template-parts/post/list-process', 'highlights', array( 'category_slug' => 'highlights', 'num' => 3, )); ?>
    </div>

    <div class="index-projects index-box">
        <?php get_template_part('template-parts/post/list-projects', 'projects', array( 'category_slug' => 'projects', 'num' => 2, )); ?>
    </div>

    <div class="index-process index-box pb-5">
        <?php get_template_part('template-parts/post/list-process', 'process', array( 'category_slug' => 'process', 'num' => 6, )); ?>
    </div>

    <?php if ($feedbacks): ?>
    <div class="index-box">
        <header class="header-page">
            <div class="container">
                <h2>Feedback<span>客戶回饋</span></h2>
                <p>從小型登陸頁到大型正式網站，<br>野薑致力於提供高品質設計給您。</p>
            </div>
        </header>
        <div class="container">
            <div class="row gy-4">
                <?php foreach($feedbacks as $feed): ?>
                <div class="col-12 col-lg-4">
                    <a href="<?=$feed['link']?>">
                        <img src="<?=$feed['img']['url']?>" alt="" class="w-100">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($clientsImgMobile): ?>
    <div class="index-box">
        <header class="header-page">
            <div class="container">
                <h2>
                    Clients
                    <span>客戶品牌</span>
                </h2>
                <p>我們合作客戶，都是很棒的人，設計也為他們創造價值，歡迎加入我們！</p>
            </div>
        </header>
        <div class="container">
            <picture>
                <source srcset="<?=$clientsImgDesktop?>" media="(min-width: 768px)">
                <img src="<?=$clientsImgMobile?>" alt="Clients" class="w-100">
            </picture>
        </div>
    </div>
    <?php endif; ?>

    <div class="index-box" id="homeedm">
        <header class="header-page mb-0">
            <div class="container">
                <h2>
                    You
                    <span>有興趣嗎？為你事業打造一個家吧！</span>
                </h2>
                <p>打造事業的家，需要一份藍圖，我們已經為您準備好了，填寫 Email 領取您網站藍圖吧！</p>
            </div>
        </header>
        <?php get_template_part('template-parts/content/subscribe');?>
        <div class="container">
            <h3 class="title-sm mb-3">你已經準備好打造美好的家嗎？立即和我們聯絡！</h3>
            <a href="/contact" class="btn btn-gold btn-gold-normal">填寫需求單</a>
            <span class="ms-3 gold-light"><?php get_template_part('template-parts/svg/arrow-right');?></span>
        </div>
    </div>

</div><!-- .index-wrap -->

<script>
_lt('send', 'cv', {
  type: 'Conversion'
},['299420f4-b7ee-4f96-b243-c4fa8c70f23f']);
</script>

<?php
get_footer();
