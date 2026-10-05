<?php
/**
 * 成功案例頁 Hero 輪播（Figma node 7420:821 project_hero）
 * 結構：圖卡（整張可點、連到作品）→ 右下角圓點 → 標題（h3）→ 副標（子分類名）
 *
 * 資料來源：projects 分類的 ACF Repeater `project_hero`（見 functions.php ginger_get_project_hero）
 * 只有 /category/projects/ 頂層頁會引入（category.php），沒資料就整塊不輸出。
 * 換圖 / 換順序 / 加減張數：後台「文章 → 分類 → 成功案例」編輯頁的「Hero 圖卡」。
 * 標題副標不用填，自動帶所選作品的標題與分類。
 *
 * 輪播行為在 js/project-hero.js：5 秒自動播、hover 暫停、點圓點跳張、手機左右滑。
 * 只有一張時 JS 不啟動、圓點隱藏。
 */
$slides = $args['slides'] ?? array();
if ( empty( $slides ) ) {
    return;
}
$count = count( $slides );
?>
<div class="container">
    <section class="project-hero" data-project-hero aria-roledescription="carousel" aria-label="精選案例">
        <div class="project-hero__viewport">
            <div class="project-hero__track" data-project-hero-track>
                <?php foreach ( $slides as $i => $slide ) : ?>
                <div class="project-hero__slide" data-project-hero-slide aria-roledescription="slide" aria-label="<?= esc_attr( ( $i + 1 ) . ' / ' . $count ); ?>"<?= $i === 0 ? '' : ' aria-hidden="true"'; ?>>
                    <a href="<?= esc_url( $slide['link'] ); ?>" class="project-hero__link" aria-label="<?= esc_attr( $slide['title'] ); ?>">
                        <img src="<?= esc_url( $slide['img_url'] ); ?>" alt="<?= esc_attr( $slide['img_alt'] ); ?>" width="760" height="489"<?= $i === 0 ? ' data-no-lazy fetchpriority="high"' : ''; ?>>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ( $count > 1 ) : ?>
        <div class="project-hero__dots" role="tablist" aria-label="切換圖卡">
            <?php foreach ( $slides as $i => $slide ) : ?>
            <button type="button" class="project-hero__dot<?= $i === 0 ? ' is-active' : ''; ?>" data-project-hero-dot="<?= $i; ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false'; ?>" aria-label="第 <?= $i + 1; ?> 張：<?= esc_attr( $slide['title'] ); ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="project-hero__captions" aria-live="polite">
            <?php foreach ( $slides as $i => $slide ) : ?>
            <div class="project-hero__caption<?= $i === 0 ? ' is-active' : ''; ?>" data-project-hero-caption>
                <h3 class="project-hero__title"><a href="<?= esc_url( $slide['link'] ); ?>"><?= esc_html( $slide['title'] ); ?></a></h3>
                <?php if ( $slide['subtitle'] !== '' ) : ?>
                <p class="project-hero__subtitle"><?= esc_html( $slide['subtitle'] ); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
