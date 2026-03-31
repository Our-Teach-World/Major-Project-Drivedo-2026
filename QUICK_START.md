# Quick Start Commands

## Essential Commands to Run

```bash
# Navigate to project
cd c:\xampp\htdocs\drivedo\htdocs\drive-in-laravel

# 1. Run migrations (creates database tables)
php artisan migrate

# 2. Seed default admin user
php artisan db:seed --class=AdminSeeder

# 3. Create storage symlink
php artisan storage:link

# 4. Start development server
php artisan serve

# 5. (Optional) Clear cache/views
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Access Points

- **Homepage**: http://localhost:8000
- **Login**: http://localhost:8000/login
- **Register**: http://localhost:8000/register
- **Admin Login**: http://localhost:8000/admin/login
- **Teacher Dashboard**: http://localhost:8000/teacher/dashboard (after login as teacher)
- **Student Dashboard**: http://localhost:8000/student/dashboard (after login as student)
- **Admin Dashboard**: http://localhost:8000/admin/dashboard (after admin login)

## Useful Tinker Commands

```php
# Start tinker
php artisan tinker

# Check if migrations ran
DB::table('auth')->count();

# Verify admin user exists
DB::table('admin')->first();

# Create test user
App\Models\Auth::create([
    'username' => 'testuser',
    'password' => Hash::make('password123'),
    'role' => 'student',
    'status' => 'approved'
]);
```

## Database Commands

```bash
# Reset database (WARNING: Deletes all data)
php artisan migrate:reset

# Re-run all migrations
php artisan migrate:refresh

# Seed after fresh migration
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_[table_name]

# Create new model
php artisan make:model [ModelName]

# Create new controller
php artisan make:controller [ControllerName]
```

## File Structure Quick Reference

- **Controllers**: `app/Http/Controllers/`
- **Views**: `resources/views/`
- **Models**: `app/Models/`
- **Routes**: `routes/web.php`
- **Middleware**: `app/Http/Middleware/`
- **Migrations**: `database/migrations/`
- **Configuration**: `config/`
- **Uploads**: `storage/app/uploads/`

## Common Issues & Fixes

### Database not connecting?
```bash
# Check .env file
cat .env | grep DB_

# Start MySQL
# XAMPP: Open control panel and click "Start" on MySQL
```

### Can't upload files?
```bash
# Make storage writable
php artisan storage:link

# Check permissions
chmod -R 755 storage/
```

### Views not updating?
```bash
# Clear view cache
php artisan view:clear
```

### Routes not found?
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

## Testing Workflow

1. **Start the server**
   ```bash
   php artisan serve
   ```

2. **Navigate to homepage**
   - http://localhost:8000

3. **Test Student Flow**
   - Register as student (Status: approved immediately)
   - Login with student role
   - View available teachers
   - Browse files

4. **Test Teacher Flow**
   - Register as teacher (Status: pending)
   - Logout and login as admin
   - Approve teacher account
   - Login as teacher
   - Upload test files
   - View uploaded files

5. **Test Admin Panel**
   - http://localhost:8000/admin/login
   - Username: admin
   - Password: admin123
   - Approve pending users
   - View statistics
   - Export user data

## Performance Monitoring

```bash
# Check Laravel log
tail -f storage/logs/laravel.log

# Clear logs
php artisan logs:clear

# Check database queries (in code)
# Add: DB::enableQueryLog();
# Then: dd(DB::getQueryLog());
```

## Production Checklist

Before deploying to production:
- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Setup proper HTTPS certificates
- [ ] Move `NVIDIA_API_KEY` to environment variables
- [ ] Backup database
- [ ] Test all features one final time

---
**Status**: All commands are ready to use
**Last Updated**: 2025-03-26
