# Deployment Checklist for Authentication Features

## Pre-Deployment

- [ ] **Backup Database:** Ensure a full backup exists before applying migrations.
- [ ] **Environment Variables:**
    - Set `SESSION_LIFETIME=120` in `.env`.
    - Set `SESSION_DRIVER=database` (or redis).
    - Ensure `APP_KEY` is set.

## Deployment Steps

1. **Pull Code:**
   ```bash
   git pull origin main
   ```

2. **Install Dependencies:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm ci && npm run build
   ```

3. **Run Migrations:**
   ```bash
   php artisan migrate --force
   ```

4. **Seed Permissions and Roles:**
   ```bash
   php artisan db:seed --class=RolePermissionSeeder --force
   ```
   *Note: This will ensure all new permissions are created and assigned to default roles.*

5. **Clear Caches:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan permission:cache-reset
   ```

## Post-Deployment Verification

- [ ] **Login:** Verify Admin login works.
- [ ] **Permissions:** Check if Admin can access `/admin/users`.
- [ ] **Super Admin:** Verify Super Admin has access to everything.
- [ ] **Rate Limiting:** Try to login 6 times incorrectly and verify 1-minute block.
- [ ] **Logs:** Check `/admin/activity` to see if the deployment actions (logins) are being recorded.
