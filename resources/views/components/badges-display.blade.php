<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @forelse($badges as $badge)
        <div class="bg-white rounded-lg shadow p-4 text-center border-2 border-yellow-300">
            <div class="text-5xl mb-2">{{ $badge->icon_url }}</div>
            <h4 class="font-bold text-gray-800">{{ $badge->name }}</h4>
            <p class="text-sm text-gray-600">{{ $badge->description }}</p>
            <div class="mt-3 text-xs text-green-600 font-semibold">✓ Unlocked</div>
        </div>
    @empty
        <div class="col-span-full bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-gray-600">No badges earned yet. Keep tracking your health to earn badges! 🚀</p>
        </div>
    @endforelse
</div>
