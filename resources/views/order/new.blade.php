@section('jshead')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const products = @json($productjs);
        const itemsBody = document.getElementById('items');
        const itemTemplate = document.getElementById('item-row-template');
        const addItemButton = document.getElementById('add-item');
        const orderTotal = document.getElementById('order-total');
        const money = new Intl.NumberFormat('zh-TW');
        let nextRowIndex = itemsBody.querySelectorAll('.item-row').length;

        const productPrice = (productId) => Number(products[productId]?.price ?? 0);

        function updateRow(row) {
            const productId = row.querySelector('.product-select').value;
            const quantity = Math.max(1, Number.parseInt(row.querySelector('.quantity-input').value, 10) || 1);
            const price = productPrice(productId);

            row.querySelector('.quantity-input').value = quantity;
            row.querySelector('.unit-price').textContent = `$${money.format(price)}`;
            row.querySelector('.subtotal').textContent = `$${money.format(price * quantity)}`;
            row.dataset.subtotal = price * quantity;
        }

        function updateTotal() {
            const total = [...itemsBody.querySelectorAll('.item-row')]
                .reduce((sum, row) => sum + Number(row.dataset.subtotal || 0), 0);

            orderTotal.textContent = `$${money.format(total)}`;
        }

        function refreshRow(row) {
            updateRow(row);
            updateTotal();
        }

        function updateRemoveButtons() {
            const rows = itemsBody.querySelectorAll('.item-row');
            rows.forEach((row) => {
                const button = row.querySelector('.remove-item');
                button.disabled = rows.length === 1;
                button.classList.toggle('opacity-40', rows.length === 1);
                button.classList.toggle('cursor-not-allowed', rows.length === 1);
            });
        }

        addItemButton.addEventListener('click', () => {
            const row = itemTemplate.content.firstElementChild.cloneNode(true);
            row.querySelector('.product-select').name = `products[${nextRowIndex}][product_id]`;
            row.querySelector('.quantity-input').name = `products[${nextRowIndex}][quantities]`;
            nextRowIndex += 1;

            itemsBody.appendChild(row);
            refreshRow(row);
            updateRemoveButtons();
        });

        itemsBody.addEventListener('change', (event) => {
            if (event.target.matches('.product-select, .quantity-input')) {
                refreshRow(event.target.closest('.item-row'));
            }
        });

        itemsBody.addEventListener('input', (event) => {
            if (event.target.matches('.quantity-input')) {
                refreshRow(event.target.closest('.item-row'));
            }
        });

        itemsBody.addEventListener('click', (event) => {
            const button = event.target.closest('.remove-item');
            if (!button || button.disabled) return;

            button.closest('.item-row').remove();
            updateTotal();
            updateRemoveButtons();
        });

        itemsBody.querySelectorAll('.item-row').forEach(updateRow);
        updateTotal();
        updateRemoveButtons();
    });
</script>
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新增訂單') }}
        </h2>
    </x-slot>

    <form action="{{ route('order.store') }}" method="post">
        @csrf

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold mb-4">新增訂單</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="customer" class="block text-sm font-medium text-gray-700">客戶</label>
                    <select id="customer" name="customer" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                        <option value="">請選擇客戶</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer') == $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">訂單日期</label>
                    <input type="date" id="date" name="date" value="{{ old('date', now()->toDateString()) }}"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                </div>
            </div>

            <div class="overflow-x-auto mb-3">
                <table class="w-full min-w-[720px] text-sm border border-gray-300">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="border p-2 text-left">商品</th>
                            <th class="border p-2 text-right w-28">數量</th>
                            <th class="border p-2 text-right w-32">單價</th>
                            <th class="border p-2 text-right w-36">小計</th>
                            <th class="border p-2 text-center w-24">操作</th>
                        </tr>
                    </thead>
                    <tbody id="items">
                        @php($oldProducts = old('products', [['product_id' => $products->first()?->id, 'quantities' => 1]]))
                        @foreach($oldProducts as $index => $oldProduct)
                            <tr class="item-row">
                                <td class="border p-2">
                                    <select name="products[{{ $index }}][product_id]" class="product-select w-full border-gray-300 rounded" required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" @selected(($oldProduct['product_id'] ?? null) == $product->id)>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border p-2 text-right">
                                    <input type="number" name="products[{{ $index }}][quantities]"
                                           value="{{ $oldProduct['quantities'] ?? 1 }}" min="1" step="1"
                                           class="quantity-input w-20 text-right border-gray-300 rounded" required>
                                </td>
                                <td class="unit-price border p-2 text-right whitespace-nowrap">$0</td>
                                <td class="subtotal border p-2 text-right font-medium whitespace-nowrap">$0</td>
                                <td class="border p-2 text-center">
                                    <button type="button" class="remove-item text-red-600 hover:text-red-800 font-medium">移除</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="button" id="add-item"
                    class="inline-flex items-center px-3 py-2 mb-5 text-sm font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                ＋ 新增品項
            </button>

            <div class="flex justify-end mb-6">
                <div class="w-full sm:w-72 flex justify-between border-t border-gray-300 pt-3 text-base font-semibold">
                    <span>訂單總額</span>
                    <span id="order-total">$0</span>
                </div>
            </div>

            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700">備註</label>
                <textarea id="note" name="note" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('note') }}</textarea>
            </div>

            <div class="flex justify-end">
                
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500
                        bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 ">
                    新增
                </button>
            </div>
        </div>
    </form>

    <template id="item-row-template">
        <tr class="item-row">
            <td class="border p-2">
                <select class="product-select w-full border-gray-300 rounded" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="border p-2 text-right">
                <input type="number" value="1" min="1" step="1"
                       class="quantity-input w-20 text-right border-gray-300 rounded" required>
            </td>
            <td class="unit-price border p-2 text-right whitespace-nowrap">$0</td>
            <td class="subtotal border p-2 text-right font-medium whitespace-nowrap">$0</td>
            <td class="border p-2 text-center">
                <button type="button" class="remove-item text-red-600 hover:text-red-800 font-medium">移除</button>
            </td>
        </tr>
    </template>
</x-app-layout>