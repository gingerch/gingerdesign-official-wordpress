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

### 3. CF edge cache HTML（**待使用者手動設定**）
- 看 [CF-cache-rule.md](./CF-cache-rule.md) — Cloudflare 儀表板逐步指南。
- 預期：CF cache HIT TTFB 全球 ~50-100ms（原 550-670ms）。
- 完成後真實訪客感受第一次載入速度大幅改善。

## 待處理

- **CF Cache Rule 手動設定**（見上）——這是剩下最大效能提升點。
- **AVIF 圖檔（可選）**：目前只轉 WebP。若要再壓，可跑 `wp option update webpc_settings '{"output_formats":["webp","avif"]}' --format=json` 再 regenerate。多轉 30-60 分鐘、多省 20-30% 檔案大小。
- **CF API 自動 purge**（可選）：發文時自動打 CF API 清 edge cache，取代 2h TTL 等待。
- **清 2022 年老舊素材**（141MB uploads/2022 大多可能沒在用）——風險高、要一張張確認。

## 檔案地圖

| 檔案 | 說明 |
|---|---|
| [`README.md`](./README.md) | 本檔案 |
| [`CF-cache-rule.md`](./CF-cache-rule.md) | Cloudflare Cache Rule 逐步設定指南 |
| [`ginger-cache-headers.php`](./ginger-cache-headers.php) | mu-plugin 快照（實際檔在 Lightsail `/var/www/html/wp-content/mu-plugins/`，遷移時記得帶） |
