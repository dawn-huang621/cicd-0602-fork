# 實作任務清單

## 已完成的基礎實作

- [x] 建立 order_items 資料表 migration
- [x] 建立 stock_movements 資料表 migration
- [x] 建立 OrderItem Model
- [x] 建立 StockMovement Model
- [x] 新增 Order 與 OrderItems 的一對多關聯（hasMany）
- [x] 新增 OrderItem 與 Product 的所屬關聯（belongsTo）


## Model 關聯設定

- [x] 新增 Product 與 OrderItems 的一對多關聯（hasMany）


## 測試資料（Seed Data）

* [ ] 建立 StockMovementSeeder
* [ ] 為每個產品建立初始入庫紀錄
* [ ] 根據入庫紀錄增加產品庫存數量
* [ ] 新增訂單與訂單明細範例資料
* [ ] 確認執行 `php artisan migrate:fresh --seed` 後，產品具有足夠庫存
* [ ] 確認 Demo 資料可支援訂單建立與訂單審核流程測試



## 後端功能（Backend）

- [x] 重構訂單建立流程，支援多項商品
- [x] 建立 OrderService
- [x] 將訂單商業邏輯從 Controller 移動至 Service
- [x] 實作訂單審核流程
- [x] 審核通過後扣除產品庫存
- [x] 審核通過後建立庫存異動紀錄
- [x] 使用資料庫 Transaction 保護訂單審核流程


## 前端功能（Frontend）

- [x] 新增動態商品列功能
- [x] 計算每個商品項目的小計金額
- [x] 顯示訂單總金額
- [x] 實作訂單建立頁面的 JavaScript：
      動態新增商品列與總金額計算


## 測試（Test）

- [x] 測試建立包含多項商品的訂單
- [x] 測試訂單審核流程
- [x] 測試庫存扣除功能
- [x] 測試庫存異動紀錄建立