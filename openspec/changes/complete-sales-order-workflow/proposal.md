# 完整銷售訂單流程（Complete Sales Order Workflow）

## 為什麼需要這次變更（Why）

目前 ERP 系統只支援基本的訂單資料紀錄。

但實際的銷售流程需要包含：

* 一張訂單可以包含多個商品項目
* 訂單審核流程
* 審核後自動扣除庫存
* 保存庫存異動歷史紀錄

這次變更目標是完成 ERP MVP 版本中的核心銷售流程。

## 變更內容（What Changes）

新增：

* 銷售訂單明細（Sales Order Items）
* 訂單審核流程（Order Approval Workflow）
* 庫存扣除機制（Stock Deduction）
* 庫存異動紀錄（Stock Movement Records）

## 影響範圍（Impact）

影響功能模組：

* 訂單管理（Orders）
* 商品管理（Products）
* 庫存管理（Inventory）

影響資料表：

* orders
* order_items
* products
* stock_movements