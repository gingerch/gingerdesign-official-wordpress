# Cloudflare Cache Rule 設定指南

> **狀態：2026-07-08 已完成**（用 Rulesets API 設定，非儀表板手動；規格同本文件）。
> Ruleset `b84f2bac70634bd5abc83c13f44a01c8` / rule 「Cache HTML pages」。
> API token 在本機 `~/.cloudflare/gingerdesign.env`。實測 HIT TTFB ~170-230ms。
> 本文件保留作為規格紀錄與 rollback 參考。

**目的**：把首頁與文章頁的 HTML cache 在 Cloudflare 邊緣，把 TTFB 從 ~550ms 打到 ~50-100ms（全球）。

**前置**：
- Origin 已裝 Cache Enabler（cache HIT <5ms）
- mu-plugin `ginger-cache-headers.php` 已補 `Cache-Control: public` 給匿名前台

---

## 步驟

### 1. 登入 Cloudflare 儀表板
到 [dash.cloudflare.com](https://dash.cloudflare.com) → 選 **gingerdesign.com.tw** 網域。

### 2. 建立 Cache Rule

左側選單 → **Caching → Cache Rules** → **Create rule**。

**Rule name**: `Cache HTML pages`

**When incoming requests match**（If）：
- Field: **Hostname** — Operator: **equals** — Value: `gingerdesign.com.tw`
- **AND**
- Field: **URI Path** — Operator: **does not start with** — Value: `/wp-admin`
- **AND**
- Field: **URI Path** — Operator: **does not start with** — Value: `/ho-tai`
- **AND**
- Field: **URI Path** — Operator: **does not start with** — Value: `/wp-login`
- **AND**
- Field: **URI Path** — Operator: **does not start with** — Value: `/wp-json`
- **AND**
- Field: **URI Query String** — Operator: **equals** — Value: `` (空字串，也就是無 query string)
- **AND**
- Field: **Cookie** — Operator: **does not contain** — Value: `wordpress_logged_in`
- **AND**
- Field: **Cookie** — Operator: **does not contain** — Value: `wp-postpass`

（介面上按「AND」加條件；「URI Query String equals ''」用來排除搜尋/預覽等有 query 的頁面）

**Then**：
- **Cache eligibility**: **Eligible for cache**
- **Edge TTL**:
  - Select **Override origin**
  - Value: `2 hours`
- **Browser TTL**: **Respect origin** （讓 origin 送的 `max-age=600` 生效）

### 3. Save + Deploy

按 **Deploy**。規則即時生效。

---

## 驗證方法

```bash
# 第一次：MISS
curl -sI "https://gingerdesign.com.tw/" | grep -Ei "cf-cache-status|cache-control"
# 應該看到：cf-cache-status: MISS

# 第二次：HIT
curl -sI "https://gingerdesign.com.tw/" | grep -Ei "cf-cache-status|cache-control"
# 應該看到：cf-cache-status: HIT
```

TTFB 應該從 ~550ms 降到 ~50-100ms。

---

## 內容更新時怎麼辦？

**發文 / 改文章的 cache 刷新有兩層**：

1. **Origin (Cache Enabler)**：發表/更新文章時自動清 origin cache（已設 `clear_site_cache_on_saved_post: 1`）。
2. **Cloudflare edge**：不會自動清。兩個選項：
   - **A（推薦，簡單）**：接受 2 小時 TTL 過期時間差，反正每篇文章發表後 2h 內全球 CF 邊緣會自動 refresh 新版
   - **B（急件用）**：手動去 Cloudflare 儀表板 **Caching → Configuration → Purge Cache → Custom Purge**，貼上該文章 URL 清掉

---

## 想更進一步：CF API 自動 purge

如果不想手動 purge，可在 WordPress 裝 **Super Page Cache – Cloudflare Cache** 或
自己寫 mu-plugin，在 `save_post` hook 打 CF API 清 cache。需要：

- Cloudflare API Token（Zone-Cache Purge 權限）
- Zone ID（在 CF Overview 頁右下）

這步驟不急，先把 Cache Rule 開起來看效果，之後想自動化再說。

---

## 影響範圍

**會被 CF 邊緣 cache 的**：
- 首頁 `/`
- 各文章頁 `/xxx/`
- 靜態頁 `/about/`、`/contact/`

**不會被 cache 的**：
- `/ho-tai`、`/wp-admin` — 後台
- `/wp-json/*` — REST API
- `/?s=xxx` — 搜尋（有 query string）
- 已登入使用者（cookie 檢查）
- POST 請求（CF 預設）

---

## Rollback

如果哪天出現詭異的頁面 cache 錯亂問題，直接到 Cache Rules 把這條規則 **Disable**（不用刪），
邊緣立即停止 cache HTML。Origin 的 Cache Enabler 不受影響、繼續運作。
