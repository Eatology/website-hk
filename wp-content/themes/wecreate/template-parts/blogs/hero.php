<style>
    .article-hero {
        background: url('<?php the_post_thumbnail_url('full') ?>') center center no-repeat;
    }
    @media (max-width: 600px) {
        .article-hero{
            background: url('<?php the_post_thumbnail_url('wecreate_mobile_cover') ?>') center center no-repeat;
        }
    }
</style>
<div class="article-hero"></div>