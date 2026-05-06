@extends('layouts.app')

@section('title', 'New Stock In')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('stock-in.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-arrow-down mr-2 text-emerald-400"></i>
            New Stock In
        </h1>
    </div>
    
    <form action="{{ route('stock-in.store') }}" method="POST" id="stockInForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Header Information -->
                <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Receipt Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Supplier *</label>
                            <select name="supplier_id" class="supplier-select w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white" required style="width: 100%">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->code }} - {{ $supplier->name }}</option>
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
                            <label class="block text-sm font-medium text-gray-300 mb-2">Receipt Date *</label>
                            <input type="date" name="receipt_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                            <textarea name="notes" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Items Section with Unit Conversion -->
                <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-white">Items *</h2>
                        <button type="button" id="addItemBtn" class="text-indigo-400 hover:text-indigo-300">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                    
                    <div id="itemsContainer">
                        <div class="item-row bg-slate-900/50 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Product *</label>
                                    <select name="items[0][product_id]" class="product-select w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required style="width: 100%">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-base-unit="{{ $product->base_unit ?? 'pcs' }}">
                                            {{ $product->sku }} - {{ $product->name }} (Base Unit: {{ $product->base_unit ?? 'pcs' }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Purchase Unit *</label>
                                    <select name="items[0][purchase_unit]" class="purchase-unit w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required>
                                        <option value="pcs">Pieces (pcs)</option>
                                        <option value="box">Box</option>
                                        <option value="carton">Carton</option>
                                        <option value="dus">Dus</option>
                                        <option value="pack">Pack</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" class="qty-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="1">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Unit Price *</label>
                                    <input type="number" name="items[0][unit_price]" step="0.01" class="price-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="0">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Conversion Factor *</label>
                                    <input type="number" name="items[0][conversion_factor]" step="0.001" class="conversion-factor w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="0.001" placeholder="e.g., 1 box = ? pcs">
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-calculator mr-1"></i> 1 unit = ? base unit
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Batch Number</label>
                                    <input type="text" name="items[0][batch_number]" class="batch-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-gray-300" placeholder="Leave empty to auto-generate">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Expiry Date</label>
                                    <input type="date" name="items[0][expiry_date]" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-item-btn text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <div class="conversion-info text-xs text-emerald-400 mt-2 hidden">
                                <i class="fas fa-exchange-alt mr-1"></i> 
                                Will be converted to base unit
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
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Base Units:</span>
                            <span id="totalBaseUnits" class="text-white font-semibold">0</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-slate-700">
                            <span class="text-gray-400">Total Amount:</span>
                            <span id="totalAmount" class="text-white text-xl font-bold">Rp 0</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full mt-6 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i> Process Stock In
                    </button>
                </div>
            </div>
            
        </div>
    </form>
    
</div>

<script>
    let itemIndex = 1;
    
    // Initialize Select2 on page load
    $(document).ready(function() {
        $('.supplier-select').select2({
            placeholder: 'Search supplier...',
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
    
    function updateSummary() {
        let totalItems = 0;
        let totalQuantity = 0;
        let totalBaseUnits = 0;
        let totalAmount = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            totalItems++;
            const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
            const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
            const factor = parseFloat(row.querySelector('.conversion-factor')?.value) || 1;
            
            totalQuantity += qty;
            totalBaseUnits += qty * factor;
            totalAmount += qty * price;
            
            // Show conversion info
            const convInfo = row.querySelector('.conversion-info');
            const purchaseUnit = row.querySelector('.purchase-unit')?.value || 'unit';
            const productSelect = row.querySelector('.product-select');
            const baseUnit = productSelect.options[productSelect.selectedIndex]?.dataset.baseUnit || 'pcs';
            
            if (qty > 0 && factor > 0) {
                convInfo.innerHTML = `<i class="fas fa-exchange-alt mr-1"></i> ${qty} ${purchaseUnit} × ${factor} = ${(qty * factor).toLocaleString()} ${baseUnit}`;
                convInfo.classList.remove('hidden');
            } else {
                convInfo.classList.add('hidden');
            }
        });
        
        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalQuantity').textContent = totalQuantity;
        document.getElementById('totalBaseUnits').textContent = totalBaseUnits.toLocaleString();
        document.getElementById('totalAmount').innerHTML = 'Rp ' + totalAmount.toLocaleString('id-ID');
    }
    
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'item-row bg-slate-900/50 rounded-lg p-4 mb-4';
        newRow.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Product *</label>
                    <select name="items[${itemIndex}][product_id]" class="product-select w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required style="width: 100%">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-base-unit="{{ $product->base_unit ?? 'pcs' }}">
                            {{ $product->sku }} - {{ $product->name }} (Base Unit: {{ $product->base_unit ?? 'pcs' }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Purchase Unit *</label>
                    <select name="items[${itemIndex}][purchase_unit]" class="purchase-unit w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required>
                        <option value="pcs">Pieces (pcs)</option>
                        <option value="box">Box</option>
                        <option value="carton">Carton</option>
                        <option value="dus">Dus</option>
                        <option value="pack">Pack</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Quantity *</label>
                    <input type="number" name="items[${itemIndex}][quantity]" class="qty-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="1">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Unit Price *</label>
                    <input type="number" name="items[${itemIndex}][unit_price]" step="0.01" class="price-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="0">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Conversion Factor *</label>
                    <input type="number" name="items[${itemIndex}][conversion_factor]" step="0.001" class="conversion-factor w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white" required min="0.001" placeholder="e.g., 1 box = ? pcs">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-calculator mr-1"></i> 1 unit = ? base unit
                    </p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Batch Number</label>
                    <input type="text" name="items[${itemIndex}][batch_number]" class="batch-input w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-gray-300" placeholder="Leave empty to auto-generate">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Expiry Date</label>
                    <input type="date" name="items[${itemIndex}][expiry_date]" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white">
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-item-btn text-red-400 hover:text-red-300">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
            <div class="conversion-info text-xs text-emerald-400 mt-2 hidden">
                <i class="fas fa-exchange-alt mr-1"></i> 
                Will be converted to base unit
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
        document.querySelectorAll('.qty-input, .price-input, .conversion-factor, .purchase-unit, .product-select').forEach(input => {
            input.removeEventListener('input', updateSummary);
            input.removeEventListener('change', updateSummary);
            input.addEventListener('input', updateSummary);
            input.addEventListener('change', updateSummary);
        });
    }
    
    attachRemoveEvents();
    attachInputEvents();
    updateSummary();
</script>
@endsection