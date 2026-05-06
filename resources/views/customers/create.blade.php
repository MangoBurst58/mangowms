@extends('layouts.app')

@section('title', 'Add Customer | MangoWMS')
@section('title', 'Add Customer')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-plus mr-2 text-indigo-400"></i>
            Add New Customer
        </h1>
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Customer Code <span class="text-gray-500">(Auto-generated)</span>
    </label>
    <input type="text" name="code" value="{{ old('code') }}" 
           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-gray-400 focus:border-indigo-500 focus:outline-none cursor-not-allowed"
           placeholder="Leave empty to auto-generate" readonly>
    <p class="text-xs text-gray-500 mt-1">
        <i class="fas fa-magic mr-1"></i> Auto-generated format: CUST-0001
    </p>
    @error('code') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Customer Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required>
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tax ID (NPWP)</label>
                    <input type="text" name="tax_id" value="{{ old('tax_id') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="3" 
                              class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">{{ old('address') }}</textarea>
                </div>
                
                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-slate-700 rounded focus:ring-indigo-500">
                    <label class="text-sm font-medium text-gray-300">Active</label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-700">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Save Customer
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection