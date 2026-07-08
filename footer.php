</main>

<?php if (empty($args['isBlankPage'])): ?>
    <div class="footer-main">
        <?php get_template_part('/template-parts/svg/footer-logo'); ?>
        <h2>野薑設計科技有限公司</h2>
        <h4>統編: 93563601</h4>
        <ul class="list-social">
            <li class="facebook">
                <a href="https://www.facebook.com/GingerDesignUIUX" target="_blank" rel="noopener noreferrer">
                    <?php get_template_part('/template-parts/svg/facebook'); ?>
                </a>
            </li>
            <li class="instagram">
                <a href="https://instagram.com/gingerdesigntw?igshid=MmIzYWVlNDQ5Yg==" target="_blank" rel="noopener noreferrer">
                    <?php get_template_part('/template-parts/svg/instagram'); ?>
                </a>
            </li>
            <li class="line">
                <a href="https://lin.ee/XYfFeH7" target="_blank" rel="noopener noreferrer">
                    <?php get_template_part('/template-parts/svg/line'); ?>
                </a>
            </li>
        </ul>
        <h3>Ginger Design</h3>
        <p class="center-line-text"><span>Contact time</span></p>
        <p>Mon ~ Fri  10:00 ~ 18:00</p>
        <p class="center-line-text"><span>Email</span></p>
        <p><a href="mailto:contact@gingerdesign.com.tw">contact@gingerdesign.com.tw</a></p>
        <p class="center-line-text"><span>Phone</span></p>
        <p>0928-252-353</p>
    </div>

    <div id="img-show"><img id="img-show-img" src=""></div>
    <div id="btns-fixed">
        <ul>
            <li><a href="#page"><span class="material-icons">keyboard_arrow_up</span></a></li>
            <li><a href="https://lin.ee/u7j7mWJ" target="_blank"><?php get_template_part('/template-parts/svg/line-simple'); ?></a></li>
            <li><a href="https://www.messenger.com/t/2068633876694243/?messaging_source=source%3Apages%3Amessage_shortlink&source_id=1441792" target="_blank"><?php get_template_part('/template-parts/svg/messenger'); ?></a></li>
        </ul>
    </div>
<?php endif; ?>

</div><!-- #page -->

<?php if (empty($args['isBlankPage'])): ?>

<script src="<?= esc_url( get_template_directory_uri() ) ?>/js/vendor/jquery-3.6.0.slim.min.js?<?= filemtime( get_template_directory() . '/js/vendor/jquery-3.6.0.slim.min.js' ) ?>"></script>
<script src="<?= esc_url( get_template_directory_uri() ) ?>/js/main.js?20240317" type="module"></script>
<!--
<script type="text/javascript"> var arrgetbtn=[]; arrgetbtn.push({"title":"Facebook Messenger","icon":"fwidgethelp-facebook-messenger","link":"https://www.messenger.com/t/2068633876694243/?messaging_source=source%3Apages%3Amessage_shortlink&source_id=1441792","target":"_blank","color":"#FFFFFF","background":"#6699FF"}); arrgetbtn.push({"title":"yichunchen","icon":"fwidgethelp-line-messenger","link":"https://lin.ee/u7j7mWJ","target":"_blank","color":"#FFFFFF","background":"#00B900"}); var WidGetButtonOptions = { id:"7ea76983a93979e5c6b8ae2715a26c9e", iconopen:"fwidgethelp-commenting-o", maintitle:"", mainbackground:"#ffd28d", maincolor:"#ffffff", pulse:"widgethelp_pulse", fasize:"2", direction:"top", position:"rightbottom", arrbtn: arrgetbtn }; (function() { var script = document.createElement("script"); script.type = "text/javascript"; script.async = true; script.src = "https://getbtn.com/widget/index.php?id="+WidGetButtonOptions.id; document.getElementsByTagName("head")[0].appendChild(script); })();</script> -->

<?php endif; ?>


</body>
</html>
