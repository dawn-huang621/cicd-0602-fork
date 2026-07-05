# Complete Sales Order Workflow

## Why

The current ERP system only supports basic order records.

A real sales process requires:
- multiple products in one order
- order approval
- inventory deduction
- inventory history tracking

This change completes the MVP sales workflow.

## What Changes

Add:
- Sales order items
- Order approval workflow
- Stock deduction
- Stock movement records

## Impact

Affected modules:
- Orders
- Products
- Inventory

Affected database:
- orders
- order_items
- products
- stock_movements