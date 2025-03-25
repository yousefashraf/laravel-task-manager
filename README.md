
# Task Management System API

This is a RESTful API built with Laravel 12 for managing tasks with role-based access control.

## Features
- **User authentication** with Laravel Passport
- **Role-based access control** using Spatie Permissions
- **Task management** (create, update, assign, status update)
- Managers can **create**, **update**, and **assign tasks**
- Users can **view** and **update** the status of assigned tasks

## Installation

### Prerequisites
- Docker (recommended) or PHP 8.2+
- Composer
- MySQL or another supported database

### Setup Steps
1. **Start Docker services** (if using Docker):
   ```sh
   docker-compose up -d
   ```

2. **Install dependencies**:
   ```sh
   composer install
   ```

3. **Copy environment file**:
   ```sh
   cp .env.example .env
   ```

4. **Generate application key**:
   ```sh
   php artisan key:generate
   ```

5. **Configure the database** in the `.env` file and run migrations:
   ```sh
   php artisan migrate --seed
   ```

## Obtaining OAuth Token for Testing

### 1. **Generate Passport Client**:
   Run the following command to generate the OAuth client:
   ```sh
   php artisan passport:client
   ```

### 2. **Get Access Token Using Postman**:
   - In Postman, create a **POST** request to:
     ```
     http://localhost/oauth/token
     ```
   - Set the body type to `x-www-form-urlencoded` and add these parameters:
     - **grant_type**: `password`
     - **client_id**: (Your `client_id`)
     - **client_secret**: (Your `client_secret`)
     - **username**: (Your user’s email)
     - **password**: (Your user’s password)
     - **scope**: `*`

### 3. **Receive Access Token**:
   - Send the request and you will receive an `access_token` in the response, like this:
     ```json
     {
       "access_token": "your_generated_token"
     }
     ```

### 4. **Use Token for API Requests**:
   - In Postman, set the **Authorization** tab to **Bearer Token** and paste the `access_token` in the token field.
