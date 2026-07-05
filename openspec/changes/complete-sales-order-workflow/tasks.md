# Implementation Tasks

## Existing Implementation

- [x] Create order_items migration
- [x] Create stock_movements migration
- [x] Create OrderItem model
- [x] Create StockMovement model
- [x] Add Order hasMany OrderItems
- [x] Add OrderItem belongsTo Product


## Model Relationships

- [x] Add Product hasMany OrderItems


## Backend

- [x] Refactor order creation for multiple items
- [x] Create OrderService
- [x] Move order business logic from Controller to Service
- [x] Implement approve order workflow
- [x] Reduce product stock after approval
- [x] Create stock movement after approval
- [x] Wrap approval process in database transaction


## Frontend

- [x] Add dynamic product rows
- [x] Calculate subtotal per item
- [x] Display order total


## Test

- [x] Test creating order with multiple items
- [x] Test approving order
- [x] Test stock reduction
- [x] Test stock movement creation