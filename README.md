# E-commerce Order Management System (Laravel 12)

## Table of Contents

1. [Project Overview](#project-overview)  
2. [Requirements](#requirements)  
3. [Installation & Setup](#installation--setup)  
4. [Database Setup & Seeded Data](#database-setup--seeded-data)  
5. [Running the Application](#running-the-application)  
6. [API Documentation](#api-documentation)  
7. [Authentication](#authentication)  
8. [Postman Collection](#postman-collection)  
9. [Testing](#testing)  
10. [Notes](#notes)  

---

## Project Overview

This project is a **backend for an E-commerce Order Management System**, built with **Laravel 12**.  

The system includes:

- User authentication & authorization (admin and customer roles)  
- Product & category management  
- Cart & order processing  
- Mocked payments  
- Middleware, services, and traits for reusable logic  
- Notifications & caching  
- RESTful APIs with consistent JSON responses  

---

## Requirements

- PHP >= 8.1  
- Composer  
- MySQL or MariaDB  
- Laravel 12  
- Xdebug

---

## Installation & Setup

1. Clone the repository  
2. Install dependencies  
3. Copy `.env.example` and configure database  
4. Generate application key  

---

## Database Setup & Seeded Data

Run migrations and seeders.  

Seeded Data Includes:

| Entity     | Count | Notes |
|------------|-------|-------|
| Admins     | 2     | Specific emails: admin1@example.com, admin2@example.com |
| Customers  | 10    | Randomly generated |
| Categories | 5     | Random names & descriptions |
| Products   | 20    | Random names, prices, stock |
| Carts      | 10    | Random products added to carts |
| Orders     | 15    | Created from carts with mocked payments |

---

## Running the Application

Start Laravel’s development server.  

The API will be available at: `http://127.0.0.1:8000`

---

## API Documentation

All endpoints return **consistent JSON responses** with the following structure:

- `success`: boolean  
- `message`: string  
- `data`: object or array  
- `errors`: object or null  

### User APIs

| Method | Endpoint         | Role/Auth       | Description            |
|--------|----------------|----------------|-----------------------|
| POST   | /api/register   | No             | Register a user       |
| POST   | /api/login      | No             | Login a user          |
| POST   | /api/logout     | Bearer token   | Logout                |
| GET    | /api/me         | Bearer token   | Get current user info |

### Category APIs

| Method | Endpoint               | Role/Auth       | Description           |
|--------|------------------------|----------------|----------------------|
| GET    | /api/categories        | No             | List categories      |
| POST   | /api/categories        | Admin           | Create category      |
| PUT    | /api/categories/{id}   | Admin           | Update category      |
| DELETE | /api/categories/{id}   | Admin           | Delete category      |

### Product APIs

| Method | Endpoint               | Role/Auth       | Description           |
|--------|------------------------|----------------|----------------------|
| GET    | /api/products          | No             | List products (filters available) |
| GET    | /api/products/{id}     | No             | Get product details  |
| POST   | /api/products          | Admin           | Create product       |
| PUT    | /api/products/{id}     | Admin           | Update product       |
| DELETE | /api/products/{id}     | Admin           | Delete product       |

### Cart APIs

| Method | Endpoint          | Role/Auth       | Description           |
|--------|-----------------|----------------|----------------------|
| GET    | /api/cart        | Customer        | View cart            |
| POST   | /api/cart        | Customer        | Add product to cart  |
| PUT    | /api/cart/{id}   | Customer        | Update cart quantity |
| DELETE | /api/cart/{id}   | Customer        | Remove product       |

### Order APIs

| Method | Endpoint                  | Role/Auth       | Description             |
|--------|---------------------------|----------------|------------------------|
| POST   | /api/orders               | Customer        | Place order from cart  |
| GET    | /api/orders               | Customer        | List user orders       |
| PUT    | /api/orders/{id}/status   | Admin           | Update order status    |

### Payment APIs

| Method | Endpoint                  | Role/Auth       | Description             |
|--------|---------------------------|----------------|------------------------|
| POST   | /api/orders/{id}/payment  | Bearer token   | Mock payment           |
| GET    | /api/payments/{id}        | Bearer token   | Get payment details    |

---

## Authentication

- Uses **Laravel Sanctum**  
- After login, use the **Bearer token** in headers: `Authorization: Bearer {{TOKEN}}`  
- Postman script can save token for future requests

---

## Postman Collection

- File: `E-commerce-API.postman_collection.json`  
- Import into Postman to test all endpoints  
- Environment variables: `{{base_url}}`, `{{TOKEN}}`

---

## Testing

- Feature tests for registration, login, product creation, cart updates, order placement  
- Unit test for `OrderService`  
- Run tests using `php artisan test`  
- Code coverage requires Xdebug or PCOV  

---

## Notes

- All APIs return **consistent JSON responses**  
- **Caching** implemented for product listing  
- **Notifications & queues** for order confirmation  
- **Seeded data** included for demo/testing  
- Designed for **extendability**  
