# Design: Complete Sales Order Workflow

## Overview

This change completes the Mini ERP sales order workflow by supporting multi-item orders, order approval, inventory deduction, and stock movement records.

## Architecture

- OrderController handles HTTP requests.
- OrderService handles business logic.
- OrderItem stores each product line in an order.
- StockMovement records inventory changes.
- Database transactions are used during approval.

## Data Flow

1. User creates an order with multiple items.
2. System saves the order and order_items.
3. User approves the order.
4. System checks product stock.
5. System deducts product stock.
6. System creates stock_movement records.
7. System updates order status.

## Business Rules

- Only pending orders can be approved.
- Product stock must be enough before approval.
- Approval should be atomic.
- If any item fails stock validation, no stock should be deducted.