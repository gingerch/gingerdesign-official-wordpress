<?php
$homeDataPage = get_page_by_path('home-data');
$feedbacks = get_field('feedbacks', $homeDataPage->ID);
$clientsImgDesktop = get_field('clients_img_desktop', $homeDataPage->ID);
$clientsImgMobile = get_field('clients_img_mobile', $homeDataPage->ID);
get_header();
?>

<div class="index-wrap">
    <div class="index-welcome">
        <div class="container px-4">
            <div class="row">
                <div class="col-12 col-lg-4 order-lg-2 offset-lg-1 mb-5 mb-lg-0">
                    <img src="<?= esc_url( get_template_directory_uri() ) ?>/img/index-welcome.svg" alt="歡迎來到野薑設計工作室" data-no-lazy fetchpriority="high">
                </div>
                <div class="col-12 col-lg-7 order-lg-1">
                    <h1 class="index-welcome-h1">網站設計、AI agent、內容行銷、商業美感</h1>
                    <?php // 首頁 welcome 主文案：已脫離 ACF，直接改下面文字與 <br> 換行即可；樣式在 sass/index.sass 的 .index-welcome h2 / .desc ?>
                    <h2>讓事業持續成長，也讓生活留有餘裕。</h2>
                    <h3>野薑運用科技，陪你打造聰明且從容的經營方式。</h3>
                    <div class="desc">我們是一間想為人們創造「從容餘裕」的數位科技工作室。
                    <br>在野薑，科技的價值，不是讓人更有效率，而是減少重複的勞動。用聰明的方式經營事業，將時間留給重要的人。
                    <br>因此，我們持續實驗網站系統、AI、各種槓桿，協助品牌主累積長期的數位資產。
                    <br>讓每一次投入，不只完成眼前的工作，而是能持續產生價值。
                    <div class="desc-signoff">逐步創造從容、有餘裕的人生。
                    <br>- Work with Ease, Live with Space</div></div>
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
                <?php $feedIndex = 0; foreach($feedbacks as $feed): $feedIndex++; ?>
                <div class="col-12 col-lg-4">
                    <a href="<?=$feed['link']?>" aria-label="查看客戶好評 <?=$feedIndex?>">
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
