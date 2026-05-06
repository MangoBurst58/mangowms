@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-history mr-2 text-indigo-400"></i>
            Audit Logs
        </h1>
    </div>
    
    <!-- Filter Form -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-4">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Module</label>
                <select name="module" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                        {{ ucfirst($module) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Action</label>
                <select name="action" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">User</label>
                <select name="user_id" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
            </div>
            <div class="md:col-span-5 flex justify-end gap-3">
                <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Logs Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700" style="min-width: 900px">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Time</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Action</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Module</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">IP Address</th>
                        <th class="px-4 py-3 text-center text-xs text-gray-400 uppercase">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-4 py-3 whitespace-nowrap text-white text-sm">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-300 text-sm">
                            {{ $log->user->name ?? 'System' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $badgeClass = match($log->action) {
                                    'create' => 'bg-emerald-900/50 text-emerald-300',
                                    'update' => 'bg-blue-900/50 text-blue-300',
                                    'delete' => 'bg-red-900/50 text-red-300',
                                    'login' => 'bg-indigo-900/50 text-indigo-300',
                                    'logout' => 'bg-yellow-900/50 text-yellow-300',
                                    default => 'bg-gray-900/50 text-gray-300',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $badgeClass }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-300 text-sm">
                            {{ ucfirst($log->module) }}
                        </td>
                        <td class="px-4 py-3 text-gray-300 text-sm max-w-xs truncate" title="{{ $log->description }}">
                            {{ Str::limit($log->description, 50) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-400 text-sm">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <a href="{{ route('activity-logs.show', $log) }}" class="text-indigo-400 hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            <i class="fas fa-history text-4xl mb-3 block"></i>
                            No activity logs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
    
</div>
@endsection