<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Unlock Your Next Badge</h3>
    
    @if($nextBadge)
        <div class="border-l-4 border-blue-500 pl-4 py-2">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-bold text-gray-800">{{ $nextBadge['name'] }}</h4>
                    <p class="text-sm text-gray-600">{{ $nextBadge['description'] }}</p>
                </div>
                <div class="text-3xl">🏆</div>
            </div>
            
            <div class="mt-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold">Progress</span>
                    <span class="text-sm text-gray-600">{{ $nextBadge['progress'] }}/{{ $nextBadge['requirement'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full transition-all" 
                         style="width: {{ min($nextBadge['percentage'], 100) }}%"></div>
                </div>
            </div>
            
            <p class="text-xs text-gray-500 mt-2">
                🎯 {{ round(100 - $nextBadge['percentage']) }}% more to go!
            </p>
        </div>
    @else
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <p class="text-green-700 font-semibold">🎉 Amazing! You've unlocked all badges!</p>
        </div>
    @endif
</div>
