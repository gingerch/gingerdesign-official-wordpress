import { animationData } from './animation.js'
import { initProjectHero } from './project-hero.js'

initProjectHero();

if ($('#lottieRef').length) {
    var params = {
        container: document.getElementById('lottieRef'),
        renderer: 'svg',
        loop: false,
        autoplay: false,
        rendererSettings: {
            progressiveLoad: false,
            preserveAspectRatio: "xMidYMid slice",
        },
        animationData,
    };
    var anim;
    anim = lottie.loadAnimation(params);
    let animPosition = { right: -100, bottom: 400 };
    let lastScrollTop = 0;
    let isCompleteAnim = false;
    let animSite = 0;
    const isMobile = window.innerWidth < 768;
    const animWidth = $('#lottieRef').width();
    const bannerHeight = window.innerHeight * 3;
    const mainPadding = isMobile ? 50 : 120;
    const totalFrames = 34000;
    const animRightStart = animPosition.right;
    const animRightEndPoint = (window.innerWidth - animWidth) / 2;
    const animBottomEnd = isMobile ? -320 : -900;
    const animBottomEndPoint = animBottomEnd + mainPadding + 20;
    const bannerScrollHeight = window.innerHeight * 2 + mainPadding * 2 + 100;
    const scrollPercent = totalFrames / bannerScrollHeight;
    const scrollPercentRounded = Math.round(scrollPercent);
    $(window).scroll(function() {
        const st = window.pageYOffset || document.documentElement.scrollTop;
        //control anim frame
        const animStep = st * scrollPercentRounded;
        if (animStep < totalFrames) {
            !!anim && anim.goToAndStop(animStep);
        } else {
            !!anim && anim.goToAndStop(totalFrames);
        }
        //control anim position
        const animRightPercent = (-animRightStart + animRightEndPoint) / (bannerHeight * 0.1);
        const animRightStep = animPosition.right + (st - bannerHeight * 0.5) * animRightPercent;
        const animBottomPercent = (animPosition.bottom - animBottomEnd) / bannerScrollHeight;
        const animBottomStep = animPosition.bottom - st * animBottomPercent;
        if (animBottomStep > animBottomEndPoint) {
            setAnimPosition({ ...animPosition, bottom: animBottomStep });
            if (st > bannerHeight * 0.5 && animRightStep < animRightEndPoint) {
                setAnimPosition({ right: animRightStep, bottom: animBottomStep, });
            } else if (st > bannerHeight * 0.5) {
                setAnimPosition({ right: animRightEndPoint, bottom: animBottomStep, });
            }
        }
        //control anim is complete
        if (st > lastScrollTop && st > bannerHeight + 100) {
            isCompleteAnim = true;
        } else if (st < lastScrollTop && st < bannerHeight + 100) {
            isCompleteAnim = false;
        }
        lastScrollTop = st <= 0 ? 0 : st;
        //control anim site
        if (st > bannerHeight * 0.5) {
            animSite = 2;
            $('.slogan').removeClass('active');
            $('#slogan-3').addClass('active');
        } else if (st > bannerHeight * 0.25) {
            animSite = 1;
            $('.slogan').removeClass('active');
            $('#slogan-2').addClass('active');
        } else {
            animSite = 0;
            $('.slogan').removeClass('active');
            $('#slogan-1').addClass('active');
        }
    });
}

function setAnimPosition(position) {
    const { right, bottom } = position;
    $('#lottieRef').css({ right, bottom });
}

$('.copy-link').on('click', function(e) {
    e.preventDefault();
    const { href } = window.location;
    copyTextToClipboard(href);
    $(this).addClass('done');
});

$('.list-faq').children('li').children('h3').on('click', function() {
    $(this).closest('li').toggleClass('active');
});

$('.btn-nav-main').on('click', function() {
    $('body').toggleClass('open-nav-main');
});

$('.btn-search').on('click', function() {
    $(this).closest('.box-search').addClass('open');
    $('#search-form-1').focus();
});
$('.btn-search-close').on('click', function() {
    $(this).closest('.box-search').removeClass('open');
});

$('.single-content').find('img').on('click', function(e) {
    e.preventDefault();
    showImg($(this));
});

$('.single-cover').find('img').on('click', function() {
    showImg($(this));
});

$('#img-show').on('click', function() {
    $('#img-show').removeClass('active');
});

function showImg($this) {
    const src = $this.attr('src');
    $('#img-show-img').attr('src', src);
    $('#img-show').addClass('active');
}

function copyTextToClipboard(text) {
    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
    }
    navigator.clipboard.writeText(text).then(function() {
        console.log('Async: Copying to clipboard was successful!');
    }, function(err) {
        console.error('Async: Could not copy text: ', err);
    });
}

function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;

    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Fallback: Copying text command was ' + msg);
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
    }
    document.body.removeChild(textArea);
}

let lastScrollTop = 0;
window.addEventListener("scroll", function(){
    var st = window.pageYOffset || document.documentElement.scrollTop;
    if (st > lastScrollTop) {
        $('body').addClass('down').removeClass('up');
        resetIdleTimer();
    } else if (st < lastScrollTop) {
        $('body').addClass('up').removeClass('down');
    }
    lastScrollTop = st <= 0 ? 0 : st;
}, false);

let idleTimer;
function resetIdleTimer() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(showSiteNotice, 3000); // 3 seconds
}
function showSiteNotice() {
    $('body').addClass('up').removeClass('down');
}
document.addEventListener('mousemove', function() {
    resetIdleTimer();
});
resetIdleTimer();
