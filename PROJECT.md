# 禮寵 Reiko Pet — PROJECT.md

## 專案概覽
| 項目 | 內容 |
|------|------|
| 平台 | Laravel 12 + Vite + Tailwind CSS + Alpine.js |
| 路徑 | ~/Desktop/寵物美容預約系統/app/ |
| 啟動指令 | `php artisan serve` |
| 建置指令 | `env -u NODE_OPTIONS /Users/lee/.nvm/versions/node/v24.14.1/bin/npm run build` |
| 資料庫 | SQLite（database/database.sqlite） |

## 專案結構
```
app/
├── app/
│   ├── Http/Controllers/
│   │   ├── CheckoutController.php    # 購物車→結帳、寵物住宿→結帳
│   │   ├── AccommodationController.php # 住宿頁面、訂房邏輯
│   │   ├── ShopController.php        # 商城商品、購物車操作
│   │   ├── SurveyController.php      # 滿意度問卷
│   │   └── Admin/                    # 後台各管理控制器
│   ├── Models/
│   │   ├── Product.php               # stock 欄位
│   │   ├── Pet.php                   # 寵物資料 (name/type/weight)
│   │   ├── AccommodationReservation.php
│   │   ├── ShopOrder.php
│   │   ├── SiteSetting.php           # get(key,default) / set(key,value)
│   │   ├── Coupon.php / CouponUsage.php
│   │   └── ...
│   └── Services/
│       └── CartService.php           # Session 購物車服務
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php         # 前台 layout
│   │   │   └── admin.blade.php       # 後台 layout（含「前往前台」按鈕）
│   │   ├── accommodation.blade.php   # 住宿頁面（含預約表單）
│   │   ├── about.blade.php           # 關於我們（含滿意度問卷）
│   │   ├── checkout.blade.php        # 結帳頁
│   │   ├── shop/
│   │   │   ├── index.blade.php
│   │   │   └── cart.blade.php        # 購物車（含庫存錯誤提示）
│   │   ├── auth/
│   │   │   ├── register.blade.php    # 含電話 pattern 驗證、密碼 minlength=8
│   │   │   └── login.blade.php
│   │   └── admin/
│   │       ├── cms.blade.php         # stat_pets/stores/satisfaction CMS 編輯
│   │       └── ...
│   └── css/ js/
├── routes/web.php
├── database/
│   ├── migrations/
│   └── database.sqlite
└── public/build/                     # Vite 編譯輸出
```

## 各檔案職責
| 檔案 | 職責 |
|------|------|
| CartService.php | 購物車 session 管理，add() 儲存 image 欄位 |
| CheckoutController.php | fromCart() 庫存驗證；pay() 內扣庫存；住宿建立訂單 |
| accommodation.blade.php | 住宿頁面、房型選擇、寵物自動填入、體重篩選、前端驗證 |
| admin.blade.php | 後台 sidebar layout，含「前往前台」連結 |
| SiteSetting.php | CMS 設定鍵值對（stat_pets、stat_stores 等） |

## 已完成功能
- [x] 全站狀態中文化：AppointmentStatus enum label() / color() 方法，前後台統一顯示（完成於 2026-05-27）
- [x] 前台 navbar 加入「管理後台」按鈕（admin/groomer 才顯示，桌機+手機）（完成於 2026-05-27）
- [x] 移除所有 `password.confirm` middleware（原先 appointments.status / members.destroy 有此問題）（完成於 2026-05-27）
- [x] 會員管理統計修正：`withCount(['pets','appointments'])` 正確顯示預約數與寵物數（完成於 2026-05-27）
- [x] 後台側欄重新分類：首頁管理/寵物美容/寵物住宿/線上商城/寵物專欄/關於我們/人事管理（完成於 2026-05-27）
- [x] 後台 AJAX 導覽：fetch + DOMParser + history.pushState，不重整頁面（完成於 2026-05-27）
- [x] 住宿/訂單管理：建立空白佔位頁（placeholder.blade.php）（完成於 2026-05-27）
- [x] 啟用狀態 Toggle 按鈕：services/addons/stores/products/announcements/promotions（完成於 2026-05-27）
- [x] 編輯新增分離：9個後台頁面（services/addons/stores/groomers/articles/flyers/products/announcements/promotions）改為 inline edit row，新增表單保持乾淨（完成於 2026-05-27）
- [x] 後台 sidebar 加入「前往前台」按鈕（完成於 2026-05-25）
- [x] 顧客滿意度問卷顯示（seeded 4 道問題，完成於 2026-05-25）
- [x] stat_pets 改為 DB 動態計數（Appointment where status=completed）（完成於 2026-05-26）
- [x] 首頁 DM 傳單：1張置中、2張雙欄、3張+三欄自適應（完成於 2026-05-26）
- [x] 關於我們問卷：改為橫式兩欄佈局，文字題自動 col-span-2（完成於 2026-05-26）
- [x] 會員中心個人資料：移除重複的「我的寵物快速瀏覽」區塊（完成於 2026-05-26）
- [x] 購物車商品圖片顯示（CartService.add() 儲存 image、cart.blade.php 顯示）（完成於 2026-05-25）
- [x] 商品庫存扣除：結帳前驗證庫存、付款後扣除（完成於 2026-05-25）
- [x] 住宿預約：寵物選擇器自動填入 pet_name/pet_type/weight（完成於 2026-05-25）
- [x] 全站表單驗證：電話 09+10 碼 pattern、密碼 minlength=8（完成於 2026-05-25）
- [x] Task 1：後台住宿房型管理 CRUD+Toggle+inline edit，前台動態從 DB 載入（完成於 2026-05-27）
- [x] Task 2：後台住宿預約管理，5 狀態系統（中文標籤），AccommodationReservationStatus enum（完成於 2026-05-27）
- [x] Task 3：後台商城訂單管理，ShopOrderStatus enum，admin/orders.blade.php，member orders 狀態 badge 改用 enum（完成於 2026-05-28）
- [x] Task 4：付款頁面統一架構—三個來源（美容/住宿/商城）共用 checkout.blade.php；商城多「貨到付款」選項（COD=pending 狀態）；美容預約 step5 改為確認頁轉向統一結帳（完成於 2026-05-28）
- [x] Task 5：優惠券系統重構—visibility（public/member/personal）欄位、assigned_user_id；Promotion 綁定 Coupon（coupon_id）、前台卡片顯示優惠碼、詳情頁可複製；結帳頁加入可用優惠券快選清單；首頁/促銷列表 CTA 連向詳情頁（完成於 2026-05-28）—三個來源（美容/住宿/商城）共用 checkout.blade.php；商城多「貨到付款」選項（COD=pending 狀態）；美容預約 step5 改為確認頁轉向統一結帳（完成於 2026-05-28）
- [x] 後台全面測試與整理（完成於 2026-05-26）
  - 修復：預約管理 PUT status route 誤加 `password.confirm` middleware → 405 bug
  - 修復：coupons/survey/survey_responses 缺少 `@section('page-title')` → header 顯示「管理後台」
  - 修復：cms/coupons/survey/flyers/announcements/promotions 與 admin layout 重複顯示 flash 訊息

- [x] 後台側欄內容管理重新分配：住宿內容管理獨立頁（admin/accommodation_cms）移入寵物住宿群組；關於我們管理→「內容管理」；cms.blade.php 移除住宿區塊；首頁管理只保留首頁內容（完成於 2026-05-28）
- [x] 後台管理4項優化：美容預約狀態標籤過濾修正（query param + eager load）；全後台圖片上傳建議尺寸提示；@push scripts 改為 inline 修復第一次進入編輯按鈕無效問題（完成於 2026-05-28）

- [x] Task 6：住宿預約—預設選第一隻寵物（old fallback 第一 pet id）、選項改「手動輸入」（完成於 2026-05-28）
- [x] Task 7：儀表板統計重排（待確認→商品→會員→總預約）；優惠券從人事管理移至首頁管理側欄（完成於 2026-05-28）
- [x] Task 9：關於我們內容管理拆分為獨立頁（admin/about）；側欄關於我們群組改連 admin.about；cms.blade.php 移除該區塊（完成於 2026-05-28）
- [x] 全站程式碼優化：提取 member nav tabs 為 `_tabs.blade.php` partial（8 個頁面共用）；修復儀表板 `$appt->pet_name` → `$appt->pet->name`；AdminController dashboard 補 `groomer` eager load；CouponController visibility check 移至 `isValid()` 前以給出精確錯誤訊息；MemberController accommodation 補 `with('room')` + 改用 `roomName()`；刪除孤立 placeholder.blade.php（完成於 2026-05-28）
- [x] 結帳頁五項修復（完成於 2026-05-29）
  - 修復：美容預約結帳頁 500 crash（meta 含 addon_ids 陣列 → htmlspecialchars TypeError）
  - 重構：checkout.blade.php 日式精品風格重排版，三個來源各自顯示對應標籤
  - 修復：住宿訂房電話欄位支援市話格式（pattern 改為手機 + 市話雙格式）
  - 修復：結帳頁優惠券輸入框溢出 + 所有文字中文化（% 折扣、折 NT$X、月/年等）
  - 修復：商城訂單成立頁顯示空白訂單號（session key 不一致：last_order_id vs last_order_no）

- [x] 圖片整合與多項修復（完成於 2026-06-03）
  - 整合 5 張 CMS 圖片：hero_image、philosophy_image、about_founder_image、flyer_image（存入 storage/app/public/cms/ 與 flyers/，更新 site_settings + flyers DB）
  - 修復：首頁 Hero 背景圖未顯示（`$heroSettings['image']` 從未讀取）→ 加入帶暗色遮罩的背景圖 overlay
  - 修復：services.blade.php 缺少 `dog`/`cat` 全套服務區塊（ID 7 NT$2400、ID 10 NT$1600）→ 新增「全套服務」段落與導覽錨點
  - 修復：step5 付款按鈕版面歪斜（form 在 grid 外且無 margin）→ 重構為 form 包裹 grid，前往付款進右欄，上一步置中
  - 修復：首頁 OUR PHILOSOPHY 圖片偏右（max-w-sm 限制）→ 移除 max-w-sm，圖片填滿欄寬

- [x] 首頁優惠券推薦區塊（完成於 2026-06-03）
  - 新增 `show_on_home` boolean 欄位至 coupons 資料表（migration 已套用）
  - Coupon model 新增 `forHome($userId)` 靜態方法（含資格/有效期/次數篩選，共用邏輯）
  - Coupon model 新增 `discountLabel()`、`scopeLabel()`、`scopeUrl()` helper methods
  - 首頁新增「優惠券推薦」section（4 欄 grid，依 show_on_home=true 顯示）
  - 會員中心「近期優惠」改為讀取 Coupon::forHome()，顯示折扣描述與立即使用連結
  - 後台優惠券管理：新增「首頁推薦」欄位（表頭、列狀態、inline edit 勾選框）

## 尚未完成 / 已知問題
- [ ] 門市管理：水星門市、木星門市地址/電話為「XXX」待補
- [ ] 會員管理 `members.destroy` 路由存在但無 UI 入口（孤立路由）

## 常用指令
```bash
# 啟動開發伺服器
cd ~/Desktop/寵物美容預約系統/app && php artisan serve

# 建置前端
cd ~/Desktop/寵物美容預約系統/app && env -u NODE_OPTIONS /Users/lee/.nvm/versions/node/v24.14.1/bin/npm run build

# 執行 migration
php artisan migrate

# Tinker
php artisan tinker
```
