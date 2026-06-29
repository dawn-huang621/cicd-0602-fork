# Mini ERP System

A lightweight ERP system for Sales and Inventory Management.

Built with Laravel 11, PostgreSQL and Docker.

## Tech Stack

- Laravel 11
- PostgreSQL
- Docker Compose
- Bootstrap 5
- Chart.js

系統 Dashboard 截圖

## Order Workflow

```mermaid
flowchart TD
A[Create Order]
--> B[Pending Approval]

B --> C[Approved]

C --> D[Ship Order]

D --> E[Reduce Inventory]

E --> F[Create Stock Movement]

+--------------------------------------------------+
| Sales Order SO0001                              |
+--------------------------------------------------+
| Customer : Company A                            |
| Status   : Approved                             |
+--------------------------------------------------+

| Product | Qty | Price | Subtotal               |
| Laptop  | 2   |30000  |60000                   |

Total : 60000

+----------------------------------------------------------------+
| Inventory Movements                                            |
+----------------------------------------------------------------+
| Date       | Product | Type | Qty | Balance | Reference       |
| 2026-06-24 | Laptop  | OUT  | -2  | 18      | SO0001          |
+----------------------------------------------------------------+

Order Module

*Stock Movement
+----------------------------------------------------------------+
| Inventory Movements                                            |
+----------------------------------------------------------------+
| Date       | Product | Type | Qty | Balance | Reference       |
| 2026-06-24 | Laptop  | OUT  | -2  | 18      | SO0001          |
+----------------------------------------------------------------+

5. Database ER Diagram

## System Architecture

Browser
   │
   ▼

Laravel Application
   │
   ├── Controllers
   ├── Services
   ├── Models
   └── Repositories

   │
   ▼

PostgreSQL

+------------------------+
| Docker                 |
|                        |
| nginx                  |
| php-fpm                |
| postgres               |
+------------------------+

## Technical Highlights

- Transaction for inventory consistency
- Service Layer architecture
- Approval workflow implementation
- Dockerized development environment
- Dashboard analytics with Chart.js

## Installation

```bash
git clone xxx

cp .env.example .env

docker compose up -d

docker compose exec app composer install

docker compose exec app php artisan migrate --seed

## Project Goals

This project simulates a lightweight ERP system focusing on sales and inventory workflows.

Key business scenarios include:

- Sales order management
- Approval workflow
- Inventory deduction
- Stock movement tracking
- Dashboard reporting