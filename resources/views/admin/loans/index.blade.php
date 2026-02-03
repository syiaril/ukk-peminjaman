@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manage All Loans</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                &larr; Back to Dashboard
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tool</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dates</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="font-bold">{{ $loan->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $loan->user->email }}</div>
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $loan->tool->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="text-xs">Borrow: {{ $loan->borrow_date }}</div>
                            <div class="text-xs font-bold">Due: {{ $loan->expected_return_date }}</div>
                            @if($loan->actual_return_date)
                                <div class="text-xs text-green-600">Returned: {{ $loan->actual_return_date }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($loan->status === 'returning' ? 'bg-purple-100 text-purple-800 border-2 border-purple-300' : 
                                   ($loan->status === 'returned' ? 'bg-blue-100 text-blue-800' : 
                                   ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                            @if($loan->fine > 0)
                                <div class="text-xs text-red-600 mt-1 font-bold">Fine: Rp {{ number_format($loan->fine) }}</div>
                            @endif
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs">
                            @if($loan->status === 'pending')
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-800 border border-gray-800 px-3 py-1 rounded-sm hover:bg-gray-800 hover:text-white transition-colors">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-400 border border-gray-400 px-3 py-1 rounded-sm hover:bg-gray-400 hover:text-white transition-colors">Reject</button>
                                    </form>
                                </div>
                            @elseif($loan->status === 'approved')
                                <span class="text-gray-400 italic text-xs">Waiting for return request</span>
                            @elseif($loan->status === 'returning')
                                <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST" onsubmit="return confirm('Confirm return and restore stock?');">
                                    @csrf
                                    <button class="font-bold text-purple-700 border-2 border-purple-500 px-3 py-1 rounded-sm hover:bg-purple-700 hover:text-white transition-colors">
                                        Confirm Return
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300 italic">Done</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
