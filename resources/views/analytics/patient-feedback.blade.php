@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $patient->name }}'s Feedback</h1>
                    <p class="text-gray-600 mt-2">Total feedback: {{ $feedbacks->total() }}</p>
                </div>
                <a href="{{ route('analytics.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="space-y-4">
            @forelse($feedbacks as $feedback)
                <div class="bg-white rounded-lg shadow p-6 border-l-4 {{ $feedback->anonymous ? 'border-gray-400' : 'border-blue-400' }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            @if($feedback->anonymous)
                                <p class="text-sm text-gray-500 italic">Anonymous Feedback 🔐</p>
                            @else
                                <h3 class="font-bold text-gray-800">
                                    {{ $feedback->sender->name }}
                                    @if($feedback->sender->role === 'dokter')
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Doctor</span>
                                    @endif
                                </h3>
                            @endif
                            <p class="text-xs text-gray-500">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        @if($feedback->rating)
                            <div class="text-right">
                                <div class="text-lg">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <span>⭐</span>
                                        @else
                                            <span class="opacity-30">⭐</span>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-600">{{ $feedback->rating }}/5</p>
                            </div>
                        @endif
                    </div>

                    <p class="text-gray-700 mb-3">{{ $feedback->message }}</p>

                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="space-x-3">
                            @if($feedback->follow_up_sent)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">✓ Follow-up sent</span>
                            @else
                                <a href="#" class="bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200">
                                    Send Follow-up
                                </a>
                            @endif
                        </div>
                        <span>{{ $feedback->is_read ? '✓ Read' : 'Unread' }}</span>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <p class="text-gray-500">No feedback received yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($feedbacks->hasPages())
            <div class="mt-6">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
