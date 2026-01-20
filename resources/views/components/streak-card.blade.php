<div class="bg-gradient-to-r from-orange-400 to-red-500 rounded-lg p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-4xl font-bold">{{ $user->streak_days }}</h3>
            <p class="text-orange-100 text-lg">Day Streak</p>
            <p class="text-orange-100 text-sm mt-2">Keep it up! 🔥</p>
        </div>
        <div class="text-6xl">🔥</div>
    </div>
    
    <div class="mt-4 text-sm">
        <p>Last entry: <strong>{{ $user->last_data_entry_date?->format('d M Y') ?? 'Never' }}</strong></p>
    </div>
</div>
