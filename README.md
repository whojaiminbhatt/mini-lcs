# Mini Loan Collection System (Mini-LCS)

Mini-LCS is a showcase application that mimics the core functionality of a Loan Collection System. It provides tools for managing loans, tracking collections, and visualizing metrics.

## Tech Stack

- **Backend**: Laravel 13 (PHP 8.3)
- **Frontend**: React 19 + Vite
- **Database**: MySQL 8.0
- **Cache**: Redis

## Quick Start

### 1. Infrastructure
Ensure Docker is running and start the database and cache services:
```bash
docker-compose up -d
```

### 2. Backend Setup
```bash
cd backend
composer run setup
composer run dev
```

### 3. Frontend Setup
```bash
cd frontend
npm install
npm run dev
```

Detailed instructions can be found in the [Backend README](./backend/README.md) and [Frontend README](./frontend/README.md).
