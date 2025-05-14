<div class="bg-[#1D1F2E] text-white min-h-screen p-6">
    <!-- Controls -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-[#25273D] shadow-lg z-40 border-t border-[#3E3B5B]">
        <div class="flex justify-around text-[#B3B3B3] text-xs">
            <a href="{{ route('main') }}" class="flex flex-col items-center py-3 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <span>Menu</span>
            </a>
            <a href="{{ route('order') }}" class="flex flex-col items-center py-3 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Orders</span>
            </a>
            <a href="{{ route('tables') }}" class="flex flex-col items-center py-3 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span>Tables</span>
            </a>
            <button x-data @click="$dispatch('toggle-cart')" class="flex flex-col items-center py-3 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Cart</span>
            </button>
        </div>
    </div>

    <!-- Desktop Sidebar -->
    <aside class="hidden lg:block w-48 bg-[#1D1F2E] h-screen fixed inset-y-0 left-0 z-30">
        <div class="flex flex-col h-full">
            <!-- Logo or Title -->
            <div class="p-4 border-b border-[#3E3B5B]">
                <h2 class="text-lg font-bold">Restaurant</h2>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-2 space-y-1">
                <a href="{{ route('main') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-[#25273D]">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Menu
                    </div>
                </a>
                <a href="{{ route('order') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-[#25273D]">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Orders
                    </div>
                </a>
                <a href="{{ route('tables') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-[#25273D]">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Table Map
                    </div>
                </a>
            </nav>
        </div>
    </aside>

    <div class="lg:ml-48 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4">
        <input type="text" 
               wire:model.live.debounce.300ms="searchQuery"
               placeholder="Search tables..."
               class="w-full lg:w-64 bg-[#25273D] text-white px-4 py-2 rounded-lg focus:ring-2 focus:ring-[#E87C6F]">
        
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('activeFilter', 'all')"
                    class="px-4 py-2 rounded-md {{ $activeFilter === 'all' ? 'bg-[#E87C6F] text-white' : 'bg-[#25273D]' }}">
                All
            </button>
            @foreach($statusFilters as $status)
            <button wire:click="$set('activeFilter', {{ $status->id }})"
                    class="px-4 py-2 rounded-md flex items-center gap-2 text-sm"
                    style="background-color: {{ $status->color }}; {{ $activeFilter === $status->id ? 'opacity: 1' : 'opacity: 0.7' }}">
                {{ $status->etat }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- Floor Plan Container -->
    <div class="lg:ml-48 relative w-120 h-[75vh] bg-[#25273D] rounded-xl border-2 border-[#3E3B5B]"
         x-data="{ selectedTable: null }"
         style="background-image: radial-gradient(circle at 1px 1px, #3E3B5B 1px, transparent 0); background-size: 40px 40px;">
         
        @foreach($filteredTables as $table)
        <div wire:key="table-{{ $table['id'] }}"
             class="absolute cursor-pointer group"
             style="left: {{ ($table['x'] - 100) / 9 }}%; 
                    top: {{ ($table['y'] - 100) / 9 }}%;
                    transform: translate(-50%, -50%);">
            <div class="w-16 h-16 rounded-lg flex flex-col items-center justify-center border-2 shadow-lg transition-all duration-200 group-hover:scale-110"
                 style="background-color: {{ $table['status']['color'] }}">
                <span class="text-lg font-bold">T{{ $table['number'] }}</span>
                <span class="text-xs mt-1 text-white/80">{{ $table['status']['name'] }}</span>
            </div>
        </div>
        @endforeach

        <!-- Selected Table Details -->
        <template x-if="selectedTable">
            <div class="absolute bottom-4 left-4 bg-[#1D1F2E] p-4 rounded-lg shadow-xl max-w-xs">
                <h3 class="text-lg font-bold mb-2" x-text="'Table ' + selectedTable.number"></h3>
                <p class="text-sm" :style="`color: ${selectedTable.status.color}`" 
                   x-text="selectedTable.status.name"></p>
                <template x-if="selectedTable.command">
                    <div class="mt-2 pt-2 border-t border-[#3E3B5B]">
                        <p class="text-xs" x-text="'Order #' + selectedTable.command.id"></p>
                        <p class="text-xs text-[#B3B3B3]" x-text="selectedTable.command.time"></p>
                    </div>
                </template>
            </div>
        </template>
    </div>

</div>