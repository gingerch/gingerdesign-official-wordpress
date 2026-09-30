// 成功案例頁 Hero 輪播（template-parts/post/project-hero.php）
// 自動每 5 秒換一張、滑鼠移入暫停、點圓點跳張、觸控左右滑。
// 只有一張時直接不啟動。原生 JS，不依賴 jQuery。

const AUTOPLAY_MS = 5000;
const SWIPE_THRESHOLD = 40;

export function initProjectHero() {
    document.querySelectorAll('[data-project-hero]').forEach(setup);
}

function setup(root) {
    const track = root.querySelector('[data-project-hero-track]');
    const slides = Array.from(root.querySelectorAll('[data-project-hero-slide]'));
    const dots = Array.from(root.querySelectorAll('[data-project-hero-dot]'));
    const captions = Array.from(root.querySelectorAll('[data-project-hero-caption]'));
    if (!track || slides.length < 2) return;

    let index = 0;
    let timer = null;
    let dragStartX = null;
    let dragDeltaX = 0;

    function goTo(next) {
        index = (next + slides.length) % slides.length;
        track.style.transform = `translateX(-${index * 100}%)`;
        slides.forEach((el, i) => {
            if (i === index) el.removeAttribute('aria-hidden');
            else el.setAttribute('aria-hidden', 'true');
        });
        dots.forEach((el, i) => {
            el.classList.toggle('is-active', i === index);
            el.setAttribute('aria-selected', i === index ? 'true' : 'false');
        });
        captions.forEach((el, i) => el.classList.toggle('is-active', i === index));
    }

    function start() {
        stop();
        timer = setInterval(() => goTo(index + 1), AUTOPLAY_MS);
    }
    function stop() {
        if (timer) clearInterval(timer);
        timer = null;
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => { goTo(i); start(); });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin', stop);
    root.addEventListener('focusout', start);

    // 觸控滑動：跟著手指位移，放開超過門檻就換張，否則彈回
    track.addEventListener('touchstart', (e) => {
        if (e.touches.length !== 1) return;
        dragStartX = e.touches[0].clientX;
        dragDeltaX = 0;
        track.classList.add('is-dragging');
        stop();
    }, { passive: true });

    track.addEventListener('touchmove', (e) => {
        if (dragStartX === null) return;
        dragDeltaX = e.touches[0].clientX - dragStartX;
        const pct = (dragDeltaX / track.clientWidth) * 100;
        track.style.transform = `translateX(calc(-${index * 100}% + ${pct}%))`;
    }, { passive: true });

    function endDrag() {
        if (dragStartX === null) return;
        track.classList.remove('is-dragging');
        if (dragDeltaX <= -SWIPE_THRESHOLD) goTo(index + 1);
        else if (dragDeltaX >= SWIPE_THRESHOLD) goTo(index - 1);
        else goTo(index);
        dragStartX = null;
        dragDeltaX = 0;
        start();
    }
    track.addEventListener('touchend', endDrag);
    track.addEventListener('touchcancel', endDrag);

    // 滑動途中的 click 不要跳頁
    track.addEventListener('click', (e) => {
        if (Math.abs(dragDeltaX) > 5) e.preventDefault();
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) stop();
        else start();
    });

    goTo(0);
    start();
}
