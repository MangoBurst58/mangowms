@extends('layouts.app')

@section('title', 'New Stock Out')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('stock-out.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-arrow-up mr-2 text-red-400"></i>
            New Stock Out
        </h1>
    </div>
    
    <form action="{{ route('stock-out.store') }}" method="POST" id="stockOutForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Header Information -->
                <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Order Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Customer *</label>
                            <select name="customer_id" class="customer-select w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white" required style="width: 100%">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->code }} - {{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Warehouse *</label>
                            <select name="warehouse_id" class="warehouse-select w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white" required style="width: 100%">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Delivery Date *</label>
                            <input type="date" name="delivery_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                            <textarea name="notes" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Items Section -->
                <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-white">Items *</h2>
                        <button type="button" id="addItemBtn" class="text-indigo-400 hover:text-indigo-300">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                    
                    <div id="itemsContainer">
                        <div class="item-row bg-slate-900/50 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Product *</label>
                                    <select name="items[0][product_id]" class="product-select w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required style="width: 100%">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->total_stock }}">
                                            {{ $product->sku }} - {{ $product->name }} (Stock: {{ $product->total_stock }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" class="qty-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="1">
                                    <div class="stock-warning text-xs mt-1 hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Right Column - Summary -->
            <div class="lg:col-span-1">
                <div class="bg-slate-800 rounded-xl border border-slate-700 p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Items:</span>
                            <span id="totalItems" class="text-white font-semibold">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Quantity:</span>
                            <span id="totalQuantity" class="text-white font-semibold">0</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-slate-700">
                            <span class="text-gray-400">Total Amount:</span>
                            <span id="totalAmount" class="text-white text-xl font-bold">Rp 0</span>
                        </div>
                    </div>
                    
                    <div id="globalError" class="hidden mt-4 p-3 bg-red-900/50 border border-red-700 rounded-lg text-red-300 text-sm text-center"></div>
                    
                    <button type="submit" id="submitBtn" class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i> Process Stock Out
                    </button>
                </div>
            </div>
            
        </div>
    </form>
    
</div>

<script>
    let itemIndex = 1;
    let stockValid = true;
    
    // Initialize Select2 on page load
    $(document).ready(function() {
        $('.customer-select').select2({
            placeholder: 'Search customer...',
            allowClear: true,
            width: '100%'
        });
        
        $('.warehouse-select').select2({
            placeholder: 'Search warehouse...',
            allowClear: true,
            width: '100%'
        });
        
        $('.product-select').select2({
            placeholder: 'Search product...',
            allowClear: true,
            width: '100%'
        });
    });
    
    function validateStock(row) {
        const productSelect = row.querySelector('.product-select');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption?.dataset.stock) || 0;
        const qtyInput = row.querySelector('.qty-input');
        const quantity = parseInt(qtyInput.value) || 0;
        const warningDiv = row.querySelector('.stock-warning');
        
        if (quantity > stock) {
            warningDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle mr-1"></i> 
                Insufficient stock! Available: ${stock} units
            `;
            warningDiv.classList.remove('hidden');
            warningDiv.classList.remove('text-emerald-400');
            warningDiv.classList.add('text-red-400');
            qtyInput.classList.add('border-red-500');
            stockValid = false;
        } else if (quantity > 0 && quantity <= stock) {
            warningDiv.innerHTML = `
                <i class="fas fa-check-circle mr-1"></i> 
                Stock available: ${stock} units
            `;
            warningDiv.classList.remove('hidden');
            warningDiv.classList.remove('text-red-400');
            warningDiv.classList.add('text-emerald-400');
            qtyInput.classList.remove('border-red-500');
        } else {
            warningDiv.classList.add('hidden');
            qtyInput.classList.remove('border-red-500');
        }
    }
    
    function checkAllStockValid() {
        stockValid = true;
        document.querySelectorAll('.item-row').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const stock = parseInt(selectedOption?.dataset.stock) || 0;
            const qtyInput = row.querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value) || 0;
            
            if (quantity > stock) {
                stockValid = false;
            }
        });
        
        const submitBtn = document.getElementById('submitBtn');
        const globalError = document.getElementById('globalError');
        
        if (stockValid) {
            submitBtn.disabled = false;
            globalError.classList.add('hidden');
        } else {
            submitBtn.disabled = true;
            globalError.classList.remove('hidden');
            globalError.innerHTML = `
                <i class="fas fa-times-circle mr-2"></i> 
                Cannot submit: Some items exceed available stock
            `;
        }
    }
    
    function updateSummary() {
        let totalItems = 0;
        let totalQuantity = 0;
        let totalAmount = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            totalItems++;
            const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
            const productSelect = row.querySelector('.product-select');
            const price = parseFloat(productSelect?.options[productSelect.selectedIndex]?.dataset.price) || 0;
            totalQuantity += qty;
            totalAmount += qty * price;
            
            validateStock(row);
        });
        
        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalQuantity').textContent = totalQuantity;
        document.getElementById('totalAmount').innerHTML = 'Rp ' + totalAmount.toLocaleString('id-ID');
        
        checkAllStockValid();
    }
    
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'item-row bg-slate-900/50 rounded-lg p-4 mb-4';
        newRow.innerHTML = `
            <div class="flex justify-end mb-2">
                <button type="button" class="remove-item-btn text-red-400 hover:text-red-300 text-sm">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Product *</label>
                    <select name="items[${itemIndex}][product_id]" class="product-select w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required style="width: 100%">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->total_stock }}">
                            {{ $product->sku }} - {{ $product->name }} (Stock: {{ $product->total_stock }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Quantity *</label>
                    <input type="number" name="items[${itemIndex}][quantity]" class="qty-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="1">
                    <div class="stock-warning text-xs mt-1 hidden"></div>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        
        // Initialize Select2 for the new row
        $(newRow).find('.product-select').select2({
            placeholder: 'Search product...',
            allowClear: true,
            width: '100%'
        });
        
        itemIndex++;
        attachRemoveEvents();
        attachInputEvents();
        updateSummary();
    });
    
    function attachRemoveEvents() {
        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.removeEventListener('click', removeItem);
            btn.addEventListener('click', removeItem);
        });
    }
    
    function removeItem(e) {
        if (document.querySelectorAll('.item-row').length > 1) {
            e.target.closest('.item-row').remove();
            updateSummary();
        } else {
            alert('At least one item is required');
        }
    }
    
    function attachInputEvents() {
        document.querySelectorAll('.qty-input, .product-select').forEach(input => {
            input.removeEventListener('change', updateSummary);
            input.removeEventListener('input', updateSummary);
            input.addEventListener('change', updateSummary);
            input.addEventListener('input', updateSummary);
        });
    }
    
    attachRemoveEvents();
    attachInputEvents();
    updateSummary();
</script>
@endsection