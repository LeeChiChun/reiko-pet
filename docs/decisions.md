# Decisions Log

- **2026-05-16 02:00** | 無專案 .md 文件，以現有程式碼為基準執行六方向升級
  - 背景：README.md 是 Laravel 預設內容，沒有專案 SRS 文件
  - 決定：直接以程式碼現況為基準，補缺漏
  - 理由：用戶指令明確，不需要 SRS 文件也能執行升級
  - 影響：方向 1 改為「建立架構文件」

- **2026-05-16 02:00** | 後台路徑改為 /manage-panel
  - 背景：現有路徑 /admin 太常見，容易被掃描攻擊
  - 決定：路由 prefix 改為 manage-panel，相關 route name 仍用 admin.*
  - 理由：用戶明確要求，route name 不改可減少 Blade 修改量
  - 影響：所有 admin.* route 的 URL 前綴變更，middleware redirect 需更新

- **2026-05-16 02:00** | 2FA 只套用於 Admin 和 Groomer，Customer 不強制
  - 背景：用戶要求「管理員和美容師登入加入雙重驗證」
  - 決定：Customer 登入不加 2FA
  - 理由：用戶明確指定對象
  - 影響：2FA middleware 只掛在 admin 和 groomer 路由群組

- **2026-05-16 02:00** | 使用 pragmarx/google2fa-laravel 套件
  - 背景：用戶明確推薦此套件
  - 決定：採用，搭配 bacon/bacon-qr-code 生成 QR code
  - 理由：用戶指定
  - 影響：需 composer install

- **2026-05-16 02:00** | SESSION_LIFETIME 改為 30 分鐘
  - 背景：現有設定 120 分鐘，用戶要求 30 分鐘閒置登出
  - 決定：SESSION_LIFETIME=30，並加入 middleware 主動驗證 last_activity
  - 理由：Laravel session lifetime 設定搭配 custom middleware 更可靠
  - 影響：.env 和 config/session.php 都需更新
