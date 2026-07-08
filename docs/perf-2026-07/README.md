# 效能優化 2026-07

**目標**：把野薑官網（gingerdesign.com.tw）從首頁 2.9MB / TTFB 670ms
降到「一般網速視覺可接受」。

## 已完成

### 1. 圖片優化（2026-07-02，commit 46ebab1 + 717a31c）
- 裝 **Converter for Media** WordPress 外掛（自動 WebP 轉檔、htaccess Accept rewrite）。
- 批次跑 `wp webp-converter regenerate` 產出 **3195 個 WebP 檔**。
  途中因 8751×3751 圖 crash → 已縮到 2560×1097 並 resume 完成。
- 主題 `functions.php` 加 ob_start filter：
  - 所有 `<img>` 自動加 `loading="lazy"` + `decoding="async"`（首屏 hero 排除）
  - `uploads/` 內 `.png/.jpg` 若對應 WebP 檔存在就包 `<picture><source type="image/webp">`
    （避開 Cloudflare 不完整支援 `Vary: Accept` 的問題，不同格式 = 不同 URL）
- 主題 `index.php` hero SVG 加 `data-no-lazy fetchpriority="high"`。

**成果**：首屏 top-5 圖檔 2205 KB → 852 KB（**-61%**）。

### 2. 原站 HTML cache（2026-07-02）
- 裝 **Cache Enabler** WordPress 外掛：wp-cli 安裝 + 設定：
  - TTL 24 小時
  - 發文/發評論時自動清 cache
  - 壓縮存 br/gzip
  - Bypass: `/ho-tai`（後台登入頁）
- `wp-config.php` 加 `define('WP_CACHE', true)`（備份為 `.bak.{時間戳}`）
- mu-plugin `ginger-cache-headers.php`（見同資料夾）：
  - 匿名前台 GET：`Cache-Control: public, s-maxage=3600, max-age=600`
  - 後台/預覽/AJAX/POST/query-string：`private, no-cache`

**成果**：origin cache HIT TTFB **2.7ms**（原 100ms）。

### 3. CF edge cache HTML（**2026-07-08 已設定完成，走 API**）
- 規格照 [CF-cache-rule.md](./CF-cache-rule.md)，改用 Cloudflare Rulesets API 建立
  （zone `23973be1b4da1a0527d64ecc846ca1ba`，ruleset `b84f2bac70634bd5abc83c13f44a01c8`，
  phase `http_request_cache_settings`，rule「Cache HTML pages」）。
- API token（Zone Read + Cache Rules Edit + Cache Purge）存在本機
  `~/.cloudflare/gingerdesign.env`（`CF_API_TOKEN` + `ZONE_ID`）。

**成果**（2026-07-08 實測）：
- 首頁/文章頁 MISS → HIT 正常；HIT 後 TTFB **~170-230ms**（原 ~550ms，-60%+）。
- 排除規則驗證通過：`/ho-tai`、`/wp-json`、`?s=` 搜尋皆 DYNAMIC 不快取。

### 4. Bootstrap purge + Google Fonts 收斂（2026-07-08，commit 1e753d5）
- **Bootstrap**：CDN 全量 163KB → PurgeCSS 本站專用子集 **10KB**，存主題 `css/bootstrap.min.css`
  （header.php 以 filemtime 版本號載入，少一個第三方 render-blocking 請求）。
- **Google Fonts**：砍掉全站未使用的 Roboto 400/700 與 Montserrat 200，
  只留 Montserrat 400/700 + Noto Sans TC 400/700；載入方式從 style.css `@import`（串行）
  改成 header.php `<link>` + preconnect（並行）。
- **成果**：observedLoad ~1.3s → **~0.66s**；Bootstrap 從 Lighthouse unused-css 名單消失；
  server response 90ms（CF cache rule 生效後）。Lab score 55 持平（受模擬 4G 節流噪音影響）。

**⚠️ 新增 Bootstrap class 時要重跑 purge**，流程：
```bash
# 1. 抓全站頁面 HTML（從 sitemap 收 URL）+ 下載全量 bootstrap
curl -s https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css -o /tmp/bs.css
# 2. 以「線上全站 HTML + 主題 PHP + js/main.js」為掃描來源跑 PurgeCSS
npx purgecss --css /tmp/bs.css --content '<抓下來的html>/*.html' '<主題>/**/*.php' '<主題>/js/main.js' -o css/
# 3. 驗證：比對頁面上實際 class ∩ bootstrap class ⊆ purged class，然後 commit
```
（2026-07-08 那次的驗證腳本邏輯：抓 170 頁 sitemap 全量 HTML，43 個使用中 class 全保留。）

## 待處理

- **AVIF 圖檔（可選）**：目前只轉 WebP。若要再壓，可跑 `wp option update webpc_settings '{"output_formats":["webp","avif"]}' --format=json` 再 regenerate。多轉 30-60 分鐘、多省 20-30% 檔案大小。
- **CF API 自動 purge**（可選）：發文時自動打 CF API 清 edge cache，取代 2h TTL 等待。
- **清 2022 年老舊素材**（141MB uploads/2022 大多可能沒在用）——風險高、要一張張確認。

## 檔案地圖

| 檔案 | 說明 |
|---|---|
| [`README.md`](./README.md) | 本檔案 |
| [`CF-cache-rule.md`](./CF-cache-rule.md) | Cloudflare Cache Rule 逐步設定指南 |
| [`ginger-cache-headers.php`](./ginger-cache-headers.php) | mu-plugin 快照（實際檔在 Lightsail `/var/www/html/wp-content/mu-plugins/`，遷移時記得帶） |
