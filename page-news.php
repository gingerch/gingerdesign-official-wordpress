<?php
    $id = $_GET['id'] ?? null;
    $fbPost = null;
    $fbPosts = null;
    if ($id) {
        $fbPost = json_decode(getFBPost($id));
    }
    if (!$fbPost) {
        $offset = (int) ($_GET['offset'] ?? 0);
        $fbPostData = json_decode(getFBPosts(9, $offset));
        $fbPosts = $fbPostData->data ?? [];
        $paging = $fbPostData->paging ?? null;
    }
    get_header();
?>

<div class="mb-5">
    <div class="container">
        <header class="header-category text-center pt-5">
            <?php if($fbPost): ?><h2 class="mb-4">開業日記 Crafters' Posts</h2><?php else: ?><h1 class="mb-4">開業日記 Crafters' Posts</h1><?php endif; ?>
            <?php if(!$fbPost): ?>
                <p class="mb-0">即時與臉書粉專同步 Sync with our facebook，<br>歡迎追蹤我們！ welcome to follow us。</p>
            <?php endif; ?>
        </header>
        <?php if($fbPost):
            $date = new DateTime($fbPost->created_time);
            $shareCount = 0;
            $tags = $fbPost->message_tags;
            $title = $tags[0]->name;
            $title = ltrim($title, '#');
            if ($fbPost->shares->count) $shareCount = $fbPost->shares->count;
        ?>
            <div class="box-news">
                <h1 class="news-title"><?=$title?></h1>
                <div class="news-info">
                    <div class="post-date"><?=$date->format('Y-m-d')?></div>
                    <ul class="list-counts">
                        <li>
                            <span class="material-icons">favorite</span>
                            <b><?=$fbPost->likes->summary->total_count?></b>
                        </li>
                        <li>
                            <span class="material-icons">share</span>
                            <b><?=$shareCount?></b>
                        </li>
                        <li>
                            <span class="material-icons">comment</span>
                            <b><?=$fbPost->comments->summary->total_count?></b>
                        </li>
                    </ul>
                </div>
                <div class="mb-3">
                    <img src="<?=$fbPost->full_picture?>" class="w-100">
                </div>
                <?php if ($fbPost->message_tags):?>
                <ul class="list-tags">
                    <?php foreach($fbPost->message_tags as $tag): ?>
                    <li><?=$tag->name?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <p class="news-message"><?=$fbPost->message?></p>
                <div class="text-center">
                    <ul class="list-shares my-5">
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?=get_permalink()?>" target="_blank" rel="noopener noreferrer"><img src="<?= esc_url( get_template_directory_uri() ) ?>/img/share-facebook.svg" alt="share to Facebook"></a>
                        </li>
                        <li>
                            <a href="https://twitter.com/intent/tweet?url=<?=get_permalink()?>" target="_blank" rel="noopener noreferrer"><img src="<?= esc_url( get_template_directory_uri() ) ?>/img/share-twitter.svg" alt="share to Twitter"></a>
                        </li>
                        <li>
                            <a href="https://line.me/R/share?text=<?=get_permalink()?>" target="_blank" rel="noopener noreferrer"><img src="<?= esc_url( get_template_directory_uri() ) ?>/img/share-line.svg" alt="share to LINE"></a>
                        </li>
                        <li>
                            <a href="#" class="copy-link">
                                <img src="<?= esc_url( get_template_directory_uri() ) ?>/img/share-link.svg" alt="share link">
                                <span class="material-icons">check_circle</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="row gx-lg-5 list-news">
                <?php foreach($fbPosts as $post):
                    $date = new DateTime($post->created_time);
                ?>
                    <div class="col-12 col-lg-4 post-text-item mb-5">
                        <p class="post-date mb-1 ms-3"><?=$date->format('Y-m-d')?></p>
                        <div>
                            <?php if($post->full_picture): ?>
                            <img src="<?=$post->full_picture?>" class="w-100 mb-3">
                            <?php endif; ?>
                            <a href="/news?id=<?=$post->id?>">
                                <p class="post-excerpt max-eight-lines"><?=$post->message?></p>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <ul class="list-prev-next">
                <?php if($offset >= 9): ?>
                <li class="prev">
                    <a href="/news?offset=<?=$offset-9?>">
                        <span class="material-icons">arrow_back</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="next">
                    <a href="/news?offset=<?=$offset+9?>">
                        <span class="material-icons">arrow_forward</span>
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();
