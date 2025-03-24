# Task Management System API

This is a RESTful API built with Laravel 12 for managing tasks with role-based access control.

## Features
- User authentication with Laravel Passport
- Role-based access control using Spatie Permissions
- Task management (create, update, assign, status update)
- Managers can create, update, and assign tasks
- Users can view and update the status of assigned tasks
- API follows RESTful principles

## Installation

### Prerequisites
- Docker (recommended) or PHP 8.2+
- Composer
- MySQL or another supported database

### Setup Steps
```sh
# Start Docker services (if using Docker)
docker-compose up -d

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file and run migrations
php artisan migrate --seed
```

## API Endpoints

### Authentication
- `POST /api/login` - Authenticate user and get token
- `POST /api/logout` - Logout user

### Task Management
- `GET /api/tasks` - List tasks
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{task}` - Get task details
- `PUT /api/tasks/{task}` - Update task details
- `POST /api/tasks/{task}/assign` - Assign task to user
- `PATCH /api/tasks/{task}/status` - Update task status
