<?php
    $mailID = '402882ae8d7d6cca018d8222502504ba';
?>

<section class="box-subscribe">
    <div class="container">
        <div class="row">
            <div id="sub-form-container">
                <div id="sub-form-content">
                    <form action="https://api-backend.app.newsleopard.com/api/contacts/subscribe/<?=$mailID?>/verify" method="POST" target="popupwindow" onsubmit="return nl_1554802646254.subscribe()" id="sub-form" class="content active">
                        <div class="input-wrap">
                            <div class="">
                                <label for="sub-name">Name</label>
                                <input id="sub-name" class="sub-inputs" name="name" required style="width: 150px;">
                            </div>
                            <div class="">
                                <label for="sub-mail">Email</label>
                                <input type="email" id="sub-mail" class="sub-inputs" name="email" required style="width: 330px;">
                                <span>＊填寫正確，才會收到小禮物喔！</span>
                            </div>
                            <div class=""><button id="sub-submit" class="btn btn-gold btn-gold-normal" type="submit">訂閱</button></div>
                        </div>
                    </form>
                    <div class="content confirm result">
                        <div class="head">
                            <h3 class="title">訂閱服務確認</h1>
                            <div class="description">已發送 Email 驗證信給你，請點擊信件連結以完成訂閱程序</div>
                        </div>
                    </div>
                    <div class="content err result">
                        <div class="head">
                            <h3 class="title">訂閱失敗</h1>
                            <div class="description">暫時無法接受訂閱，請稍候重新嘗試</div>
                        </div>
                        <button class="btn btn-gold btn-gold-normal" onclick="nl_1554802646254.retry()">重試</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
var nl_1554802646254 = (function subIIFE() {
    function e() {
        var e = document.querySelector('.content.confirm'), t = document.querySelector('form#sub-form');
        r(e), n(t);
    }
    function t(e) {
        var t = document.querySelector('.content.err'), u = document.querySelector('form#sub-form');
        r(t), n(u);
    }
    function r(e) {
        e.className = e.className + ' active';
    }
    function n(e) {
        e.className = e.className.replace(/\bactive\b/g, '');
    }
    function u(e) {
        return encodeURIComponent(e);
    }

    return {
        subscribe: function() {
            var r, n, o, c, a, s = document.querySelector('form#sub-form'), i = s.getAttribute('action'),
            m = new FormData(s), l = m.get('email'),
            f = m.get('name') ? 'email=' + u(l) + '&name=' + u(m.get('name')) : 'email=' + u(l),
            d = (l = s.querySelector('#sub-mail'), s.querySelector('#sub-name'), s.querySelector('#sub-submit'));
            try {
                d.setAttribute('disabled', 'disabled'), r = {
                    target: i,
                    form: f,
                    success: e,
                    failure: t,
                    finally: function() {
                    d.removeAttribute('disabled');
                    },
                }, n = new XMLHttpRequest, o = r.success || function() {
                }, c = r.failure || function() {
                }, a = r.finally || function() {
                }, n.onreadystatechange = function() {
                    this.readyState === XMLHttpRequest.DONE && (this.status >= 200 && this.status < 300 ? (o(), a()) : (c(this.status), a()));
                }, n.open('POST', r.target, !0), n.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'), n.send(r.form);
                // LINE event
                _lt('send', 'cv', { type: 'EDM' },['299420f4-b7ee-4f96-b243-c4fa8c70f23f']);
                // LINE event
            } catch (e) {
            }
            return !1;
        }, retry: function() {
            var e = document.querySelector('.content.err'), t = document.querySelector('form#sub-form');
            n(e), r(t);
        },
    };
})();
</script>
