<div class="bg-[#1D1F2E] text-white min-h-screen flex">
    <!-- Replace the existing sidebar code with this -->
    <aside class="w-48 bg-[#1D1F2E] h-screen fixed inset-y-0 left-0 z-30">
        <div class="flex flex-col h-full">
            <!-- Logo or Title -->
            <div class="p-4 border-b border-[#3E3B5B]">
                <h2 class="text-lg font-bold">Restaurant</h2>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-2 space-y-1">
                <a href="{{route('app')}}" 
                   class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Menu
                </a>
                <a href="{{route('order')}}" 
                   class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                  >
                    Orders
                </a>
            </nav>
        </div>
    </aside>

    <!-- Update the main content margin -->
    <main class="flex-1 flex flex-col p-6 space-y-6 ml-48">
        <!-- Header and search -->
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-white">{{ Auth()->user()->name }}</h1>
                <p class="text-xs text-[#B3B3B3] mt-1">{{ now()->toDateString() }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <input
                    class="bg-[#25273D] rounded-md text-xs placeholder-[#4B4B5B] py-2 px-3 w-64 sm:w-72 focus:outline-none focus:ring-2 focus:ring-[#E87C6F]"
                    placeholder="Search for food" type="search" wire:model.debounce.300ms="search" />
            </div>
        </header>

        <!-- Familles Tabs -->
        <nav class="flex space-x-6 text-xs font-semibold text-[#B3B3B3] border-b border-[#3E3B5B] pb-2">
            @foreach ($familles as $famille)
                <button wire:click="loadSousFamilles({{ $famille->id }})"
                    class="hover:text-white pb-1 {{ $activeFamille == $famille->id ? 'text-[#E87C6F] border-b-2 border-[#E87C6F]' : '' }}">
                    {{ $famille->famille }}
                </button>
            @endforeach
        </nav>

        <!-- Sous Familles Dropdown -->
        @if ($sousFamilles)
            <div class="flex justify-between items-center">
                <h2 class="text-sm font-semibold">Sous Familles</h2>
                <div class="relative">
                    <select
                        class="bg-[#25273D] text-white text-xs rounded-md py-2 px-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#E87C6F]"
                        wire:model="selectedSousFamille" wire:change="loadArticles($event.target.value)">
                        <option value="">Select Sous Famille</option>
                        @foreach ($sousFamilles as $sf)
                            <option value="{{ $sf->id }}">{{ $sf->sous_famille }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl">
            @foreach ($articles as $article)
                <article wire:key="article-{{ $article->id }}" class="bg-[#25273D] rounded-lg p-4 flex flex-col items-center space-y-3 text-center relative">
                    <img src="{{ $article->image }}" alt="{{ $article->designation }}"
                        class="w-28 h-28 rounded-full object-cover">
                    <h3 class="text-xs font-semibold">{{ $article->designation }}</h3>
                    <p class="text-[10px] text-[#B3B3B3]">${{ number_format($article->prix_ht, 2) }}</p>
                    <p class="text-[10px] text-[#B3B3B3]">{{ $article->stock }}</p>
                    <button wire:click="addToPanier({{ $article->id }})"
                        class="bg-[#E87C6F] text-white text-xs px-3 py-1 rounded-md hover:bg-[#d86b5e] transition"
                        {{ $article->stock < 1 ? 'disabled' : '' }}>
                        {{ $article->stock >= 1 ? 'Add to Order' : 'Out of Stock' }}
                    </button>
                </article>
            @endforeach
        </div>
    </main>

    <!-- Orders Panel -->
    <aside class="w-80 bg-[#25273D] flex flex-col h-screen">
        <div class="p-6 flex-1 flex flex-col">
            <!-- Header -->
            <h2 class="text-sm font-semibold mb-4">
                Orders <span class="text-[#B3B3B3] font-normal">#{{ rand(10000, 99999) }}</span>
            </h2>

            <!-- Table Selection -->
            <select
                class="w-full mb-6 bg-[#3E3B5B] text-white text-xs rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#E87C6F]"
                wire:model="selectedTable">
                <option value="">Select Table</option>
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}" @style(['color: ' . ($table->latestCommande?->etat?->color ?? '#00FF00'), 'background-color: #3E3B5B'])>
                        Table {{ $table->Numero }}
                        @if ($table->latestCommande?->etat)
                            ({{ $table->latestCommande->etat->etat }})
                        @endif
                    </option>
                @endforeach
            </select>

            <!-- Order Type Buttons -->
            <div class="flex space-x-3 mb-6">
                @foreach ($type as $t)
                    <button wire:click='loadType({{ $t->id }})'
                        class="text-xs font-semibold rounded-md py-2 px-5 {{ $selectedType == $t->id ? 'bg-[#E87C6F] text-white' : 'bg-[#3E3B5B] text-[#B3B3B3]' }}">
                        {{ $t->type }}
                    </button>
                @endforeach
            </div>


            <!-- Order Items -->
            <div class="flex-1 overflow-y-auto pr-2">
                <div class="space-y-4">
                    @foreach ($panier as $articleId => $item)
                        <div wire:key="panier-item-{{ $articleId }}" class="flex items-center space-x-3">
                            <img src="{{ $item['image'] }}" alt="{{ $item['designation'] }}"
                                class="w-8 h-8 rounded-full object-cover">
                            <div class="flex justify-between mb-4">
                                <p class="text-xs font-semibold">{{ $item['designation'] }}</p>
                                <p class="text-[10px] text-[#B3B3B3]">${{ number_format($item['prix_ht'], 2) }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="incOrDecQTE({{ $item['id'] }}, 'dec')"
                                    class="bg-[#3E3B5B] text-white w-5 h-5 rounded-md flex items-center justify-center">
                                    -
                                </button>
                                <input type="text"
                                    wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                    class="text-xs w-5 bg-gray-600 text-center" value="{{ $item['quantity'] }}">
                                <button wire:click="incOrDecQTE({{ $item['id'] }}, 'inc')"
                                    class="bg-[#3E3B5B] text-white w-5 h-5 rounded-md flex items-center justify-center">
                                    +
                                </button>
                            </div>
                            <p class="text-xs font-semibold w-14 text-right">
                                ${{ number_format($item['prix_ht'] * $item['quantity'], 2) }}
                            </p>
                        </div>
                        <div class="flex justify-between mb-4">
                            <button wire:key="panier-item-{{ $articleId }}" wire:click="removeFromPanier({{ $articleId }})"
                                class="bg-[#3E3B5B] text-[#E87C6F] p-1 rounded-md text-xs w-6 h-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>

                            </button>
                            <select wire:model="panier.{{ $item['id'] }}.message"
                                class="bg-[#E87C6F] text-white text-xs font-600 rounded-md py-1 px-2">
                                <option value="">Add Note</option>
                                @foreach ($messages as $message)
                                    <option value="{{ $message->id }}">{{ $message->message }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
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
                class="mt-6 bg-[#E87C6F] text-white text-xs font-semibold rounded-md py-3 w-full hover:bg-[#d86b5e] transition"
                wire:click="processPayment">
                Continue to Payment
            </button>
        </div>
    </aside>
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-[#25273D] rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Payment Details</h2>

                <!-- Order Summary -->
                <div class="mb-4">
                    <p class="text-sm">Table: {{ $tables->find($selectedTable)->Numero ?? 'N/A' }}</p>
                    <p class="text-sm">Order Type: {{ $type->find($selectedType)->type ?? 'N/A' }}</p>
                </div>

                <!-- Payment Methods -->
                <div class="space-y-2 mb-6">
                    @foreach ($modeDepaiment as $mode)
                        <label class="flex items-center p-3 bg-[#3E3B5B] rounded-md">
                            <input type="radio" wire:model="selectedModePaiement" value="{{ $mode->id }}"
                                class="mr-3">
                            <span class="text-sm">{{ $mode->mode_paiement }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Items List -->
                <div class="mb-6 max-h-60 overflow-y-auto">
                    @foreach ($panier as $item)
                        <div class="flex justify-between items-center py-2 border-b border-[#3E3B5B]">
                            <span class="text-sm">{{ $item['designation'] }} x{{ $item['quantity'] }}</span>
                            <span class="text-sm">${{ number_format($item['prix_ht'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($sousTotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div>
                        <textarea wire:model="observation" placeholder="Add any observations..."
                            class="w-full p-2 bg-[#3E3B5B] text-white rounded-md"></textarea>
                    </div>
                </div>

                <button wire:click="confirmPayment"
                    class="w-full text-white py-2 rounded-md transition"
                    {{ (!$selectedModePaiement && !$selectedTable && $selectedType != 1 ) ? 'disabled' : ''}}>
                    Confirm Payment
                </button>
                <button wire:click='$set("showModal", false)'
                    class="w-full bg-[#FF0033] text-white py-2 rounded-md hover:bg-[#F34646] transition">
                    cancel
                </button>
            </div>
        </div>
    @endif
</div>
