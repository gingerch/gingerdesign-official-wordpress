<?php
$projects_category = get_category_by_slug('projects');
$taxonomies = array(
    'category',
);
$args = array(
    'child_of' => $projects_category->term_id,
    'hide_empty' => 0,
    'order_by' => 'term_id',
);
$terms = get_terms($taxonomies, $args);
usort($terms, fn($a, $b) => get_field('cat_order', $b) - get_field('cat_order', $a));
// 三個方案時桌機排成均分三欄（樣式在 list.sass）；其餘數量維持原本四欄
$list_class = count($terms) === 3 ? ' list-service-3' : '';
?>

<header class="header-page">
    <?php if (is_home()): ?><h2>Service<span>服務項目</span></h2><?php else: ?><h1>Service<span>服務項目</span></h1><?php endif; ?>
    <p>從小型登陸頁到大型正式網站，<br>野薑致力於提供高品質設計給您。</p>
</header>
<ul class="list-service<?=$list_class?> row gx-md-5">
    <?php foreach($terms as $cat):
        $img = get_field('category_img', $cat);
        $order = get_field('cat_order', $cat);
    ?>
    <li class="col-12 col-lg-6 col-xxl-3">
        <a href="<?=get_category_link($cat->term_id)?>">
            <img src="<?=$img['url']?>" alt="<?=$cat->name?>">
            <div class="text-center mb-3">
                <button type="button" class="btn btn-gold btn-gold-sm">了解詳情</button>
            </div>
            <h2><?=$cat->name?><span><?=get_field('category_title_en', $cat);?></span></h2>
            <p><?=$cat->description?></p>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
