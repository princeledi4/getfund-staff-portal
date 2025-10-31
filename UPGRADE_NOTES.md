# Laravel & Filament Upgrade Guide

## Current Versions
- Laravel: 10.49.1
- Filament: 3.3.43
- PHP: 8.4.10 ✅

## Target Versions
- Laravel: 12.x
- Filament: 4.x

## Breaking Changes to Watch For

### Laravel 11 Breaking Changes:
1. **Minimum PHP version**: 8.2 (you have 8.4 ✅)
2. **Config files**: Many moved to bootstrap/app.php
3. **Middleware**: Now defined in bootstrap/app.php
4. **Exception Handler**: Simplified
5. **Scheduling**: Now in routes/console.php

### Laravel 12 Breaking Changes:
1. **Minimum PHP version**: 8.2 (you have 8.4 ✅)
2. **Database**: Changes to query builder
3. **Events**: Some event signature changes
4. **Validation**: Rule updates

### Filament 4 Breaking Changes:
1. **Navigation**: Menu structure changes
2. **Forms**: Some component renames
3. **Tables**: Column method changes
4. **Actions**: Modal handling updates
5. **Resources**: Schema definition updates

## Files That May Need Updates

### Your Custom Files:
- `app/Http/Kernel.php` → Will move to `bootstrap/app.php` in Laravel 11
- `app/Filament/Resources/StaffResource.php` → May need Filament 4 syntax
- `app/Filament/Resources/DepartmentResource.php` → May need Filament 4 syntax
- `app/Exceptions/Handler.php` → Will be simplified

### Config Files:
- Most configs will move to bootstrap/app.php in Laravel 11

## Backup Checklist
- [ ] Database exported
- [ ] .env file backed up
- [ ] Full project folder copied
- [ ] Git committed (if using version control)

## Testing Checklist After Update
- [ ] Application loads without errors
- [ ] Admin panel login works
- [ ] Staff search functionality works
- [ ] Staff profile view works
- [ ] File uploads work
- [ ] Excel import works
- [ ] Security headers present (check with curl -I)
- [ ] Rate limiting works
- [ ] Logging works

## Rollback Plan
If update fails:
1. Delete vendor folder
2. Restore composer.json from backup
3. Run: composer install
4. Restore database from backup
5. Clear caches: php artisan config:clear && php artisan cache:clear
