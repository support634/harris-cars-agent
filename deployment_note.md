# DigitalOcean Deployment Notes

## App Platform

- App ID: `04f5ff22-8cbb-48c5-9042-69e5c2a1b909`
- App name: `harris-cars-service-center`
- Live URL: `https://harris-cars-xt6si.ondigitalocean.app` (matches `default_ingress`; `APP_URL` in the spec is also set to this value)
- Region: `nyc`
- Tier: `professional`
- Service component: `web`

> The live URL is whatever `doctl apps get … --output json` reports as `spec.services[].envs.APP_URL` and what `default_ingress` resolves to. If you re-create the app, this hostname changes — update both this doc **and** the redirect URI registered on Clerk.

## How This App Is Deployed

This project is deployed on DigitalOcean App Platform, not on a Droplet.

DigitalOcean pulls the code from GitHub:

- Repository: `support634/harris-cars-agent`
- Branch: `main`
- Auto deploy: enabled with `deploy_on_push: true`

Every push to `main` triggers a new App Platform deployment automatically.

## Build and Runtime

The production app is built from the repository root `Dockerfile`.

Important runtime details:

- Container HTTP port: `80`
- Instance size: `apps-s-1vcpu-2gb`
- Instance count: `1` (single-instance — see "Sessions" pitfall below)
- Health check path: `/`
- Health check initial delay: `180`
- Health check period: `30`
- Health check timeout: `10`
- Health check failure threshold: `5`

The Dockerfile:

- installs PHP dependencies with Composer
- builds frontend assets with Node/Vite
- runs the app behind Nginx and Supervisor
- starts through `/var/www/html/docker/render/start.sh`

`start.sh` does, in order:

1. Removes any cached `bootstrap/cache/*.php`.
2. Re-creates `.env` from a fixed allow-list of env vars (so the App Platform env wins over anything baked into the image).
3. Generates an `APP_KEY` if missing.
4. Runs `php artisan migrate --force`.
5. Seeds the local admin user (`UserSeeder`).
6. Runs `php artisan config:cache`, `route:cache`, `view:cache`.
7. Execs `supervisord`.

> **Critical:** any env var read by Laravel via `env()` or `config()` in production **must** be added to the `cat > .env` block in `docker/render/start.sh`. Otherwise it will be stripped from `.env` before `config:cache` and the cached config will see `null`. The Clerk vars below are already wired in.

## Database

This app uses a managed PostgreSQL database attached through App Platform:

- Database component name: `harris-cars-db`
- Engine: `PG`
- Version: `16`

The app uses App Platform environment variable references such as:

- `${harris-cars-db.HOSTNAME}`
- `${harris-cars-db.PORT}`
- `${harris-cars-db.USERNAME}`
- `${harris-cars-db.PASSWORD}`
- `${harris-cars-db.DATABASE}`

> Local dev is **MySQL** but production is **PostgreSQL**. Migrations must be Postgres-compatible:
>
> - `$table->string(...)->after(...)` is silently ignored on Postgres (Laravel only honours it on MySQL). Don't rely on column ordering.
> - `enum()` columns become `varchar` + check constraint on Postgres; changing enum values is non-trivial.
> - JSON cast works the same.

## Local Development

Local development is Docker-based.

- Local URL: `http://localhost:8080`
- Docker Compose maps host port `8080` to container port `80`
- Local DB: MySQL via `docker-compose.yml`

## Clerk OAuth (admin login)

Admin sign-in at `/admin/login` is **env-gated**: the local password form is shown when CLERK_* is unset, and the "Sign in with Clerk" button is shown when all five required CLERK_OAUTH_* variables are set.

### Required env vars (set on DO App Platform → web service)

| Key                          | Notes                                                   |
| ---------------------------- | ------------------------------------------------------- |
| `CLERK_PUBLISHABLE_KEY`      | `pk_test_…` / `pk_live_…` (informational, not strictly needed for OAuth-only flow) |
| `CLERK_SECRET_KEY`           | `sk_test_…` / `sk_live_…` — **mark as SECRET on DO**    |
| `CLERK_OAUTH_CLIENT_ID`      | from Clerk → Configure → OAuth Applications             |
| `CLERK_OAUTH_CLIENT_SECRET`  | shown once on creation — **mark as SECRET on DO**       |
| `CLERK_OAUTH_AUTHORIZE_URL`  | `https://<instance>.clerk.accounts.dev/oauth/authorize` |
| `CLERK_OAUTH_TOKEN_URL`      | `https://<instance>.clerk.accounts.dev/oauth/token`     |
| `CLERK_OAUTH_USERINFO_URL`   | `https://<instance>.clerk.accounts.dev/oauth/userinfo`  |
| `CLERK_OAUTH_SCOPES`         | `openid profile email` (default if unset)               |
| `ADMIN_ALLOWED_EMAILS`       | comma-separated allow-list of admin emails              |

### Set them with `doctl`

```bash
APP_ID=04f5ff22-8cbb-48c5-9042-69e5c2a1b909

# 1. dump the live spec to a yaml file
doctl apps spec get $APP_ID > /tmp/app.yaml

# 2. open /tmp/app.yaml in your editor and add the keys above to the
#    `services[].envs` array of the `web` service. Mark the *_KEY and
#    *_SECRET entries with `type: SECRET`. Example block:
#
#    - key: CLERK_OAUTH_CLIENT_ID
#      value: hOpEleLLMIFI2nlH
#      scope: RUN_AND_BUILD_TIME
#    - key: CLERK_OAUTH_CLIENT_SECRET
#      value: <secret>
#      scope: RUN_AND_BUILD_TIME
#      type: SECRET

# 3. push the new spec — this triggers a redeploy
doctl apps update $APP_ID --spec /tmp/app.yaml
```

You can also do it through the dashboard: **Apps → harris-cars-service-center → Settings → web → Edit → Environment Variables**. Use the dashboard if you want the secret entry box (it encrypts automatically); use `doctl` if you want it scripted.

### Register the production redirect URI on Clerk

In **Clerk Dashboard → Configure → OAuth Applications → your app → Redirect URIs**, add **all** of:

```
http://localhost:8080/admin/oauth/callback
https://harris-cars-xt6si.ondigitalocean.app/admin/oauth/callback
```

Also add any custom domain you later attach (e.g. `https://harriscars.com/admin/oauth/callback`).

> Clerk does an **exact string match**. `http` ≠ `https`, trailing slashes matter, port matters, hostname casing matters. A mismatch returns `invalid_request: redirect_uri … does not match any of the OAuth 2.0 Client's pre-registered redirect urls.`

## DigitalOcean / production pitfalls (translated from NOR-241)

NOR-241 was an Express + SPA codebase that verifies Clerk OAuth tokens on every API request. Our Laravel implementation is **OAuth-then-local-session** (we exchange the code, hit userinfo once, then bridge to a normal Laravel session via `Auth::login`). That sidesteps the three NOR-241 production regressions, but introduces its own. All of these are fixed in this codebase — listed here so they don't come back.

### NOR-241 issues — N/A here, do not re-introduce

| NOR-241 pitfall | Why it's N/A here |
| --- | --- |
| `__clerk_db_jwt` dev-browser cookie suppressed the OAuth bearer | We never read OAuth bearers on requests — we read `Auth::user()` from the Laravel session. Clerk dev-browser cookies are irrelevant to our request pipeline. |
| `clerkMiddleware({ acceptsToken })` silently ignored, filtering happens in `getAuth()` | We don't use `@clerk/express` (or any Node SDK). We hit Clerk's `/oauth/token` and `/oauth/userinfo` directly via Laravel's `Http` facade. |
| Top-bar Sign-in button never flipped on non-portal pages | Our Blade nav uses `auth()->check()` from the Laravel session, which is updated atomically by `Auth::login()` and visible on every subsequent request, every page. |

### Laravel / DO pitfalls that DO apply (and how we handle them)

#### 1. Reverse proxy: `redirect_uri` must be `https://`

DO terminates TLS at the edge and forwards plain HTTP to the container. If Laravel doesn't trust the `X-Forwarded-Proto` header, `route('clerk.callback')` builds an `http://...` URL that mismatches what's registered on Clerk.

**How it's handled:** `bootstrap/app.php` already calls `trustProxies(at: '*', headers: …X_FORWARDED_PROTO|…)`. Combined with `APP_URL=https://…ondigitalocean.app` in the DO env, `route('clerk.callback')` resolves to `https://harris-cars-nkz9e.ondigitalocean.app/admin/oauth/callback`.

**Don't break this** by removing `trustProxies('*')` or by setting `APP_URL` to `http://…`.

#### 2. `start.sh` strips unknown env vars before `config:cache`

`start.sh` rebuilds `.env` from a fixed list of `${VAR:-default}` entries. Any env var **not in that block** is gone before `php artisan config:cache` runs, and `config('clerk.client_id')` returns `null` forever in the cached config.

**How it's handled:** the Clerk + `ADMIN_ALLOWED_EMAILS` block has been added to `docker/render/start.sh`. If you add another env-driven config later, you must add it there too.

#### 3. State CSRF cookie must survive the OAuth round-trip

The OAuth flow stores `clerk_oauth_state` in the session at `/admin/oauth/start` and reads it at `/admin/oauth/callback`. With `instance_count > 1` and `SESSION_DRIVER=file`, the second hit could land on a different instance and lose the state, returning a 403.

**How it's handled:** `instance_count = 1` in the current spec. If you ever scale to multiple instances, switch `SESSION_DRIVER` to `redis` or `database` so sessions are shared. (`database` is the simplest because the managed Postgres is already attached.)

#### 4. Secure session cookie on production

`SESSION_SECURE_COOKIE=true` is set on DO. This is required because the redirect from Clerk (HTTPS) to our callback (HTTPS) sets the auth session cookie, and browsers reject `Secure`-marked cookies over HTTP. Don't set `APP_URL=http://…` in prod.

#### 5. `config:cache` + `env()`

Once `php artisan config:cache` runs, `env()` calls outside of `config/*.php` return `null` in the cached config. Our `config/clerk.php` only ever calls `env()` at config-load time, and all reads from app code go through `config('clerk.*')`. Don't sprinkle `env('CLERK_…')` calls in controllers or services.

#### 6. Postgres column-add migration ordering

The Clerk migration uses `$table->string('clerk_user_id')->nullable()->unique()->after('email')`. Postgres ignores `after()`, so the column lands at the end of the table — that's fine. Just don't write code that assumes column order.

## Verification (after a Clerk-aware deploy)

After pushing to `main` and waiting for the DO build:

1. `curl -s https://harris-cars-xt6si.ondigitalocean.app/admin/login | grep -E 'SIGN IN WITH CLERK|name="password"'` — should show **only** the Clerk button, no password field, when CLERK_* is set on DO. If you see the password field, the env vars didn't make it into the cached config (re-check `start.sh` and the spec).
2. `curl -s -o /dev/null -D - https://harris-cars-xt6si.ondigitalocean.app/admin/oauth/start | grep -i location` — should 302 to `https://<instance>.clerk.accounts.dev/oauth/authorize?…&redirect_uri=https%3A%2F%2Fharris-cars-xt6si.ondigitalocean.app%2Fadmin%2Foauth%2Fcallback&…`. If the `redirect_uri` is `http://…` instead of `https://…`, `TrustProxies` is misconfigured.
3. In a browser, click `SIGN IN WITH CLERK`, sign in with an email in `ADMIN_ALLOWED_EMAILS`, confirm you land on `/admin` with the admin nav.
4. Sign in with an email **not** in the allow-list, confirm you get a 403 with "Your account is not authorized to access the admin panel."
5. `curl -s -o /dev/null -w "%{http_code}\n" -X POST -H "X-XSRF-TOKEN: <fresh>" --cookie <jar> https://harris-cars-xt6si.ondigitalocean.app/admin/login` with valid CSRF should return **403** (the controller-level Clerk-only guard).
6. Tampered state on callback (`/admin/oauth/callback?code=x&state=tampered`) should return **403**.

> **Tip:** `route('clerk.callback')` builds the URL from the **request's** `X-Forwarded-Host` (because we trust proxies), not from `APP_URL`. So even if `APP_URL` ever drifts from the actual ingress, the OAuth flow will still produce a correct redirect URI as long as users hit the real ingress hostname. Keep `APP_URL` matching the ingress anyway — it's used for `route()` calls outside of an HTTP request (artisan, queues, mailables).

## Useful Commands

Check app list:

```bash
doctl apps list --format ID,Spec.Name,DefaultIngress
```

Inspect this app:

```bash
doctl apps get 04f5ff22-8cbb-48c5-9042-69e5c2a1b909 --output json
```

Live tail of deploy + runtime logs:

```bash
doctl apps logs 04f5ff22-8cbb-48c5-9042-69e5c2a1b909 --type RUN --follow
doctl apps logs 04f5ff22-8cbb-48c5-9042-69e5c2a1b909 --type DEPLOY --follow
```

Update the app spec (after editing env vars in a yaml dump):

```bash
doctl apps spec get 04f5ff22-8cbb-48c5-9042-69e5c2a1b909 > /tmp/app.yaml
# edit /tmp/app.yaml ...
doctl apps update 04f5ff22-8cbb-48c5-9042-69e5c2a1b909 --spec /tmp/app.yaml
```

Check recent git state locally:

```bash
git status
```

Check the latest commit on GitHub main:

```bash
gh api repos/support634/harris-cars-agent/commits/main --jq '.sha'
```

Force a fresh deploy without a code change:

```bash
doctl apps create-deployment 04f5ff22-8cbb-48c5-9042-69e5c2a1b909 --force-rebuild
```
