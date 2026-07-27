# Design: Complete Sales Order Workflow

## Overview

此變更完成 Mini ERP 的銷售訂單流程。

主要包含：

* 多商品訂單（Multi-item Order）
* 訂單審核（Order Approval）
* 商品庫存扣除（Inventory Deduction）
* 庫存異動紀錄（Stock Movement）
* 初始庫存測試資料（Initial Inventory Seeding）

目標是讓系統具備完整的訂單生命週期：

商品建立 → 初始入庫 → 建立訂單 → 審核訂單 → 扣除庫存 → 建立庫存異動紀錄

---

## Architecture

### OrderController

負責處理 HTTP request。

主要職責：

* 接收訂單建立請求
* 接收訂單審核請求
* 回傳處理結果

不直接處理商業邏輯。

---

### OrderService

負責訂單相關商業流程。

包含：

* 建立訂單
* 驗證庫存
* 審核訂單
* 扣除商品庫存
* 建立出庫紀錄

所有會改變庫存的流程必須使用 database transaction，
確保資料一致。

---

### Stock

保存商品目前可用庫存數量。

目前使用：

stocks.product_id
stocks.quantity

每個商品目前只有一筆 Stock 紀錄：

Product
↓ hasOne
Stock

Stock 是目前可用庫存的主要資料來源。

目前系統尚未實作多倉庫，但將庫存獨立於 products 資料表，可以在未來加入 warehouse_id，支援同一商品存放於不同倉庫。

目前不應再使用 products.stock_quantity 保存庫存，以避免產生兩份庫存來源。

---

### OrderItem

保存訂單中的每個商品項目。

一張訂單可以包含多個商品：

Order

↓

OrderItem

↓

Product

---

### StockMovement

記錄每一次庫存變化。

StockMovement 不作為目前庫存來源，
而是作為庫存歷史與追蹤紀錄。

範例：

初始入庫：

type = in

quantity = 100

訂單出貨：

type = out

quantity = 5

系統可以透過 stock movements 追蹤：

* 為什麼庫存增加
* 為什麼庫存減少
* 哪張訂單造成異動

---

## Initial Inventory Design

測試資料不直接修改商品庫存。

錯誤方式：

建立 Product 時直接給：

stock_quantity = 100

原因：

這會產生庫存結果，但沒有任何入庫紀錄。

---

正確流程：

ProductSeeder:

建立商品。

預設：

## Initial Inventory Design

測試資料不應直接修改庫存數量，而沒有建立對應的庫存異動紀錄。

錯誤方式：

在 ProductSeeder 建立商品時，直接將 `stocks.quantity` 設定為 100。

原因：

這會產生目前庫存結果，但系統中沒有任何入庫歷史紀錄，無法追蹤庫存來源。

正確流程：

### ProductSeeder

建立商品資料。


### InitialInventorySeeder

為每個商品建立初始入庫 StockMovement：

```text
type = in
quantity = 入庫數量
```

建立入庫紀錄後，同步增加：

```text
stocks.quantity += inbound quantity
```

### OrderSeeder

建立測試訂單。

訂單商品數量不可超過對應商品的 `stocks.quantity`。


---

## Seeder Flow

Seeder 必須按照資料依賴順序執行：

ProductSeeder

↓

InitialInventorySeeder

↓

CustomerSeeder

↓

OrderSeeder

執行：

php artisan migrate:fresh --seed

完成後必須確保：

* 商品存在
* 商品具有初始庫存
* 入庫紀錄存在
* 測試訂單建立成功
* 訂單不會超過可用庫存

---

## Sales Order Data Flow

1. 使用者建立包含多商品的訂單。

2. 系統建立：

* orders
* order_items

3. 使用者審核訂單。

4. 系統檢查每個商品庫存。

5. 如果全部商品庫存足夠：

* 扣除 products.stock_quantity
* 建立 outbound stock movement
* 更新訂單狀態

6. 如果任何商品庫存不足：

* 不扣除任何商品庫存
* 不建立 stock movement
* 回傳錯誤訊息

---

## Business Rules

### Order

* 只有 pending 狀態的訂單可以審核。
* 已審核訂單不可重複扣庫存。
* 訂單必須至少包含一個商品。

---

### Inventory

* 商品庫存不可小於 0。
* 出庫前必須檢查可用庫存。
* 所有庫存異動都必須留下 StockMovement。
* 不允許直接修改商品庫存但沒有紀錄。

---

### Transaction

訂單審核必須使用 database transaction。

流程包含：

1. 檢查庫存。
2. 扣除商品庫存。
3. 建立 stock movement。
4. 更新訂單狀態。

任何步驟失敗：

所有資料變更 rollback。

避免：

* 訂單成功但庫存沒有扣除
* 庫存扣除但沒有異動紀錄
* 部分商品扣庫存成功，部分失敗

---

## Out of Scope

目前不實作：

* 多倉庫（Warehouse）
* 供應商管理（Supplier）
* 採購單（Purchase Order）
* 批號管理
* 保存期限管理


以上功能未來可以基於目前 StockMovement 架構擴充。