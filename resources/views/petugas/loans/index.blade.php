@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Manage Loans</h2>
            <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                &larr; Back to Dashboard
            </a>
        </div>
        
        <div class="mb-6">
            <a href="{{ route('petugas.report') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-black rounded text-xs font-bold hover:bg-black hover:text-white transition-colors">
                Print Report
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
                            {{ $loan->user->name }}
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $loan->tool->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="text-xs">Borrow: {{ $loan->borrow_date }}</div>
                            <div class="text-xs font-bold">Due: {{ $loan->expected_return_date }}</div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($loan->status === 'returning' ? 'bg-purple-100 text-purple-800 border-2 border-purple-300' : 
                                   ($loan->status === 'returned' ? 'bg-blue-100 text-blue-800' : 
                                   ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @if($loan->status === 'pending')
                                    <form action="{{ route('petugas.loans.approve', $loan->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-800 border border-gray-800 px-3 py-1 rounded-sm hover:bg-gray-800 hover:text-white transition-colors">Approve</button>
                                    </form>
                                    <form action="{{ route('petugas.loans.reject', $loan->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-400 border border-gray-400 px-3 py-1 rounded-sm hover:bg-gray-400 hover:text-white transition-colors">Reject</button>
                                    </form>
                            @elseif($loan->status === 'approved')
                                <span class="text-gray-400 italic text-xs">Waiting for return request</span>
                            @elseif($loan->status === 'returning')
                                <form action="{{ route('petugas.loans.return', $loan->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirm return and restore stock?');">
                                    @csrf
                                    <button class="font-bold text-purple-700 border-2 border-purple-500 px-3 py-1 rounded-sm hover:bg-purple-700 hover:text-white transition-colors text-xs">
                                        Confirm Return
                                    </button>
                                </form>
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
