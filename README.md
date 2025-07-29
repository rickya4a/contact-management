# Contact Manager Application

A full-stack contact management application built with Vue.js and Laravel.

## Tech Stack

### Frontend
- Vue.js 3
- TypeScript
- Tailwind CSS
- Pinia (State Management)
- Vite
- Vitest (Testing)

### Backend
- Laravel 10
- PostgreSQL
- Laravel Sanctum (Authentication)
- PHPUnit (Testing)
- Backpack for Laravel (Admin Panel)

## Prerequisites

Before you begin, ensure you have the following installed:
- Git
- Node.js (v18 or higher)
- PHP 8.2 or higher
- Composer
- PostgreSQL
- Docker & Docker Compose (optional, for containerized setup)

## Local Development Setup

### Backend Setup

1. Navigate to backend directory:
```bash
cd backend
```

2. Install PHP dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Configure your database in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nomadas
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run database migrations:
```bash
php artisan migrate
```

7. Create test database:
```bash
createdb nomadas_test
```

8. Run backend tests:
```bash
php artisan test
```

9. Start the development server:
```bash
php artisan serve
```

The backend will be available at `http://localhost:8000`

### Frontend Setup

1. Navigate to frontend directory:
```bash
cd frontend
```

2. Install Node.js dependencies:
```bash
npm install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Configure your environment variables in `.env`:
```
VITE_API_URL=http://localhost:8000
```

5. Run frontend tests:
```bash
npm run test:unit
```

6. Start the development server:
```bash
npm run dev
```

The frontend will be available at `http://localhost:5173`


## Development Guidelines

### Code Style
- Backend follows PSR-12 coding standard
- Frontend uses ESLint and Prettier
- Use TypeScript for type safety
- Follow SOLID principles

### Testing
- Write unit tests for new features
- Maintain test coverage
- Run tests before committing

## License

[MIT License](LICENSE)