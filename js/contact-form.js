// 聯絡頁諮詢表單：攔截 submit，在瀏覽器端驗證後直接 POST 到 Google 表單的 formResponse。
// 結構見 template-parts/content/contact-form.php；樣式見 sass/contact-form.sass。
// Google 的回應是 opaque（no-cors），只能確認「請求有送出」；欄位是否被接受靠前端驗證把關。

export function initContactForm() {
    const root = document.querySelector('[data-contact-form]');
    if (!root) return;

    const form = root.querySelector('[data-contact-form-el]');
    const submitBtn = form.querySelector('[data-submit]');
    const submitLabel = form.querySelector('[data-submit-label]');
    const successBox = root.querySelector('[data-result-success]');
    const errorBox = root.querySelector('[data-result-error]');
    const honeypot = form.querySelector('[data-honeypot]');
    const defaultLabel = submitLabel.textContent;

    // 「其他」選項：在文字欄打字自動勾起；取消勾選就清空文字欄
    const otherPairs = [];
    form.querySelectorAll('[data-other-toggle]').forEach((toggle) => {
        const label = toggle.closest('label');
        const input = label.querySelector('[data-other-input]');
        otherPairs.push({ toggle, input });
        toggle.addEventListener('change', () => {
            input.required = toggle.checked;
            if (!toggle.checked) {
                input.value = '';
                input.setCustomValidity('');
            }
        });
        const check = () => {
            if (!toggle.checked) {
                toggle.checked = true;
                toggle.dispatchEvent(new Event('change', { bubbles: true }));
            }
        };
        input.addEventListener('focus', check);
        input.addEventListener('input', check);
    });

    // 必填的複選題：至少要勾一項（HTML 的 required 對 checkbox 群組無效，自己檢查）
    const requiredGroups = Array.from(form.querySelectorAll('[data-required-group]'));
    const groupIsValid = (group) => group.querySelector('input[type="checkbox"]:checked, input[type="radio"]:checked') !== null;
    const showGroupError = (group, show) => {
        const msg = group.querySelector('[data-group-error]');
        group.classList.toggle('is-invalid', show);
        if (msg) msg.hidden = !show;
    };
    requiredGroups.forEach((group) => {
        group.addEventListener('change', () => {
            if (groupIsValid(group)) showGroupError(group, false);
        });
    });

    // 單一欄位出錯時標記，讓 CSS 顯示紅框；修好就清掉
    form.addEventListener('invalid', (e) => {
        e.target.closest('.contact-form__field')?.classList.add('is-invalid');
    }, true);
    form.addEventListener('input', (e) => {
        if (e.target.checkValidity()) {
            e.target.closest('.contact-form__field')?.classList.remove('is-invalid');
        }
    });

    const validate = () => {
        let firstInvalid = null;

        requiredGroups.forEach((group) => {
            const ok = groupIsValid(group);
            showGroupError(group, !ok);
            if (!ok && !firstInvalid) firstInvalid = group;
        });

        if (!form.checkValidity()) {
            const el = form.querySelector(':invalid');
            if (el && (!firstInvalid || el.compareDocumentPosition(firstInvalid) & Node.DOCUMENT_POSITION_FOLLOWING)) {
                firstInvalid = el;
            }
        }

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const focusTarget = firstInvalid.matches('input, textarea')
                ? firstInvalid
                : firstInvalid.querySelector('input');
            if (focusTarget) {
                focusTarget.focus({ preventScroll: true });
                if (firstInvalid.matches('input, textarea')) firstInvalid.reportValidity();
            }
            return false;
        }
        return true;
    };

    const setLoading = (loading) => {
        submitBtn.disabled = loading;
        submitBtn.classList.toggle('is-loading', loading);
        submitLabel.textContent = loading ? '送出中…' : defaultLabel;
    };

    const showResult = (box) => {
        form.hidden = true;
        box.hidden = false;
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
        box.focus({ preventScroll: true });
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (honeypot && honeypot.value) return; // 機器人
        if (!validate()) return;

        setLoading(true);
        try {
            const body = new URLSearchParams(new FormData(form));
            body.delete('website');
            otherPairs.forEach(({ toggle, input }) => {
                if (!toggle.checked) body.delete(input.name);
            });
            await fetch(form.action, {
                method: 'POST',
                mode: 'no-cors',
                body,
            });
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({ event: 'contact_form_submit', form_name: 'contact_consult' });
            showResult(successBox);
        } catch (err) {
            console.error('contact form submit failed', err);
            setLoading(false);
            errorBox.hidden = false;
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
}
