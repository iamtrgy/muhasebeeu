# Muhasebe EU

A Laravel 12 based accounting application with user management and admin features.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd muhasebeeu
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=muhasebeeu
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:
```bash
php artisan migrate
```

8. Start the development server:
```bash
php artisan serve
```

9. In a new terminal, start Vite:
```bash
npm run dev
```

## Default Admin User

The application comes with a default admin user:

- Email: admin@example.com
- Password: password

## Features

- User Authentication
- Admin Dashboard
- User Management
- Role-based Access Control

## Routes

### Admin Routes
- Admin Dashboard: `/admin/dashboard`
- User Management: `/admin/users`

### User Routes
- Dashboard: `/dashboard`
- Profile: `/profile`

## Development

The application uses:
- Laravel 12
- Laravel Breeze for authentication
- Tailwind CSS for styling
- Vite for asset bundling

## License

This project is licensed under the MIT License.
