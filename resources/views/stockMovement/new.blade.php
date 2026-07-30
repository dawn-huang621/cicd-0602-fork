@section('jshead')
<style>
    * {
    box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f3f6fc;
        color: #10233f;
        font-family:
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            "Microsoft JhengHei",
            sans-serif;
    }

    .inventory-page {
        width: 100%;
        padding: 30px 14px;
    }

    .page-heading {
        margin-bottom: 24px;
    }

    .page-heading h1 {
        margin: 0 0 8px;
        color: #10233f;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.4;
    }

    .page-heading p {
        margin: 0;
        color: #65738a;
        font-size: 16px;
        line-height: 1.6;
    }

    .inventory-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #d9e2ef;
        border-radius: 9px;
        box-shadow: 0 2px 8px rgba(34, 61, 102, 0.04);
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 88px;
        padding: 20px 26px;
        border-bottom: 1px solid #e2e8f0;
    }

    .breadcrumb-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #10233f;
        font-size: 16px;
        font-weight: 700;
    }

    .breadcrumb-icon {
        font-size: 17px;
    }

    .card-body {
        padding: 28px 26px 34px;
    }

    .form-section-title {
        margin: 0 0 18px;
        padding-left: 11px;
        color: #172b4d;
        border-left: 4px solid #315be8;
        font-size: 16px;
        font-weight: 700;
    }

    .form-section-title:not(:first-child) {
        margin-top: 32px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 20px;
    }

    .form-group {
        width: 100%;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #263b5a;
        font-size: 14px;
        font-weight: 500;
    }

    .required {
        color: #e04444;
    }

    .form-control {
        display: block;
        width: 100%;
        min-height: 42px;
        padding: 9px 13px;
        color: #22334d;
        background-color: #ffffff;
        border: 1px solid #ccd8e8;
        border-radius: 7px;
        outline: none;
        font-size: 14px;
        line-height: 1.5;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .form-control::placeholder {
        color: #9ba8b9;
    }

    .form-control:hover {
        border-color: #aebed3;
    }

    .form-control:focus {
        border-color: #315be8;
        box-shadow: 0 0 0 3px rgba(49, 91, 232, 0.12);
    }

    select.form-control {
        cursor: pointer;
    }

    .textarea-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-control.is-invalid {
        border-color: #df4b4b;
    }

    .form-control.is-invalid:focus {
        border-color: #df4b4b;
        box-shadow: 0 0 0 3px rgba(223, 75, 75, 0.12);
    }

    .error-message {
        margin-top: 6px;
        color: #df4b4b;
        font-size: 13px;
    }

    .product-info-box {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0;
        margin: 20px 0 24px;
        background: #f8faff;
        border: 1px solid #dce5f2;
        border-radius: 8px;
    }

    .product-info-item {
        padding: 16px 18px;
        border-right: 1px solid #dce5f2;
    }

    .product-info-item:last-child {
        border-right: none;
    }

    .info-label {
        display: block;
        margin-bottom: 6px;
        color: #74839a;
        font-size: 13px;
    }

    .info-value {
        display: block;
        color: #172b4d;
        font-size: 16px;
        font-weight: 700;
    }

    .warning-box {
        margin-top: 18px;
        padding: 12px 15px;
        color: #a15f00;
        background: #fff7db;
        border: 1px solid #f4dfa1;
        border-radius: 7px;
        font-size: 14px;
    }

    .d-none {
        display: none;
    }

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 26px;
        background: #fbfcfe;
        border-top: 1px solid #e2e8f0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 96px;
        min-height: 42px;
        padding: 9px 18px;
        border: 1px solid transparent;
        border-radius: 7px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        text-decoration: none;
        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            transform 0.1s ease;
    }

    .btn:active {
        transform: translateY(1px);
    }

    .btn-primary {
        color: #ffffff;
        background: #315be8;
        border-color: #315be8;
    }

    .btn-primary:hover {
        background: #244bd1;
        border-color: #244bd1;
    }

    .btn-light {
        color: #42526b;
        background: #f1f3f7;
        border-color: #e1e6ee;
    }

    .btn-light:hover {
        background: #e6eaf0;
        border-color: #d6dde8;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group-full {
            grid-column: auto;
        }

        .product-info-box {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .product-info-item:nth-child(2) {
            border-right: none;
        }

        .product-info-item:nth-child(-n + 2) {
            border-bottom: 1px solid #dce5f2;
        }
    }

    @media (max-width: 576px) {
        .inventory-page {
            padding: 20px 10px;
        }

        .page-heading h1 {
            font-size: 23px;
        }

        .card-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 18px;
        }

        .card-body {
            padding: 22px 18px 28px;
        }

        .product-info-box {
            grid-template-columns: 1fr;
        }

        .product-info-item {
            border-right: none;
            border-bottom: 1px solid #dce5f2;
        }

        .product-info-item:last-child {
            border-bottom: none;
        }

        .card-footer {
            padding: 18px;
        }

        .btn {
            flex: 1;
        }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');

    const pageTitle = document.getElementById('pageTitle');
    const quantityLabel = document.getElementById('quantityLabel');
    const submitButton = document.getElementById('submitButton');
    const currentStockText = document.getElementById('currentStockText');
    const outboundWarning = document.getElementById('outboundWarning');

    function updateFormByType() {
        const type = typeSelect.value;

        if (type === 'in') {
            pageTitle.textContent = '建立入庫單';
            quantityLabel.textContent = '入庫數量';
            submitButton.textContent = '確認入庫';
            outboundWarning.classList.add('d-none');

            quantityInput.removeAttribute('max');
        } else if (type === 'out') {
            pageTitle.textContent = '建立出庫單';
            quantityLabel.textContent = '出庫數量';
            submitButton.textContent = '確認出庫';
            outboundWarning.classList.remove('d-none');

            updateCurrentStock();
        } else {
            pageTitle.textContent = '建立庫存異動';
            quantityLabel.textContent = '異動數量';
            submitButton.textContent = '儲存庫存異動';
            outboundWarning.classList.add('d-none');

            quantityInput.removeAttribute('max');
        }
    }

    function updateCurrentStock() {
        const selectedOption =
            productSelect.options[productSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            currentStockText.textContent = '請先選擇商品';
            quantityInput.removeAttribute('max');
            return;
        }

        const currentStock =
            Number(selectedOption.dataset.stock ?? 0);

        currentStockText.textContent =
            `目前庫存：${currentStock}`;

        if (typeSelect.value === 'out') {
            quantityInput.max = currentStock;
        } else {
            quantityInput.removeAttribute('max');
        }
    }

    typeSelect.addEventListener('change', updateFormByType);
    productSelect.addEventListener('change', updateCurrentStock);

    updateFormByType();
    updateCurrentStock();
});
</script>
@endSection


<x-app-layout>
    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0" id="pageTitle">建立庫存異動</h4>
            </div>

            <div class="card-body">
                <form
                    id="stockMovementForm"
                    action="{{ route('stock_movement.store') }}"
                    method="POST"
                >
                    @csrf

                    {{-- 入庫／出庫類型 --}}
                    <div class="mb-3">
                        <label for="type" class="form-label">
                            異動類型
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select @error('type') is-invalid @enderror"
                            id="type"
                            name="type"
                            required
                        >
                            <option value="">請選擇異動類型</option>

                            <option
                                value="in"
                                @selected(old('type', request('type')) === 'in')
                            >
                                入庫
                            </option>

                            <option
                                value="out"
                                @selected(old('type', request('type')) === 'out')
                            >
                                出庫
                            </option>
                        </select>

                        @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- 商品 --}}
                    <div class="mb-3">
                        <label for="product_id" class="form-label">
                            商品
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select @error('product_id') is-invalid @enderror"
                            id="product_id"
                            name="product_id"
                            required
                        >
                            <option value="">請選擇商品</option>

                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-stock="{{ $product->stock?->quantity ?? 0 }}"
                                    @selected(old('product_id') == $product->id)
                                >
                                    {{ $product->name }}
                                    （目前庫存：{{ $product->stock?->quantity ?? 0 }}）
                                </option>
                            @endforeach
                        </select>

                        @error('product_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text" id="currentStockText">
                            請先選擇商品
                        </div>
                    </div>

                    {{-- 異動數量 --}}
                    <div class="mb-3">
                        <label for="quantity" class="form-label">
                            <span id="quantityLabel">異動數量</span>
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            class="form-control @error('quantity') is-invalid @enderror"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity') }}"
                            min="1"
                            step="1"
                            placeholder="請輸入數量"
                            required
                        >

                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- 異動日期 --}}
                    <div class="mb-3">
                        <label for="movement_date" class="form-label">
                            異動日期
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            class="form-control @error('movement_date') is-invalid @enderror"
                            id="movement_date"
                            name="movement_date"
                            value="{{ old('movement_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        @error('movement_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- 備註 --}}
                    <div class="mb-3">
                        <label for="remark" class="form-label">
                            備註
                        </label>

                        <textarea
                            class="form-control @error('remark') is-invalid @enderror"
                            id="remark"
                            name="remark"
                            rows="3"
                            maxlength="500"
                            placeholder="可填寫入庫或出庫原因"
                        >{{ old('remark') }}</textarea>

                        @error('remark')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- 出庫警告 --}}
                    <div
                        id="outboundWarning"
                        class="alert alert-warning d-none"
                    >
                        出庫數量不可超過目前庫存。
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a
                            href="{{ route('stock_movement.index') }}"
                            class="btn btn-secondary"
                        >
                            取消
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="submitButton"
                        >
                            儲存庫存異動
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
