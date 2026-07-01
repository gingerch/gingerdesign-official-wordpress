# 野薑設計 Typography Spec v2

**日期**：2026-07-01
**適用**：一般中英混排網站的 baseline typography
**上線示範**：[gingerdesign.com.tw](https://gingerdesign.com.tw)

---

## 規範表

| 元素 | font-size | font-weight | line-height | letter-spacing |
|---|---|---|---|---|
| H1 | 2.25rem (36px) | Bold | 130% (1.3) | 10% (0.1em) |
| H2 | 1.75rem (28px) | Bold | 130% | 10% |
| H3 | 1.375rem (22px) | Bold | 130% | 10% |
| H4 | 1.25rem (20px) | Bold | 150% (1.5) | 10% |
| H5 | 1.125rem (18px) | Bold | 150% | 10% |
| H6 | 1rem (16px) | Bold | 185% (1.85) | 10% |
| **p** | 1rem (16px) | Regular | **200% (2)** | **5% (0.05em)** |
| small | 0.875rem (14px) | Regular | 185% | 10% |

---

## 設計理由

- **標題字距 10%、內文字距 5%**：中文標題適合較鬆的視覺節奏（呼吸感、標題辨識度），內文太鬆則會拖慢閱讀。參考政大 CID 學系網（全站 10%）與 The Reporter（內文近 3%）後折中定案。
- **內文行距 200%**：中文段落需要比英文更寬的行距才好讀（英文一般 1.5–1.75），200% 是介於 Reporter（2.11）與一般 WP theme（1.5）之間的閱讀密度。
- **標題 line-height 分三級**：H1–H3 用 130%（大字不需太多間距）、H4–H5 用 150%（中間）、H6 用 185%（跟內文一致，讓小標題融入段落節奏）。
- **字型**：中文 `Noto Sans TC`、英文 `Montserrat`（可依站台調整）。字重統一 Bold（標題）／Regular（內文）。

---

## 對比參考

| 站台 | 內文字距 | 內文行距 |
|---|---|---|
| 野薑（本規範） | 5% | 200% |
| 政大 CID 系網 | 10% | 200% |
| The Reporter | ~3% (0.5px) | 211% |

---

## 檔案

- **[`_typography.sass`](./_typography.sass)** — SASS partial，於 `style.sass` `@import typography`
- **[`typography.css`](./typography.css)** — 壓縮版純 CSS，非 SASS 專案直接 include

---

## 移植到其他站台

### SASS 專案

1. 複製 `_typography.sass` 到 `sass/` 資料夾
2. 在 `style.sass` 加入：
   ```sass
   @import typography
   ```
3. 確認 `body` 沒有 `line-height: 1.5` 或 `letter-spacing` 蓋掉這些預設；若有，把 body 的字距／行距移除（讓 h1-h6/p 各自的值生效）。

### 非 SASS 專案（純 HTML、React、Next.js 等）

直接 `<link rel="stylesheet" href="typography.css">`，或把內容貼進全域 CSS。

### 覆蓋規則注意

- p 直接命中 `p` selector（specificity 0,0,1），會勝過父層 `.container { letter-spacing: 0.1em }` 這種繼承。
- 若站台有 section-specific 大字（hero 40/48px 等），保留該區塊自己的 font-size / line-height；本規範只是 baseline。

---

## 來源

- **Figma**：檔案 `09jBPgipRffxqfg1TwKryB` node `4455:2`（標準品範本 - 診所用 / 版型開發）
- **野薑官網 commit 記錄**（gingerdesign-official-wordpress）：
  - `de24721` 全站 h1–h6/p/small 基準
  - `812efaf` H1–H6/p/small 明寫 letter-spacing
  - `54fba2a` 全站字距 5% → 10%
  - `9dec0d2` p 行距 185% → 200%
  - `b7b4121` p 字距 10% → 5%
