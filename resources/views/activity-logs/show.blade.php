@extends('layouts.app')

@section('title', 'Audit Log Detail')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('activity-logs.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-history mr-2 text-indigo-400"></i>
            Audit Log Detail
        </h1>
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        
        <!-- Basic Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            <div class="p-6 border-b md:border-b-0 md:border-r border-slate-700">
                <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-400">Time</p>
                        <p class="text-white">{{ $activityLog->created_at->format('d M Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">User</p>
                        <p class="text-white">{{ $activityLog->user->name ?? 'System' }} ({{ $activityLog->user->email ?? '-' }})</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Action</p>
                        <p class="text-white">{{ ucfirst($activityLog->action) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Module</p>
                        <p class="text-white">{{ ucfirst($activityLog->module) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Record ID</p>
                        <p class="text-white">{{ $activityLog->record_id ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Technical Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-400">IP Address</p>
                        <p class="text-white">{{ $activityLog->ip_address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">User Agent</p>
                        <p class="text-white text-sm break-all">{{ $activityLog->user_agent ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Description</p>
                        <p class="text-white">{{ $activityLog->description ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Old Data -->
        @if($activityLog->old_data)
        <div class="border-t border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Before Changes (Old Data)</h2>
            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-gray-300 text-sm">{{ json_encode($activityLog->old_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
        
        <!-- New Data -->
        @if($activityLog->new_data)
        <div class="border-t border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">After Changes (New Data)</h2>
            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-gray-300 text-sm">{{ json_encode($activityLog->new_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
        
    </div>
    
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Logs
        </a>
    </div>
    
</div>
@endsection