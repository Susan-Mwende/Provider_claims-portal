# Laravel 11 Upgrade Guide - AAR Portal

## Overview
This document outlines the upgrade from Laravel 7.30.6 to Laravel 11.x for the AAR Portal application.

**Status**: Code changes completed. Ready for composer update and testing.

## ✅ Completed Automated Changes

### 1. Composer Dependencies Updated
- **PHP Version**: Updated from `^7.2.5` to `^8.2` (REQUIRED)
- **Laravel Framework**: Updated from `^7.24` to `^11.0`
- **Removed deprecated packages**:
  - `fideloper/proxy` (now built into Laravel)
  - `fruitcake/laravel-cors` (now built into Laravel)
  - `fzaninotto/faker` (replaced with `fakerphp/faker`)
  - `facade/ignition` (replaced with `spatie/laravel-ignition`)

### 2. Application Structure Updates
- ✅ `bootstrap/app.php` - Completely rewritten to use Laravel 11's new Application builder pattern
- ✅ `app/Http/Kernel.php` - Middleware now configured in bootstrap/app.php (Kernel.php can be deleted after testing)
- ✅ `app/Exceptions/Handler.php` - Updated to use `register()` method instead of `report()` and `render()`
- ✅ `app/Console/Kernel.php` - Simplified for Laravel 11

### 3. Middleware Updates
- ✅ `app/Http/Middleware/TrustProxies.php` - Updated to use Laravel's built-in middleware
- ✅ `app/Http/Middleware/TrimStrings.php` - Added `current_password` to except array
- ✅ `app/Http/Middleware/CheckForMaintenanceMode.php` - No longer needed (handled by framework)

### 4. Model & Database Updates
- ✅ Created `app/Models/User.php` with Laravel 11 structure
- ✅ Updated User model to use `casts()` method instead of `$casts` property
- ✅ Added `password` hashing to casts
- ✅ Renamed `database/seeds` to `database/seeders`
- ✅ Updated `DatabaseSeeder.php` with proper namespace
- ✅ Updated `UserFactory.php` to use class-based factory structure

### 5. Configuration Updates
- ✅ `config/auth.php` - Updated to reference `App\Models\User` instead of `App\User`

### 6. Service Provider Updates
- ✅ `app/Providers/RouteServiceProvider.php` - Simplified for Laravel 11 (routes now handled in bootstrap/app.php)
- ✅ `app/Providers/AuthServiceProvider.php` - Already compatible

## 🔧 Required Manual Steps

### Step 1: Upgrade PHP Version
**CRITICAL**: You must upgrade to PHP 8.2 or higher before proceeding.

```bash
# Check current PHP version
php -v

# You need PHP 8.2 or higher
```

### Step 2: Backup Your Database and Files
```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Backup .env file
copy .env .env.backup
```

### Step 3: Update Composer Dependencies
```bash
# Delete vendor directory and composer.lock
Remove-Item -Recurse -Force vendor
Remove-Item composer.lock

# Install new dependencies
composer install
```

### Step 4: Model References - ✅ COMPLETED
All model references have been automatically updated:
- ✅ All models moved to `app/Models/` directory
- ✅ All controller imports updated to use `App\Models\*`
- ✅ All DataTable classes updated
- ✅ All Import classes updated
- ✅ All Notification classes updated
- ✅ Helper classes updated
- ✅ Factory classes updated to Laravel 11 pattern

### Step 5: Update Route Definitions
Laravel 11 no longer uses controller namespaces by default. Update routes in `routes/web.php`:

**Before:**
```php
Route::get('/providers', 'ProviderController@index');
```

**After:**
```php
use App\Http\Controllers\ProviderController;
Route::get('/providers', [ProviderController::class, 'index']);
```

**ALL routes need this update** - there are approximately 50+ routes to update.

### Step 6: Update Model Relationships
In models like `app/User.php` (now `app/Models/User.php`), update relationships:

**Before:**
```php
return $this->hasOne('App\PasswordSecurity');
```

**After:**
```php
return $this->hasOne(\App\PasswordSecurity::class);
```

### Step 7: Update Eloquent Model Casts
For all models, convert `$casts` property to `casts()` method:

**Before:**
```php
protected $casts = [
    'email_verified_at' => 'datetime',
];
```

**After:**
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
    ];
}
```

### Step 8: Update Date Handling
Remove `$dates` property from models - use casts instead:

**Before:**
```php
protected $dates = [
    'updated_at',
    'created_at',
    'deleted_at',
];
```

**After:** (handled automatically by Laravel 11)

### Step 9: Update Configuration Files
Review and update these config files if they exist:
- `config/cors.php` - May need updates for new structure
- `config/trustedproxy.php` - Can be removed (now in middleware)
- `config/session.php` - Check for deprecated options
- `config/database.php` - Verify MySQL driver compatibility

### Step 10: Clear and Rebuild Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Step 11: Run Migrations
```bash
php artisan migrate:status
# If needed:
# php artisan migrate
```

### Step 12: Update Package-Specific Code

#### Yajra DataTables
Update DataTable classes in `app/DataTables/`:
- Check for deprecated methods
- Update to version 11 syntax

#### Maatwebsite Excel
- Verify import/export classes are compatible
- Update namespace references if needed

#### Laravel UI
- Update authentication views if using Laravel UI
- Check for deprecated Blade directives

### Step 13: Update Tests
If you have tests in `tests/` directory:
- Update to use PHPUnit 10 syntax
- Update factory usage to new class-based factories
- Update assertions for compatibility

### Step 14: Delete Old Files
After confirming everything works, you can delete:
- `app/User.php` (moved to `app/Models/User.php`) - ⚠️ Keep until testing complete
- `app/Claim.php` (moved to `app/Models/Claim.php`) - ⚠️ Keep until testing complete
- `app/Provider.php` (moved to `app/Models/Provider.php`) - ⚠️ Keep until testing complete
- `app/PasswordSecurity.php` (moved to `app/Models/PasswordSecurity.php`) - ⚠️ Keep until testing complete
- `app/LogActivity.php` (moved to `app/Models/LogActivity.php`) - ⚠️ Keep until testing complete
- `app/Upload.php` (moved to `app/Models/Upload.php`) - ⚠️ Keep until testing complete
- `app/Export.php` (moved to `app/Models/Export.php`) - ⚠️ Keep until testing complete
- `app/File.php` (moved to `app/Models/File.php`) - ⚠️ Keep until testing complete
- `app/Http/Kernel.php` (functionality moved to bootstrap/app.php) - ⚠️ Keep until testing complete
- `app/Http/Middleware/CheckForMaintenanceMode.php` (no longer needed)

## 🚨 Breaking Changes to Watch For

### 1. String and Array Helpers
If using helper functions like `str_*` or `array_*`, ensure you're using the Str and Arr facades:
```php
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
```

### 2. Request Validation
Validation error responses may have changed format slightly.

### 3. Database Query Builder
Some query builder methods have stricter type requirements.

### 4. Carbon Date Handling
Carbon has been updated - check date formatting and parsing.

### 5. Middleware Priority
Middleware execution order is now defined in bootstrap/app.php.

## 📝 Testing Checklist

After upgrade, test these critical features:

- [ ] User authentication (login/logout)
- [ ] Two-factor authentication
- [ ] User registration (if enabled)
- [ ] Password reset
- [ ] Email verification
- [ ] Admin panel access
- [ ] Claims creation and viewing
- [ ] Excel import/export functionality
- [ ] DataTables rendering
- [ ] File uploads
- [ ] PDF generation
- [ ] User permissions (admin, auditor, guest roles)
- [ ] Audit trail logging
- [ ] Provider management
- [ ] Bulk claims import
- [ ] Search functionality
- [ ] Date range filtering

## 🔍 Common Issues and Solutions

### Issue: "Class 'App\User' not found"
**Solution**: Update all references from `App\User` to `App\Models\User`

### Issue: "Target class [Controller] does not exist"
**Solution**: Update routes to use full controller class references with `::class`

### Issue: Middleware not executing
**Solution**: Check middleware configuration in `bootstrap/app.php`

### Issue: "Call to undefined method casts()"
**Solution**: Ensure you're on Laravel 11 and have updated composer dependencies

### Issue: Session/Auth issues
**Solution**: Clear all caches and regenerate application key:
```bash
php artisan key:generate
php artisan config:clear
```

## 📚 Additional Resources

- [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
- [Laravel 11 Release Notes](https://laravel.com/docs/11.x/releases)
- [PHP 8.2 Migration Guide](https://www.php.net/manual/en/migration82.php)

## ⚠️ Important Notes

1. **PHP 8.2+ is REQUIRED** - The application will not run on PHP 7.x
2. **Test thoroughly** - This is a major version upgrade spanning 4 Laravel versions
3. **Database compatibility** - Ensure your MySQL/MariaDB version is compatible
4. **Third-party packages** - Some packages may need updates or replacements
5. **Custom code** - Review all custom helpers, traits, and classes for compatibility

## 🎯 Next Steps

1. Upgrade PHP to 8.2+
2. Run `composer install`
3. Update all route definitions
4. Update all model references
5. Test authentication flow
6. Test all critical features
7. Deploy to staging environment first
8. Monitor logs for deprecation warnings

## Support

If you encounter issues during the upgrade:
1. Check Laravel 11 documentation
2. Review error logs in `storage/logs/`
3. Check PHP error logs
4. Verify all dependencies are compatible with Laravel 11
