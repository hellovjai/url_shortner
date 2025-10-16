A Laravel 11-based service for managing companies, users with roles, invitations, and company-specific URL shortening.

## Setup
1. Clone the repo: `https://github.com/hellovjai/url_shortner.git`
2. `composer install`
3. Copy `.env.example` to `.env` and configure DB (MySQL).
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed` (creates SuperAdmin)
7. `php artisan serve`
