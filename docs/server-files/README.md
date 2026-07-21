# server-files — 不在主題內、但站台需要的伺服器檔案

這裡放的是**跑在伺服器上、但不屬於主題目錄**的檔案備份。
主題 repo 會被 clone 到新主機，這些檔案跟著一起帶走，重建站台時才不會漏。

> ⚠️ **本 repo 是公開的**（public）。放進來之前務必確認檔案不含連線資訊、金鑰、
> 後台隱藏路徑、資料庫憑證。含機密的東西放本機的 `SECRETS.local.md`，不要放這裡。

---

## 1. mu-plugins（必須手動放回）

部署位置：`/var/www/html/wp-content/mu-plugins/`
（mu-plugin = must-use，放進去就自動啟用，不需在後台啟用。）

| 檔案 | 作用 |
|---|---|
| `ginger-schema.php` | 首頁輸出 Organization + ProfessionalService 的 JSON-LD 結構化資料（含公司地址、營業時間、社群連結）。手寫，**不在主題目錄內**。 |
| `../perf-2026-07/ginger-cache-headers.php` | 對匿名前台 GET 送 `Cache-Control: public, s-maxage=3600, max-age=600`，後台/預覽/AJAX/REST/POST/query-string 則 `no-cache`。**快照維持在 `docs/perf-2026-07/`**（該目錄是效能優化的完整紀錄，不重複放一份以免兩邊不同步）。 |

還原方式：

```bash
# 主題 clone 完成後
sudo cp <theme>/docs/server-files/ginger-schema.php            /var/www/html/wp-content/mu-plugins/
sudo cp <theme>/docs/perf-2026-07/ginger-cache-headers.php     /var/www/html/wp-content/mu-plugins/
sudo chown -R www-data:www-data /var/www/html/wp-content/mu-plugins/
```

## 2. wp-config.php 需要的設定（**不備份整個檔案**）

`wp-config.php` 含資料庫帳密與鹽值，**絕不進版控**。遷移時只要確認這一行存在：

```php
define( 'WP_CACHE', true );   // Cache Enabler 靜態頁快取所需；目前在 /var/www/wp-config.php 第 105 行
```

另外本站 wp-config 有動態網址機制（`WP_HOME`/`WP_SITEURL` = `'http://' . $_SERVER['HTTP_HOST']`），
siteurl 會自動跟訪問網域走——換主機時不需改 siteurl。

## 3. 其他遷移時要記得的事

- **Cloudflare Cache Rule** 規格與 rollback 方式見 `../perf-2026-07/CF-cache-rule.md`。
- 切換主機只需改 Cloudflare 上 A 記錄的 origin IP（DNS/SSL 都在 CF，SSL 模式 Full 非 strict，origin 自簽即可）。
- 主機連線資訊、後台登入路徑、CF zone id 等**不在本 repo**，見專案本機的 `SECRETS.local.md`。
