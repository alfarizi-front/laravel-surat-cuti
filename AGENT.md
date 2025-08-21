# AGENT.md - Surat Cuti Laravel Application

## Build/Test Commands
- **Development**: `composer run dev` (runs server, queue, logs, vite concurrently)
- **Build**: `npm run build` (Vite build for assets)
- **Test**: `composer run test` or `php artisan test`
- **Single test**: `php artisan test --filter=TestName` or `php artisan test tests/Feature/SpecificTest.php`
- **Code style**: `./vendor/bin/pint` (Laravel Pint for formatting)

## Architecture & Structure
- **Framework**: Laravel 12 with PHP 8.2+, Tailwind CSS 4.0, Vite
- **Database**: SQLite (testing), configured in `.env` for production
- **Key Models**: User, SuratCuti, JenisCuti (leave application system)
- **Controllers**: Standard Laravel structure in `app/Http/Controllers/`
- **PDF Generation**: Uses `barryvdh/laravel-dompdf` package
- **Authentication**: Laravel Breeze (users have roles: admin/employee)

## Code Style & Conventions
- **PSR-4 autoloading**: `App\` namespace for `app/` directory
- **Models**: Eloquent with `$fillable` arrays, relationships, date casting
- **Controllers**: Resource controllers, dependency injection in constructors
- **Naming**: snake_case for database columns, camelCase for PHP variables
- **Types**: Use native PHP types and docblocks (`@var`, `@param`, `@return`)
- **Database**: Migrations in `database/migrations/`, use descriptive names
