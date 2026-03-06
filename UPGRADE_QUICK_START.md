# Laravel 11 Upgrade - Quick Start Guide

## ⚠️ BEFORE YOU START

**CRITICAL**: This project requires **PHP 8.2 or higher**. Check your PHP version:
```bash
php -v
```

If you're not on PHP 8.2+, you **MUST** upgrade PHP first before proceeding.

## 🚀 Quick Upgrade Steps

### 1. Backup Everything
```bash
# Backup database
mysqldump -u username -p database_name > backup.sql

# Backup .env
copy .env .env.backup
```

### 2. Install Dependencies
```bash
# Remove old dependencies
Remove-Item -Recurse -Force vendor
Remove-Item composer.lock

# Install new Laravel 11 dependencies
composer install
```

### 3. Update Routes (REQUIRED)
All routes in `routes/web.php` must be updated from string syntax to array syntax.

**Find and replace pattern:**
- `'ControllerName@method'` → `[ControllerName::class, 'method']`

**Example:**
```php
# OLD (Laravel 7):
Route::get('/providers', 'ProviderController@index');

# NEW (Laravel 11):
use App\Http\Controllers\ProviderController;
Route::get('/providers', [ProviderController::class, 'index']);
```

**You need to add `use` statements at the top of routes/web.php for ALL controllers used.**

### 4. Update Model References Throughout Codebase
Search and replace in ALL files:
- `App\User` → `App\Models\User`
- `use App\User;` → `use App\Models\User;`

**Files to check:**
- All controllers
- All middleware
- All models with User relationships
- Policies
- Observers
- Jobs
- Notifications
- Mail classes

### 5. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### 6. Test Authentication
```bash
php artisan serve
```
Then test:
- Login
- Logout
- Two-factor authentication
- Password reset

## 🔥 Most Common Errors & Fixes

### Error: "Class 'App\User' not found"
**Fix**: You missed updating a reference. Search for `App\User` and replace with `App\Models\User`

### Error: "Target class [ProviderController] does not exist"
**Fix**: Routes not updated. Add `use App\Http\Controllers\ProviderController;` and use `[ProviderController::class, 'method']` syntax

### Error: "Your requirements could not be resolved"
**Fix**: PHP version too old. Upgrade to PHP 8.2+

### Error: Session/Authentication issues
**Fix**: 
```bash
php artisan key:generate
php artisan config:clear
php artisan session:table  # if using database sessions
```

## 📋 Files Already Updated

✅ `composer.json` - Dependencies updated to Laravel 11
✅ `bootstrap/app.php` - New Laravel 11 structure
✅ `app/Models/User.php` - Created with Laravel 11 syntax
✅ `app/Exceptions/Handler.php` - Updated
✅ `app/Console/Kernel.php` - Simplified
✅ `app/Providers/RouteServiceProvider.php` - Updated
✅ `app/Http/Middleware/TrustProxies.php` - Updated
✅ `app/Http/Middleware/TrimStrings.php` - Updated
✅ `config/auth.php` - Updated User model reference
✅ `database/seeders/DatabaseSeeder.php` - Updated namespace
✅ `database/factories/UserFactory.php` - Converted to class-based

## 📋 Files You MUST Update Manually

❌ `routes/web.php` - ~50 routes need syntax update
❌ All Controllers - Update `App\User` references
❌ All Models - Update relationships and casts
❌ `app/PasswordSecurity.php` - Update User reference
❌ `app/Claim.php` - Update User reference if exists
❌ Other models with User relationships

## 🎯 Priority Order

1. **Install PHP 8.2+** (if not already)
2. **Run `composer install`**
3. **Update routes/web.php** (most critical)
4. **Update all `App\User` references**
5. **Test authentication**
6. **Test each feature systematically**

## 📞 Need Help?

See `LARAVEL_11_UPGRADE_GUIDE.md` for detailed information on:
- Breaking changes
- Configuration updates
- Testing checklist
- Troubleshooting

## ⏱️ Estimated Time

- Small project: 2-4 hours
- Medium project (like this): 4-8 hours
- Testing: 2-4 hours

**Total: 6-12 hours** depending on complexity and issues encountered.
