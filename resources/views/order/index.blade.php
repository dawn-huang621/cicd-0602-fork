<head>
    
    <style>
        * {
        box-sizing: border-box;
        }

        body {
        margin: 0;
        font-family: Arial, "Microsoft JhengHei", sans-serif;
        background: #f3f5f8;
        color: #1f2937;
        }

        .page {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 24px;
        }

        .page-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        }

        .page-desc {
        color: #6b7280;
        margin-bottom: 24px;
        }

        .card {
        background: #fff;
        border: 1px solid #d9dee7;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
        overflow: hidden;
        }

        .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fbff;
        }

        .breadcrumb {
        font-weight: 600;
        color: #374151;
        }

        .btn-primary {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 16px;
        font-weight: 600;
        cursor: pointer;
        }

        .btn-secondary {
        background: #e5e7eb;
        color: #374151;
        border: none;
        border-radius: 6px;
        padding: 10px 16px;
        cursor: pointer;
        }

        .filters {
        display: grid;
        grid-template-columns: 1.3fr 1fr 1fr 1.8fr auto auto;
        gap: 16px;
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
        align-items: end;
        }

        .form-group label {
        display: block;
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
        }

        input,
        select {
        width: 100%;
        height: 38px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0 12px;
        font-size: 14px;
        background: #fff;
        }

        .date-range {
        display: flex;
        align-items: center;
        gap: 8px;
        }

        .table-area {
        padding: 24px;
        }

        .table-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 16px;
        align-items: center;
        }

        .entries select {
        width: 70px;
        margin-right: 6px;
        }

        .search {
        display: flex;
        align-items: center;
        gap: 8px;
        }

        .search input {
        width: 220px;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        }

        th {
        text-align: left;
        padding: 12px 10px;
        border-bottom: 1px solid #9ca3af;
        font-size: 14px;
        color: #374151;
        }

        td {
        padding: 12px 10px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 15px;
        }

        tr:hover {
        background: #f9fafb;
        }

        .amount {
        text-align: right;
        }

        .status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        }

        .status-pending {
        color: #dc2626;
        background: #fee2e2;
        border: 1px solid #fecaca;
        }

        .status-approved {
        color: #15803d;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        }

        .status-shipped {
        color: #2563eb;
        background: #dbeafe;
        border: 1px solid #bfdbfe;
        }

        .btn-detail {
        background: #6366f1;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 12px;
        cursor: pointer;
        font-size: 14px;
        }

        .summary-row td {
        border-bottom: 1px solid #9ca3af;
        font-weight: 700;
        }

        .total-label {
        text-align: right;
        }

        .total-money {
        font-weight: 700;
        text-align: right;
        }

        .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 18px;
        color: #374151;
        }

        .pagination {
        display: flex;
        gap: 8px;
        align-items: center;
        }

        .page-btn {
        border: 1px solid #d1d5db;
        background: #fff;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        }

        .page-btn.active {
        background: #f3f4f6;
        border-color: #9ca3af;
        font-weight: 700;
        }

        .status-help {
        margin-top: 28px;
        padding: 20px 24px;
        background: #f8fbff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        }

        .status-help h3 {
        margin-top: 0;
        margin-bottom: 16px;
        color: #1d4ed8;
        }

        .status-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        }

        .status-box {
        font-size: 14px;
        line-height: 1.7;
        color: #4b5563;
        }

        .status-box strong {
        display: block;
        margin-bottom: 6px;
        color: #111827;
        }

        @media (max-width: 900px) {
        .filters {
            grid-template-columns: 1fr 1fr;
        }

        .status-grid {
            grid-template-columns: 1fr 1fr;
        }

        .table-area {
            overflow-x: auto;
        }

        table {
            min-width: 900px;
        }
        }

        @media (max-width: 600px) {
        .filters {
            grid-template-columns: 1fr;
        }

        .table-top,
        .table-footer,
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .status-grid {
            grid-template-columns: 1fr;
        }
        }
    </style>
    <script>
        
        // 取得訂單細項
        function getOrderContent(orderId){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                url: "{{ route('order.detail', ['id' => ':id']) }}".replace(':id', orderId),
                type: 'POST',
                dataType: 'json',
                data: {
                    orderId: Number(orderId)
                },
                success: function(response) {
                    console.log(response);
                    console.log(response.data);

                    // alert('訂單已通過');
                    if(response.status == 'success'){

                        let tableBody = '';
                        for(const item of response.data.order_items){        
                            console.log(item);
                            $count = item.amount * item.price;
                            tableBody +=`
                            <tr>
                                <td>${item.product.name}</td>
                                <td>${item.amount}</td>
                                <td>${item.product.amount}</td>
                                <td>${item.price}</td>
                                <td>${$count}<td>
                            </tr>`;
                        }

                        Swal.fire({
                            title: "訂單細項",
                            html: `
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <table style="
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: center;
                                        font-size: 14px;
                                    ">
                                        <thead style="background-color: #f8f9fa;">
                                            <tr>
                                                <th style="border: 1px solid #ddd; padding: 8px;">產品名稱</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">訂單數量</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">現有庫存</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">單價</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">小計</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tableBody}
                                        </tbody>
                                    </table>
                                </div>
                            `,
                            cancelButtonAriaLabel: "Thumbs down"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error);
                    alert('訂單出貨失敗');
                }
            });
        }
        
        function decideOrderStatus(approved, id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                url: "{{ route('orders.review', ['id' => ':id']) }}".replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: { 
                    orderId: Number(id),
                    approved: approved
                },
                success: function(response) {
                    console.log(response);
                    // alert('訂單已通過');
                    if(response.status == 'success'){
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error);
                    alert('訂單審核失敗');
                }
            });
        }

        function dispatch(orderId){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                url: "{{ route('orders.dispatch', ['id' => ':id']) }}".replace(':id', orderId),
                type: 'POST',
                dataType: 'json',
                data: { 
                    orderId: Number(orderId)
                },
                success: function(response) {
                    console.log(response);
                    // alert('訂單已通過');
                    if(response.status == 'success'){
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error);
                    alert('訂單出貨失敗');
                }
            })
        }

        // 區間訂單撈取
        function fetchOrdersByDateRange() {
          const startDate = document.querySelector('.filters input[type="date"]:first-child').value;
          const endDate = document.querySelector('.filters input[type="date"]:last-child').value;
        //   let orderTable = $('#orderTable').DataTable();

		  if (!startDate || !endDate) { alert('請選擇開始日期與結束日期'); return; };
		  
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                url: "{{ route('orders.fetchByDateRange') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    console.log(response);
					let orders = response.message || [];
					console.log(response, orders, typeof orders);
					console.log(orders);
					console.log(orders[0]);
					console.log(Array.isArray(orders));
					// 渲染畫面
					if ($.fn.DataTable.isDataTable('#orderTable')) {
						$('#orderTable').DataTable().clear().destroy();
					}

					$('#orderTable tbody').empty();
					orderTable = $('#orderTable').DataTable({ 
						data: orders, 
						destroy: true,
						columns: [
							{ data: 'id' },
							{ data: 'customer.name' },
							{ data: 'status' },
							{ data: 'amount',
								defaultContent: '-'
							},
							{ data: 'orderDeadLine',
								defaultContent: '-'
							},
							{ data: 'created_at' },
							{
								data: 'id',
								render: function(id) {
									return `<button class="btn-detail" onclick="getOrderContent('${id}')">訂單明細</button>`;
								}
							}
						]
					});
					
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error);
                    alert('訂單撈取失敗');
                }
            });
        }
    </script>
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('訂單列表') }}
        </h2>
    </x-slot>

    <section class="card">
      <div class="card-header">
        <div class="breadcrumb">🏠 訂單管理 / 訂單列表</div>
        <a class="btn-primary" href="{{ route('order.new') }}">＋ 新增訂單</a>
      </div>

      <div class="filters">
        <div class="form-group">
          <label>訂單日期</label>
          <div class="date-range">
            <input type="date" value="2025-09-01">
            <span>－</span>
            <input type="date" value="2025-09-30">
          </div>
        </div>

        <div class="form-group">
          <label>狀態</label>
          <select>
            <option>全部</option>
            <option>未審核</option>
            <option>審核通過</option>
            <option>已出貨</option>
            <option>已完成</option>
            <option>已取消</option>
          </select>
        </div>

        <div class="form-group">
          <label>客戶</label>
          <select>
            <option>全部</option>
            <option>陳依</option>
          </select>
        </div>

        <div class="form-group">
          <label>搜尋</label>
          <input type="text" placeholder="訂單編號 / 客戶名稱">
        </div>

        <button class="btn-primary" onclick="fetchOrdersByDateRange()">搜尋</button>
        <button class="btn-secondary">重設</button>
      </div>

      <div class="table-area">

        <table id="orderTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>客戶名稱</th>
              <th>狀態</th>
              <th class="amount">金額</th>
              <th>訂單日期</th>
              <th>建立時間</th>
              <th>操作</th>
            </tr>
          </thead>

          <tbody>
          </tbody>
        </table>

        <!-- <div class="table-footer">
          <div>Showing 1 to 5 of 5 entries</div>

          <div class="pagination">
            <button class="page-btn">«</button>
            <button class="page-btn">‹</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">›</button>
            <button class="page-btn">»</button>
          </div>
        </div> -->
      </div>
    </section>

    <section class="status-help">
      <h3>訂單狀態說明</h3>

      <div class="status-grid">
        <div class="status-box">
          <strong><span class="status status-pending">未審核</span></strong>
          訂單剛建立，尚未送出審核，可編輯與刪除。
        </div>

        <div class="status-box">
          <strong><span class="status status-approved">審核通過</span></strong>
          主管已核准，等待出貨，僅能查看明細。
        </div>

        <div class="status-box">
          <strong><span class="status status-shipped">已出貨</span></strong>
          訂單已出貨，庫存已扣除，僅能查看明細。
        </div>

        <div class="status-box">
          <strong><span class="status">已完成</span></strong>
          訂單流程已完成，可查看明細與列印。
        </div>
      </div>
    </section>
</x-app-layout>
