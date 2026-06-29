<?php
    $fbPosts = json_decode(getFBPosts(4, 0))->data ?? null;
?>

<?php if ($fbPosts): ?>
<div class="box-list" id="news">
    <div class="container">
        <header class="header-category">
            <h2>
                Crafters' Posts
                <span>開業日記</span>
            </h2>
            <a href="/news" class="more"><img src="<?= esc_url( get_template_directory_uri() ) ?>/img/arrow-right.svg" alt="more"></a>
        </header>
        <div class="row gx-lg-5">
            <?php foreach($fbPosts as $post):
                $date = new DateTime($post->created_time);
            ?>
                <div class="col-12 col-lg-4 post-text-item">
                    <div>
                        <div class="post-date"><?=$date->format('Y-m-d')?></div>
                        <a href="/news?id=<?=$post->id?>">
                            <p class="post-excerpt max-eight-lines"><?=$post->message?></p>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
