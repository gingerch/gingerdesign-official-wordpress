<?php
/**
 * 聯絡頁諮詢表單 — 站內自刻 UI，資料仍送進原本的 Google 表單。
 *
 * 每題的 name 直接用 Google 表單的 entry ID；單選／複選的選項文字
 * 必須與 Google 表單後台一字不差（前後空白會自動去掉），否則 Google 會拒收。
 * Google 表單改題目或選項時，要同步改這裡的 $questions。
 *
 * 前置條件：Google 表單的「收集電子郵件地址」必須維持「不收集」，
 * 一旦打開，Google 會要求 reCAPTCHA，站外送單一律被拒（2026-10-13 實測）。
 * Email 用表單裡的一般簡答題「Email」收，entry ID 在下面 $email_entry。
 * 送出行為見 js/contact-form.js，樣式見 sass/contact-form.sass。
 */

$form_id = '1FAIpQLSfbULZw95-QLvdRs2ir7ciWd_nK52pkybDeh1NKpKsND9Ye2Q';
$form_action = 'https://docs.google.com/forms/d/e/' . $form_id . '/formResponse';
$form_viewform = 'https://docs.google.com/forms/d/e/' . $form_id . '/viewform';

// Email 題的 entry ID（表單裡的簡答題「Email」；留空會退回 Google 內建的 emailAddress 欄，但那需要開啟收集 Email，會觸發 reCAPTCHA）
$email_entry = '963654885';
$email_name = $email_entry ? 'entry.' . $email_entry : 'emailAddress';

$questions = [
    [
        'type' => 'text',
        'entry' => '1250325076',
        'label' => '請問如何稱呼您？',
        'desc' => '',
        'placeholder' => '例：野薑設計 - 陳小姐',
        'required' => false,
    ],
    [
        'type' => 'email',
        'name' => $email_name,
        'entry' => 'email',
        'label' => 'Email',
        'desc' => '',
        'placeholder' => 'name@example.com',
        'required' => true,
    ],
    [
        'type' => 'checkbox',
        'entry' => '2125415013',
        'label' => '我希望做的事？',
        'desc' => '以下先列出常詢問的項目，如果有其他需求，請填寫其他，謝謝您～',
        'required' => true,
        'options' => [
            '我想要企業品牌官網，幫助我在網路上建立信任感，讓事業越做越省力',
            '我想要做金流購物車課程網站，商家休息也能持續帶來收益',
        ],
        'other' => true,
    ],
    [
        'type' => 'checkbox',
        'entry' => '1586174509',
        'label' => '我需要的協助',
        'desc' => '',
        'required' => true,
        'options' => [
            '我第一次做網站，需要顧問引導，讓我了解網站如何經營',
            '我已經有 N 次做過網站經驗，知道如何經營網站，需要穩定可靠夥伴',
            '網站設計的服務',
            '工程製作的服務',
            '顧問引導的服務',
        ],
    ],
    [
        'type' => 'text',
        'entry' => '2082320940',
        'label' => '我可以先給野薑評估的文件',
        'desc' => '如果有對外的企畫、其他類似的產品、參考的網站、既有產品連結、文件。歡迎貼上連結，讓野薑評估喔～',
        'placeholder' => '貼上連結即可',
        'required' => false,
    ],
    [
        'type' => 'radio',
        'entry' => '430590087',
        'label' => '我的總預算',
        'desc' => '請列出您預算範圍，有助於評估，若彼此預算範圍不合，也盡量協助轉介適合的工作室',
        'required' => true,
        'options' => [
            '100,000 以下',
            '100,000 - 200,000',
            '200,000 - 400,000',
            '400,000 - 800,000',
            '800,000 以上',
        ],
    ],
    [
        'type' => 'text',
        'entry' => '1212346542',
        'label' => '預計上線的時間',
        'desc' => '大概什麼時候要上呢？歡迎先留下預計上線日期',
        'placeholder' => '例：2027 年 3 月',
        'required' => false,
    ],
    [
        'type' => 'text',
        'entry' => '1589839981',
        'label' => '其他聯絡方式',
        'desc' => 'Line、Telegram、電話、任何方便快速討論的聯絡方式',
        'placeholder' => '例：Line ID、手機',
        'required' => false,
    ],
    [
        'type' => 'radio',
        'entry' => '443521569',
        'label' => '可以聯絡的時間',
        'desc' => '建議週一～週五時間聯繫，讓大家能維持健康的生活 😊',
        'required' => true,
        'options' => [
            '平日白天上班時間 10:00 - 12:00',
            '平日白天上班時間 13:00 - 17:00',
            '平日晚上下班時間 18:00 - 21:00',
        ],
    ],
    [
        'type' => 'textarea',
        'entry' => '1894067623',
        'label' => '我還有想討論的想法 / 我的顧慮是 ?',
        'desc' => '',
        'placeholder' => '',
        'required' => false,
    ],
];
?>

<div class="contact-form" data-contact-form>
    <form class="contact-form__form" action="<?= esc_url( $form_action ) ?>" method="POST" novalidate data-contact-form-el>
        <p class="contact-form__intro">您好，請填寫您的需求，我們會再與您聯繫，提供協助。<br>如果不確定自己的網站經營方向，歡迎使用野薑「網路知識顧問」，我們提供一對一線上諮詢服務，請填寫需求表單預約，讓我們協助您聚焦專案需要與方向。</p>

        <?php foreach ( $questions as $i => $q ) :
            $name = $q['name'] ?? 'entry.' . $q['entry'];
            $id = 'cf-' . $q['entry'];
            $required = ! empty( $q['required'] );
            $is_group = in_array( $q['type'], [ 'radio', 'checkbox' ], true );
        ?>
        <?php if ( $is_group ) : ?>
        <fieldset class="contact-form__field contact-form__field--<?= esc_attr( $q['type'] ) ?>"<?= $required ? ' data-required-group' : '' ?>>
            <legend class="contact-form__label"><?= esc_html( $q['label'] ) ?><?php if ( $required ) : ?><span class="contact-form__required" aria-hidden="true">＊</span><?php endif; ?></legend>
            <?php if ( $q['desc'] ) : ?><p class="contact-form__desc"><?= esc_html( $q['desc'] ) ?></p><?php endif; ?>
            <div class="contact-form__options">
                <?php foreach ( $q['options'] as $j => $opt ) : $oid = $id . '-' . $j; ?>
                <label class="contact-form__option" for="<?= esc_attr( $oid ) ?>">
                    <input type="<?= esc_attr( $q['type'] ) ?>" id="<?= esc_attr( $oid ) ?>" name="<?= esc_attr( $name ) ?>" value="<?= esc_attr( trim( $opt ) ) ?>"<?= ( $required && 'radio' === $q['type'] ) ? ' required' : '' ?>>
                    <span><?= esc_html( trim( $opt ) ) ?></span>
                </label>
                <?php endforeach; ?>
                <?php if ( ! empty( $q['other'] ) ) : $oid = $id . '-other'; ?>
                <label class="contact-form__option contact-form__option--other" for="<?= esc_attr( $oid ) ?>">
                    <input type="<?= esc_attr( $q['type'] ) ?>" id="<?= esc_attr( $oid ) ?>" name="<?= esc_attr( $name ) ?>" value="__other_option__" data-other-toggle>
                    <span>其他：</span>
                    <input type="text" class="contact-form__other-input" name="<?= esc_attr( $name ) ?>.other_option_response" aria-label="其他需求" placeholder="請說明" data-other-input>
                </label>
                <?php endif; ?>
            </div>
            <p class="contact-form__error" data-group-error hidden>這題至少要選一項</p>
        </fieldset>
        <?php else : ?>
        <div class="contact-form__field contact-form__field--<?= esc_attr( $q['type'] ) ?>">
            <label class="contact-form__label" for="<?= esc_attr( $id ) ?>"><?= esc_html( $q['label'] ) ?><?php if ( $required ) : ?><span class="contact-form__required" aria-hidden="true">＊</span><?php endif; ?></label>
            <?php if ( $q['desc'] ) : ?><p class="contact-form__desc"><?= esc_html( $q['desc'] ) ?></p><?php endif; ?>
            <?php if ( 'textarea' === $q['type'] ) : ?>
            <textarea id="<?= esc_attr( $id ) ?>" name="<?= esc_attr( $name ) ?>" rows="5" placeholder="<?= esc_attr( $q['placeholder'] ) ?>"<?= $required ? ' required' : '' ?>></textarea>
            <?php else : ?>
            <input type="<?= 'email' === $q['type'] ? 'email' : 'text' ?>" id="<?= esc_attr( $id ) ?>" name="<?= esc_attr( $name ) ?>" placeholder="<?= esc_attr( $q['placeholder'] ) ?>"<?= 'email' === $q['type'] ? ' autocomplete="email" inputmode="email"' : '' ?><?= $required ? ' required' : '' ?>>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>

        <?php // 蜜罐：人看不到也填不到，機器人填了就不送 ?>
        <div class="contact-form__hp" aria-hidden="true">
            <label for="cf-website">Website</label>
            <input type="text" id="cf-website" name="website" tabindex="-1" autocomplete="off" data-honeypot>
        </div>

        <input type="hidden" name="fvv" value="1">
        <input type="hidden" name="pageHistory" value="0">

        <div class="contact-form__actions">
            <button type="submit" class="btn btn-gold contact-form__submit" data-submit>
                <span data-submit-label>送出諮詢</span>
            </button>
            <p class="contact-form__note"><span class="contact-form__required">＊</span>為必填。送出後我們會在 3 工作日內與您聯繫。</p>
        </div>
    </form>

    <div class="contact-form__result contact-form__result--success" data-result-success hidden tabindex="-1">
        <h2 class="contact-form__result-title">我們已經收到您的需求</h2>
        <p>謝謝您！野薑會在您方便的時段主動與您聯繫。<br>如果想先聊聊，也歡迎直接透過 <a href="https://lin.ee/u7j7mWJ" target="_blank" rel="noopener noreferrer">LINE</a> 找我們。</p>
    </div>

    <div class="contact-form__result contact-form__result--error" data-result-error hidden tabindex="-1">
        <h2 class="contact-form__result-title">送出時遇到問題</h2>
        <p>可能是網路暫時不穩，請稍後再試一次。<br>或改用 <a href="<?= esc_url( $form_viewform ) ?>" target="_blank" rel="noopener noreferrer">原始表單</a> 填寫，一樣會送到我們這裡。</p>
    </div>
</div>
