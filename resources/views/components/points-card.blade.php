<div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">{{ $user->points }} Points</h3>
            <p class="text-purple-100">Total Points</p>
        </div>
        <div class="text-5xl">⭐</div>
    </div>
    
    <div class="mt-4 bg-white bg-opacity-20 rounded p-3">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-semibold">Next Milestone</span>
            <span class="text-sm">{{ $pointsToNext }} points left</span>
        </div>
        <div class="w-full bg-white bg-opacity-30 rounded-full h-2">
            <div class="bg-white h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
        </div>
    </div>
</div>
