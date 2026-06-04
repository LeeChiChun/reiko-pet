# 寵物美容預約系統 — 架構文件

**版本**: 2.0 (升級後)  
**更新**: 2026-05-16

---

## 技術棧

| 項目 | 技術 |
|------|------|
| 框架 | Laravel 13.x |
| 資料庫 | MySQL（SQLite for dev） |
| 前端 | Blade + Tailwind CSS via Vite |
| Auth | Laravel 內建 + 2FA (pragmarx/google2fa-laravel) |
| 快取/Session | Database driver |

---

## 子系統切割

```
┌─────────────────────────────────────────┐
│ Presentation Layer                       │
│ Blade Views + Layouts + Components       │
├─────────────────────────────────────────┤
│ Control Layer (Controllers)              │
│ BookingController / AdminController /    │
│ AuthController / MemberController /      │
│ GroomerController / ShopController /     │
│ TwoFactorController /                    │
│ PasswordConfirmController                │
├─────────────────────────────────────────┤
│ Service Layer (Mediator)                 │
│ BookingService / AppointmentService /    │
│ CartService / AuditLogService            │
├─────────────────────────────────────────┤
│ Contract Layer (Interfaces)              │
│ PaymentInterface / MailNotification /    │
│ SmsNotification / CloudStorage           │
│ (各有 Mock 實作，未來替換真實實作)         │
├─────────────────────────────────────────┤
│ Entity Layer (Models)                    │
│ User / Pet / Appointment /               │
│ AppointmentAddon / Service /             │
│ AddonService / Store / Article /         │
│ Product / AuditLog                       │
├─────────────────────────────────────────┤
│ Foundation Layer (Eloquent ORM + DB)     │
│ Migrations / Seeders                     │
└─────────────────────────────────────────┘
```

---

## 主要流程

### 預約流程
Step1（選寵物）→ Step2（選服務）→ Step3（選加值）→ Step4（選門市/時間）→ Step5（確認付款）→ Confirm

所有 session key 由 `config/booking.php` 管理：
- `max_slot_count = 3`（同時段同門市最多 3 個預約）
- `advance_days_min = 1`
- `advance_months_max = 2`

### 認證流程
1. 登入 → 帳號鎖定檢查 → 認證 → 失敗計數更新
2. Admin/Groomer 登入 → 2FA 設定（首次）or 驗證（已設定）
3. 所有登入路由加 throttle:login（5 次/15 分）

---

## 安全機制

| 機制 | 實作 |
|------|------|
| 密碼 bcrypt | User::$casts password=hashed, BCRYPT_ROUNDS=12 |
| 登入節流 | RateLimiter::for('login') 5次/15分 |
| 帳號鎖定 | login_failed_count ≥ 5 → locked 15 分 |
| Session timeout | SessionTimeout middleware, 30 分鐘 |
| 2FA | pragmarx/google2fa-laravel，Admin/Groomer 強制 |
| 後台路徑 | /manage-panel（非 /admin） |
| Audit log | AuditLogService → audit_logs 資料表 |
| 密碼再確認 | RequirePasswordConfirm middleware（刪除會員/修改預約狀態） |
| PII 遮蔽 | MaskHelper::phone() / ::email() 在列表顯示 |
| Resource 層 | UserResource / AppointmentResource / PetResource |

---

## 未實作功能（預留窗口）

| 功能 | Interface | Mock |
|------|-----------|------|
| 真實金流 | PaymentInterface | MockPayment |
| 真實 Email | MailNotificationInterface | MockMailNotification |
| 簡訊通知 | SmsNotificationInterface | MockSmsNotification |
| 雲端圖片上傳 | CloudStorageInterface | MockCloudStorage |

替換方式：在 `AppServiceProvider::register()` 改綁定目標即可，其他程式碼不需修改。

---

## 資料模型

```
users ─── pets (1:N)
users ─── appointments as customer (1:N)
users ─── appointments as groomer (1:N)
users ─── audit_logs (1:N)
appointments ─── appointment_addons (1:N)
appointment_addons ─── addon_services (N:1)
appointments ─── services (N:1)
appointments ─── stores (N:1)
appointments ─── pets (N:1)
```
