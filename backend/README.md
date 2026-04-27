# Mini LCS - Backend

The backend of the Mini Loan Collection System, built with Laravel.

## Prerequisites

- **PHP 8.3+**
- **Composer**
- **Docker & Docker Compose** (for MySQL and Redis)
- **Node.js & npm** (for Vite assets)

## Getting Started

Follow these steps to get the backend running:

### 1. Database and Cache
Start the required services using Docker (from the root directory):
```bash
docker-compose up -d
```

### 2. Initial Setup
Run the built-in setup script which installs dependencies, sets up environment variables, generates the application key, and runs migrations:
```bash
composer run setup
```

### 3. Start Development Server
Run the development command to start the Laravel server, queue listener, and Vite:
```bash
composer run dev
```

The server will be available at [http://localhost:8000](http://localhost:8000).

## Features
- **API Documentation**: Access Swagger documentation at `/api/documentation`.
- **Queue Management**: Handled automatically via `php artisan queue:listen` in the `dev` script.
- **Cache**: Uses Redis for caching and session management.

## Project Structure
- `app/`: Core business logic, models, and controllers.
- `database/`: Migrations, factories, and seeders.
- `routes/api.php`: API endpoints definition.
