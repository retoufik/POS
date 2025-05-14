<div class="bg-[#1D1F2E] text-white min-h-screen">
    <!-- Mobile Navigation -->
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

    <div class="flex flex-col lg:flex-row">
        <!-- Main Content -->
        <main x-data="{ isCartOpen: false }"
            class="flex-1 flex flex-col p-4 lg:p-6 space-y-4 lg:space-y-6 lg:ml-48 pb-16 lg:pb-6">
            <!-- Header and search -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-white">{{ Auth()->user()->name }}</h1>
                    <p class="text-xs text-[#B3B3B3] mt-1">{{ now()->toDateString() }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <div class="relative">
                        <input
                            class="bg-[#25273D] rounded-md text-xs placeholder-[#4B4B5B] py-2 pl-8 pr-3 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-[#E87C6F]"
                            placeholder="Search for food" type="search" wire:model.debounce.300ms="search" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4B4B5B] absolute left-2 top-2"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </header>

            <!-- Success/Error Messages -->
            @if ($successMessage)
                <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-500 px-4 py-2 rounded-md">
                    {{ $successMessage }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-500 px-4 py-2 rounded-md">
                    {{ $errorMessage }}
                </div>
            @endif
        
            <!-- Familles Tabs - Scrollable on mobile -->
            <div class="overflow-x-auto pb-2">
                <nav
                    class="flex space-x-6 text-xs font-semibold text-[#B3B3B3] border-b border-[#3E3B5B] pb-2 min-w-max">
                    @foreach ($familles as $famille)
                        <button 
                        type="button"
                        wire:key='famille-{{$famille['id']}}'
                        wire:model.live='activeFamille'
                        wire:click="loadSousFamilles({{$famille->id}})"
                        class="pb-1 {{ $activeFamille == $famille['id'] ? 'text-[#E87C6F] border-b-2 border-[#E87C6F]' : '' }}">
                            {{ $famille['famille'] }}
                        </button>
                    @endforeach
                </nav>
            </div>

            @if (!empty($sousFamilles))
                <div class="flex justify-between items-center">
                    <h2 class="text-sm font-semibold">Subcategories</h2>
                    <div class="relative">
                        <select wire:model="selectedSousFamille" wire:change="loadArticles($event.target.value)"
                            class="bg-[#25273D] text-white text-xs rounded-md py-2 px-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#E87C6F]">
                            @foreach ($sousFamilles as $sf)
                                <option value="{{ $sf->id }}">{{ $sf->sous_famille }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 max-w-6xl">
                @foreach ($articles as $article)
                    <article wire:key="article-{{ $article['id'] }}"
                        class="bg-[#25273D] rounded-lg p-4 flex flex-col items-center space-y-3 text-center relative transition-transform hover:scale-105">
                        <div class="relative">
                            <img src="{{ $article['image'] }}" alt="{{ $article['designation'] }}"
                                class="w-28 h-28 rounded-full object-cover shadow-md">
                            <span
                                class="absolute bottom-0 right-0 bg-[#25273D] text-xs text-[#B3B3B3] px-2 py-1 rounded-full">
                                {{ $article['stock'] }} left
                            </span>
                        </div>
                        <h3 class="text-sm font-semibold line-clamp-2">{{ $article['designation'] }}</h3>
                        <p class="text-xs text-[#B3B3B3]">${{ number_format($article['prix_ht'], 2) }}</p>
                        <button wire:click="addToPanier({{ $article['id'] }})"
                            class="bg-[#E87C6F] text-white text-xs px-4 py-2 rounded-md hover:bg-[#d86b5e] transition-colors w-full"
                            {{ $article['stock'] < 1 ? 'disabled' : '' }}>
                            {{ $article['stock'] >= 1 ? 'Add to Order' : 'Out of Stock' }}
                        </button>
                    </article>
                @endforeach
            </div>

            <!-- Empty state for articles -->
            @if (empty($articles))
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#3E3B5B]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium">No items found</h3>
                    <p class="mt-1 text-sm text-[#B3B3B3]">Try selecting a different category or search term</p>
                </div>
            @endif
        </main>

        <!-- Orders Panel - Hidden on mobile by default -->
        <aside x-data="{ open: false }" x-on:toggle-cart.window="open = !open"
            :class="open ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
            class="fixed lg:static top-0 right-0 w-full sm:w-96 lg:w-80 xl:w-96 bg-[#25273D] flex flex-col h-screen transform transition-transform duration-300 z-50 lg:z-10">
            <!-- Close button for mobile -->
            <button @click="open = false" class="lg:hidden absolute top-4 left-4 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6 flex-1 flex flex-col">
                <!-- Header -->
                <h2 class="text-sm font-semibold mb-4">
                    Orders <span class="text-[#B3B3B3] font-normal">#{{ rand(10000, 99999) }}</span>
                </h2>

                <!-- Table Selection -->
                <div class="relative mb-6">
                    <select
                        class="w-full bg-[#3E3B5B] text-white text-xs rounded-md py-2 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#E87C6F] appearance-none"
                        wire:model="selectedTable">
                        <option value="">Select Table</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table['id'] }}"
                                @if (isset($table['latestCommande']['etat']['color'])) style="color: {{ $table['latestCommande']['etat']['color'] }}" @endif>
                                Table {{ $table['Numero'] }}
                                @if (isset($table['latestCommande']['etat']))
                                    ({{ $table['latestCommande']['etat']['etat'] }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Order Type Buttons -->
                <div class="flex space-x-3 mb-6 overflow-x-auto pb-2">
                    @foreach ($type as $t)
                        <button

                        wire:click="selectType({{ $t['id'] }})"
                            class="text-xs font-semibold rounded-md py-2 px-5 {{ $selectedType == $t['id'] ? 'bg-[#E87C6F] text-white' : 'bg-[#3E3B5B] text-[#B3B3B3]' }}">
                            {{ $t['type'] }}
                        </button>
                    @endforeach
                </div>

                <!-- Order Items -->
                <div class="flex-1 overflow-y-auto pr-2">
                    @if (count($panier) > 0)
                        <div class="space-y-4">
                            @foreach ($panier as $articleId => $item)
                                <div wire:key="panier-item-{{ $articleId }}" class="bg-[#1D1F2E] p-3 rounded-md">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['designation'] }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold">{{ $item['designation'] }}</p>
                                            <p class="text-[10px] text-[#B3B3B3]">
                                                ${{ number_format($item['prix_ht'], 2) }}</p>
                                        </div>
                                        <p class="text-xs font-semibold">
                                            ${{ number_format($item['prix_ht'] * $item['quantity'], 2) }}
                                        </p>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center space-x-2 bg-[#3E3B5B] rounded-md p-1">
                                            <button wire:click="incOrDecQTE({{ $item['id'] }}, 'dec')"
                                                class="text-white w-6 h-6 rounded-md flex items-center justify-center hover:bg-[#25273D]">
                                                -
                                            </button>
                                            <input type="text"
                                                wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                                class="text-xs w-6 bg-transparent border-none text-center focus:outline-none focus:ring-0"
                                                value="{{ $item['quantity'] }}">
                                            <button wire:click="incOrDecQTE({{ $item['id'] }}, 'inc')"
                                                class="text-white w-6 h-6 rounded-md flex items-center justify-center hover:bg-[#25273D]">
                                                +
                                            </button>
                                        </div>

                                        <div class="flex space-x-2">
                                            <div class="relative">
                                                <select wire:model="panier.{{ $item['id'] }}.message"
                                                    class="bg-[#E87C6F] text-white text-xs rounded-md py-1 pl-2 pr-6 appearance-none">
                                                    <option value="">Note</option>
                                                    @foreach ($messages as $message)
                                                        <option value="{{ $message['id'] }}">
                                                            {{ $message['message'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-white">
                                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <button wire:click="removeFromPanier({{ $articleId }})"
                                                class="bg-[#3E3B5B] text-[#E87C6F] p-1 rounded-md text-xs w-6 h-6 flex items-center justify-center hover:bg-[#1D1F2E]">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty cart state -->
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#3E3B5B]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-4 text-sm font-medium">Your cart is empty</h3>
                            <p class="mt-1 text-xs text-[#B3B3B3]">Add items to create an order</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Totals -->
            <div class="p-6 border-t border-[#3E3B5B] bg-[#25273D]">
                <!-- Totals -->
                <div class="space-y-3">
                    <div class="flex justify-between text-xs text-[#B3B3B3]">
                        <span>Sub total</span>
                        <span class="font-semibold text-white">${{ number_format($sousTotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-[#B3B3B3]">
                        <span>Total</span>
                        <span class="font-semibold text-white">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Payment Button -->
                <button
                    class="mt-6 bg-[#E87C6F] text-white text-xs font-semibold rounded-md py-3 w-full hover:bg-[#d86b5e] transition-colors {{ count($panier) == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    wire:click="processPayment" {{ count($panier) == 0 ? 'disabled' : '' }}>
                    Continue to Payment
                </button>
            </div>
        </aside>
    </div>

    <!-- Payment Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-[#25273D] rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Payment Details</h2>
                    <button wire:click='$set("showModal", false)' class="text-[#B3B3B3] hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Order Summary -->
                <div class="mb-4 p-3 bg-[#1D1F2E] rounded-md">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#E87C6F]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm">Table:
                            {{ isset($tables) && $selectedTable ? collect($tables)->firstWhere('id', $selectedTable)['Numero'] : 'N/A' }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#E87C6F]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <p class="text-sm">Order Type:
                            {{ isset($type) && $selectedType ? collect($type)->firstWhere('id', $selectedType)['type'] : 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                @if ($selectedType == 2)
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Payment Method</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($modeDepaiment as $mode)
                                <button type="button" wire:click="$set('selectedModePaiement', {{ $mode->id }})"
                                    class="p-3 rounded-lg text-center text-sm {{ $selectedModePaiement == $mode['id']
                                        ? 'bg-[#E87C6F] text-white'
                                        : 'bg-[#1D1F2E] text-[#B3B3B3] hover:bg-[#3E3B5B]' }}">
                                    {{ $mode->mode_paiement }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Order Items Summary -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium mb-3">Order Items</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                        @foreach ($panier as $item)
                            <div class="flex justify-between items-center text-xs bg-[#1D1F2E] p-2 rounded">
                                <div>
                                    <span class="font-medium">{{ $item['designation'] }}</span>
                                    <span class="text-[#B3B3B3] ml-2">x{{ $item['quantity'] }}</span>
                                </div>
                                <span>${{ number_format($item['prix_ht'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Total -->
                <div class="border-t border-[#3E3B5B] pt-4 mb-6">
                    <div class="flex justify-between items-center mb-2 text-sm">
                        <span class="text-[#B3B3B3]">Subtotal</span>
                        <span>${{ number_format($sousTotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Order Notes (Optional)</label>
                    <textarea wire:model="observation"
                        class="w-full bg-[#1D1F2E] rounded-lg text-sm p-3 focus:outline-none focus:ring-2 focus:ring-[#E87C6F]"
                        rows="3" placeholder="Add any special instructions..."></textarea>
                </div>

                <!-- Confirm Button -->
                <button wire:click="confirmPayment"
                    class="w-full bg-[#E87C6F] text-white text-sm font-semibold rounded-lg py-3 hover:bg-[#d86b5e] transition-colors">
                    Confirm Order
                </button>
            </div>
        </div>
    @endif
</div>
