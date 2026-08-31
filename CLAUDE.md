# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> FoodScan — QR-code restaurant ordering & management platform (Laravel 12 + Vue 3 SPA).

---

## Development Commands

```bash
# First-time setup (install deps, generate key, migrate, build)
composer run setup

# Start full local dev environment (web server + Vite + queue + Pail log)
composer run dev

# Build assets for production
npm run build

# Run all tests
composer run test

# Run a specific test class
php artisan test --filter=OrderServiceTest

# Code style (Laravel Pint)
./vendor/bin/pint

# Reset and re-seed database
php artisan migrate:fresh --seed

# Reset order data only (demo/test environments)
php artisan fresh-order-seed
```

---

## Tech Stack

**Backend:** Laravel 12, Sanctum (bearer token auth), Spatie Permission v6 (RBAC), Spatie MediaLibrary v11 (file uploads), `dipokhalder/settings` (runtime key-value config), Maatwebsite Excel, DomPDF, OpenAI PHP client.

**Frontend:** Vue 3, Vuex 4, Vue Router 4, Vite 5, Bootstrap 5, Tailwind CSS 3, vue-i18n 9, Firebase SDK v9 (FCM push), Axios (store actions only), `vuex-persistedstate`.

**Payment gateways (22):** Stripe, PayPal, Razorpay, Mollie, Cashfree, Midtrans, MyFatoorah, iyzico, MercadoPago, Paytm, bKash, EasyPaisa, Flutterwave, Paystack, Skrill, Telr, Payfast, Pesapal, Senangpay, SSLCommerz, Myfatoorah, Credit.

**SMS:** Twilio, Vonage, Msg91, Clickatell, Telesign, Bulksms, Bulksmsbd, Twofactor.

**Key env vars:** `VITE_API_KEY` (required header for all API calls), `VITE_GOOGLE_MAP_KEY`, `VITE_HOST` (mirrors `APP_URL`). User-configurable values live in the settings DB, not `.env`.

---

## Architecture

### Request Flow

```
FormRequest → Controller (try/catch) → Service → Model → API Resource (JSON)
```

- **Controllers** (`app/Http/Controllers/`): Thin HTTP boundary only. Must implement `HasMiddleware`. Every action wrapped in `try/catch`; failures return HTTP 422. Extend `AdminController`, `FrontendController`, or base `Controller`.
- **Services** (`app/Services/`): All business logic and DB operations. 90+ service classes — one per domain. Injected via constructor. No HTTP awareness.
- **Models** (`app/Models/`): 41 models. Relations, casts, scopes only — no execution flow. File uploads use Spatie MediaLibrary (`HasMedia` + `InteractsWithMedia`).
- **API Resources** (`app/Http/Resources/`): 80+ resource classes. All JSON responses go through resources — never return raw Eloquent models.
- **Form Requests** (`app/Http/Requests/`): 60+ request classes. Validation rules only — never validate inside controllers.

### Error Handling (mandatory pattern)

```php
// Controller
try {
    $result = $this->service->execute($request);
    return new SuccessResource($result);
} catch (Exception $exception) {
    return response(['status' => false, 'message' => $exception->getMessage()], 422);
}

// Service — catch, log, rethrow with normalized message
try {
    return Branch::create($request->validated());
} catch (Exception $exception) {
    Log::info($exception->getMessage());
    throw new Exception(QueryExceptionLibrary::message($exception), 422);
}
```

- Services use `Log::info()` (not `Log::error()`) and rethrow via `QueryExceptionLibrary::message()` to normalize DB errors.
- Controllers catch and return 422. Successful `destroy` actions return **202**.
- Use `tap()` for chainable model updates: `return tap($model)->update($request->validated());`

### Route Groups (`routes/api.php`)

| Prefix | Auth | Purpose |
|--------|------|---------|
| `/api/auth/*` | public | Login, signup, OTP, forgot-password |
| `/api/profile/*` | `auth:sanctum` | User profile CRUD |
| `/api/admin/*` | `auth:sanctum` + `permission:*` | Full admin panel |
| `/api/frontend/*` | mixed | Customer catalog, ordering, account |
| `/api/table/*` | table token | QR dining table ordering |

### Middleware Stack

| Key | Responsibility |
|-----|----------------|
| `installed` | Verifies installer has run |
| `apiKey` | Validates `VITE_API_KEY` request header |
| `auth:sanctum` | Bearer token authentication |
| `localization` | Sets request locale from header |
| `permission:*` | Spatie RBAC gate |

### Multi-Tenancy

Branch-scoped models use `MultiTenantModelTrait`. `UserObserver` fires on `creating`/`updating` and calls `DefaultAccessModelTrait::setBranch()` to auto-assign `branch_id` from the authenticated user's context.

### Observers & Events

**UserObserver** (`app/Observers/UserObserver.php`) — registered in `EventServiceProvider`. Auto-sets `branch_id` via `DefaultAccessModelTrait` on every user create/update.

**Event-driven notifications** — 6 event/listener pairs covering order lifecycle:

```
Service dispatches event
  └─► Listener receives event
        └─► Builder Service (OrderGotMailNotificationBuilder, etc.)
              └─► Specialized Service (Mail / SmsManagerService / FirebaseService)
```

Builder classes: `OrderGotMailNotificationBuilder`, `OrderGotPushNotificationBuilder`, `OrderGotSmsNotificationBuilder`. Sending is gated by `SwitchBox::ON` — admins can disable individual channels.

### Vue Router

Routes split into ~22 module files in `resources/js/router/modules/`, composed in `router/index.js`. Global `beforeEach` checks auth and `to.meta.access` permission before every navigation.

---

## PHP / Laravel Conventions

- **Enums:** Use PHP-backed enums from `app/Enums/` (25 files: `OrderStatus`, `PaymentGateway`, `Role`, `Status`, `SwitchBox`, etc.). Never hardcode integers or strings.
- **Helpers:** Format all dates and money via `AppLibrary` statics: `::date()`, `::datetime()`, `::currencyAmountFormat()`, `::flatAmountFormat()`. Never call `env()` directly — use `config()`.
- **Settings:** Runtime config via `Settings::group('site')->get('site_default_branch')`. Groups: `site`, `company`, `order_setup`, `notification`, `otp`. Don't add `.env` keys for user-configurable values.
- **Database:** Never modify existing migrations — always create a new one. Eager-load (`with()`) all relations. Use foreign key constraints.
- **Media:** File uploads use Spatie MediaLibrary (`HasMedia` + `InteractsWithMedia`). Never store file paths manually.
- **Services:** Check all 90+ existing services before creating a new one. One class per domain — no duplicates.
- **Service filter pattern:** Every service listing method whitelists searchable columns in a protected `$filterArray` (or similarly named) property and applies them dynamically. Follow this pattern for any new `list()` method.
- **Route model binding:** Use `show(Branch $branch)` — not manual `findOrFail()` in controllers.
- **Permissions:** All admin write endpoints need a `permission:*` gate in `HasMiddleware`. Use `new Middleware('permission:settings', only: ['store', 'update', 'destroy'])`.
- **AI content generation:** `HasAiPrompt` trait (`app/Traits/HasAiPrompt.php`) builds structured LLM prompts for product names/descriptions. Use it on item services.

---

## Vue / Frontend Conventions

- **Options API:** For all page and layout components. Composition API only for shared composables.
- **Vuex store:** All API calls dispatch through Vuex actions. Read state only via Vuex getters. Never call Axios directly inside a component. 70+ store modules exist — check before adding one.
- **Vuex temp pattern:** Every module tracks editing state in `temp: { temp_id: null, isEditing: false }`. The `save` action checks `isEditing` to switch between `axios.post` and `axios.put`.
- **`appService.requestHandler(payload)`** — converts a filter/sort payload object into a query string. Always use it to build list URLs.
- **Loading state pattern:**
  ```js
  data() { return { loading: { isActive: false } } },
  async loadData() {
      this.loading.isActive = true;
      try { await this.$store.dispatch('module/fetch') }
      finally { this.loading.isActive = false; }
  }
  ```
  Wrap async template sections with `<LoadingComponent :props="loading" />`.
- **Translations:** All UI text via `$t('key')`. Nested keys in `resources/js/languages/en.json`. Never hardcode display strings.
- **JS Enums:** Mirror enums in `resources/js/enums/`. Use them for status comparisons — no magic numbers or strings.
- **CSS:** Bootstrap 5 utilities and `db-*` theme classes first. Tailwind for layout. Avoid ad-hoc inline styles.
- **Filenames:** PascalCase (e.g. `BranchShowComponent.vue`).
- **Vue Router:** New routes in the relevant module file under `resources/js/router/modules/`. Set `meta.access` for permission-guarded routes.

---

## Pluggable Systems

All integration systems follow the same **Abstract → Dispatcher → Concrete** pattern. Always route through the dispatcher — never instantiate a gateway class directly.

### AI Agents
- Abstract: `app/Services/AiAbstract.php`
- Dispatcher: `app/Services/AiService.php`
- Implementations: `app/Http/AiAgents/Agents/` (e.g. `Openai.php`)
- Active agent configured via the `site_default_ai_agent` settings key.

### Payment Gateways
- Abstract: `app/Services/PaymentAbstract.php`
- Dispatcher: `app/Services/PaymentManagerService.php`
- Implementations: `app/Http/PaymentGateways/Gateways/`
- To add: create class extending `PaymentAbstract`, add case to `app/Enums/PaymentGateway.php`, register in `PaymentManagerService`.

### SMS Gateways
- Abstract: `app/Services/SmsAbstract.php`
- Dispatcher: `app/Services/SmsManagerService.php`
- Implementations: `app/Http/SmsGateways/Gateways/`

---

## Order Domain

Three entry controllers all delegate to `OrderService` (`app/Services/OrderService.php`), which handles stock reduction, pricing, payment state, and status transitions.

| Type | Controller | Channel |
|------|-----------|---------|
| POS | `PosOrderController` | In-store register |
| Online | `OrderController` (Frontend) | Customer web/mobile |
| Table | `TableOrderController` | QR-code dining |

Status changes dispatch events → listeners → builder services → Mail / SMS / FCM push. Message templates are stored in the `NotificationAlert` table and can be customized per status per channel by admins.

---

## Testing

- **Database:** SQLite in-memory (`:memory:`) — fast, isolated, reset per suite.
- **Queue:** Synchronous — jobs run inline during tests, no worker needed.
- **Mail:** Array driver — captured, not sent.
- **BCRYPT_ROUNDS:** 4 — faster hashing in tests.
- Tests live in `tests/Feature/` and `tests/Unit/`.

---

## New Feature Checklist

- [ ] Migration with foreign keys (`php artisan make:migration`)
- [ ] Model with relations; add `MultiTenantModelTrait` if branch-scoped
- [ ] Form Request class for validation
- [ ] API Resource class for response shaping
- [ ] Service class — follow filter array pattern for `list()`, use `QueryExceptionLibrary`, `Log::info()`, `tap()`
- [ ] Thin controller with `HasMiddleware` + `try/catch`; route in `routes/api.php`; `destroy` returns 202
- [ ] `permission:*` middleware on all write endpoints
- [ ] Vuex store module (state with `temp`, `save` action checks `isEditing`)
- [ ] Vue Router path with `meta.access`
- [ ] Vue component (Options API, `$t()`, loading state, `appService.requestHandler()`)
- [ ] Add translation keys to `resources/js/languages/en.json`
- [ ] PHPUnit feature test

---

## Git Workflow

**NEVER stage, commit, or push files.** The user handles all Git operations.

**Before coding:**
1. Analyze only the files relevant to the task.
2. Produce a short implementation plan.
3. Wait for explicit user approval before writing any code.

**After coding:**
1. List all modified/created files with their paths.
2. Explain the changes made.
3. Suggest a git commit message.

---

## Supporting Documentation

Deeper reference docs live in `.claude/`:

- [`.claude/ARCHITECTURE.md`](.claude/ARCHITECTURE.md) — Detailed layer diagram, multi-tenancy, middleware stack
- [`.claude/CONVENTIONS.md`](.claude/CONVENTIONS.md) — Full coding rules and frontend patterns
- [`.claude/SYSTEMS.md`](.claude/SYSTEMS.md) — Settings API, AI/Payment/SMS adapter patterns, order flow
