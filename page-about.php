<?php
$aboutPage = get_page_by_path('about');
$title = get_field('title', $aboutPage->ID);
$desc = get_field('desc', $aboutPage->ID);
$team = get_field('team', $aboutPage->ID) ?: [];
// 「合作夥伴」名單：後台 ACF `team` 裡名字在此清單內的人會從「專業團隊」移到「合作夥伴」區塊。
$partnerNames = ['Elsa', 'Alvin', 'Dana'];
$partners = array_values(array_filter($team, fn($p) => in_array(trim($p['name']), $partnerNames, true)));
$team = array_values(array_filter($team, fn($p) => !in_array(trim($p['name']), $partnerNames, true)));
get_header();
?>

<div id="page-about" class="py-5">
    <div class="box-about about-intro">
        <div class="container">
            <div class="box-sm">
                <h1>Hello，我們是野薑設計</h1>
                <div class="about-quote">
                    <img src="<?= esc_url( get_template_directory_uri() ) ?>/img/quote.svg" alt="">
                    <div class="title"><pre><?=$title?></pre></div>
                </div>
                <div class="desc"><pre><?=$desc?></pre></div>
                <div class="text-center">
                    <a href="/contact" class="btn btn-gold">聯絡我們</a>
                </div>
            </div>
        </div>
    </div>
    <div class="box-about about-team py-5">
        <div class="container">
            <h2 class="title-lg text-center">專業團隊</h2>
            <div class="row gx-5 list-team">
                <?php foreach($team as $p): ?>
                <div class="col-12 col-lg-3 mb-4">
                    <img src="<?=$p['img']['sizes']['thumbnail']?>" alt="<?=$p['name']?>">
                    <h3><?=$p['name']?></h3>
                    <h4><?=$p['job']?></h4>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php if ($partners): ?>
    <div class="box-about about-team py-5">
        <div class="container">
            <h2 class="title-lg text-center">合作夥伴</h2>
            <div class="row gx-5 list-team">
                <?php foreach($partners as $p): ?>
                <div class="col-12 col-lg-3 mb-4">
                    <img src="<?=$p['img']['sizes']['thumbnail']?>" alt="<?=$p['name']?>">
                    <h3><?=$p['name']?></h3>
                    <h4><?=$p['job']?></h4>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
get_footer();
