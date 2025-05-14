<div class="bg-primary-dark text-white min-h-screen flex">
    <aside class="w-48 bg-[#1D1F2E] h-screen fixed inset-y-0 left-0 z-30">
        <div class="flex flex-col h-full">
            <!-- Logo or Title -->
            <div class="p-4 border-b border-[#3E3B5B]">
                <h2 class="text-lg font-bold">Restaurant</h2>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-2 space-y-1">
                <a href="{{ route('main') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-accent/20">
                    Menu
                </a>
                <a href="{{ route('order') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium bg-accent/20 text-accent transition-colors duration-200">
                    Orders
                </a>
                <a href="{{ route('tables') }}" 
                    class="block px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-accent/20">
                    Table Map
                </a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 ml-48 p-6">
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Notification messages -->
            @if (session()->has('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-lg flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button wire:click="$refresh" class="text-green-400 hover:text-green-300">×</button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-400 p-4 rounded-lg flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button wire:click="$refresh" class="text-red-400 hover:text-red-300">×</button>
                </div>
            @endif

            <div class="bg-elevated rounded-xl p-4 shadow-lg">
                <select wire:model="selectedTable" wire:change="loadOrders"
                    class="w-full bg-input text-white rounded-lg p-3 
                       border-none focus:ring-2 focus:ring-accent">
                    <option value="">All Tables</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}" class="bg-primary-dark">
                            Table {{ $table->Numero }}
                        </option>
                    @endforeach
                </select>
            </div>

            @forelse($orders as $order)
                <div class="bg-elevated rounded-xl shadow-lg overflow-hidden">
                    <div class="p-4 flex items-center justify-between bg-secondary-dark">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-semibold">
                                Table {{ $order->table->Numero }}
                            </span>
                            <span class="text-xs bg-accent/20 text-accent px-2 py-1 rounded-full">
                                {{ $order->created_at ? $order->created_at->diffForHumans() : 'N/A' }}
                            </span>
                            @if ($order->etat)
                                <span class="text-xs px-2 py-1 rounded-full" style="background-color: {{ $order->etat->color }}20; color: {{ $order->etat->color }};">
                                    {{ $order->etat->etat }}
                                </span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            @if (!$editingOrder)
                                <button wire:click="startEditing({{ $order->id }})"
                                    class="p-2 hover:bg-blue-600/10 rounded-lg transition-colors">
                                    <span class="text-blue-500">✎ Edit</span>
                                </button>
                            @endif
                            <button wire:click="cancelOrder({{ $order->id }})"
                                onclick="return confirm('Are you sure you want to cancel this order?')"
                                class="p-2 hover:bg-red-600/10 rounded-lg transition-colors">
                                <span class="text-red-500">✕ Cancel</span>
                            </button>
                            <button wire:click="confirmOrder({{ $order->id }})"
                                onclick="return confirm('Are you sure you want to confirm this order?')"
                                class="p-2 hover:bg-green-600/10 rounded-lg transition-colors">
                                <span class="text-green-500">✓ Confirm</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($order->details as $detail)
                            <div class="flex justify-between items-center p-3 bg-primary-dark rounded-lg">
                                <div>
                                    <span class="font-medium">{{ $detail->qte }}x
                                        {{ $detail->article->designation }}</span>
                                    @if ($detail->message)
                                        <p class="text-xs text-muted mt-1">{{ $detail->message->message }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-semibold">
                                        Unit: {{ number_format($detail->prix_ht, 2) }} MAD
                                    </span>
                                    <span class="text-xs text-muted">
                                        TVA: {{ $detail->tva }}%
                                    </span>
                                    <span class="text-sm font-medium text-accent">
                                        Total: {{ number_format($detail->prix_ht * (1 + $detail->tva/100) * $detail->qte, 2) }} MAD
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        <div class="md:col-span-2 mt-4 p-3 bg-secondary-dark rounded-lg">
                            <div class="flex justify-between">
                                <span class="font-medium">Subtotal (HT):</span>
                                <span>{{ number_format($order->details->sum(function ($detail) {
                                    return $detail->prix_ht * $detail->qte;
                                }), 2) }} MAD</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="font-medium">TVA:</span>
                                <span>{{ number_format($order->details->sum(function ($detail) {
                                    return $detail->prix_ht * ($detail->tva/100) * $detail->qte;
                                }), 2) }} MAD</span>
                            </div>
                            <div class="flex justify-between mt-2 pt-2 border-t border-gray-700">
                                <span class="font-semibold text-accent">Total (TTC):</span>
                                <span class="font-semibold text-accent">{{ number_format($order->details->sum(function ($detail) {
                                    return $detail->prix_ht * (1 + $detail->tva/100) * $detail->qte;
                                }), 2) }} MAD</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-muted">
                    🍽️ No active orders found
                </div>
            @endforelse

            @if ($editingOrder)
                <!-- Edit Panel -->
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-start p-4 z-50">
                    <div class="bg-elevated rounded-xl shadow-lg flex-1 max-w-4xl mx-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Articles Selection -->
                            <div class="col-span-1">
                                <h3 class="text-lg font-semibold mb-4">Add Items</h3>
                                <div class="space-y-4">
                                    <select wire:model="selectedFamille"
                                        wire:change="loadSousFamilles($event.target.value)"
                                        class="w-full bg-input text-white rounded-lg p-2">
                                        <option value="">Select Category</option>
                                        @foreach ($familles as $famille)
                                            <option value="{{ $famille->id }}">{{ $famille->famille }}</option>
                                        @endforeach
                                    </select>

                                    <select wire:model="selectedSousFamille"
                                        wire:change="loadArticles($event.target.value)"
                                        class="w-full bg-input text-white rounded-lg p-2">
                                        <option value="">Select Subcategory</option>
                                        @foreach ($sousFamilles as $sf)
                                            <option value="{{ $sf->id }}">{{ $sf->sous_famille }}</option>
                                        @endforeach
                                    </select>

                                    <div class="space-y-2 max-h-96 overflow-y-auto">
                                        @forelse ($articlesToAdd as $article)
                                            <div
                                                class="flex justify-between items-center p-2 hover:bg-primary-dark rounded">
                                                <div class="flex-1">
                                                    <span>{{ $article->designation }}</span>
                                                    <div class="flex space-x-2 text-xs text-gray-400">
                                                        <span>{{ number_format($article->prix_ht, 2) }} MAD</span>
                                                        <span>Stock: {{ $article->stock }}</span>
                                                    </div>
                                                </div>
                                                <button wire:click="addToPanier({{ $article->id }})"
                                                    class="px-2 py-1 bg-accent rounded hover:bg-accent-dark transition-colors"
                                                    {{ $article->stock <= 0 ? 'disabled' : '' }}>
                                                    +
                                                </button>
                                            </div>
                                        @empty
                                            <div class="text-center py-6 text-muted">
                                                No articles available in this category
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Current Order Items -->
                            <div class="col-span-2">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold">Editing Order #{{ $editingOrder->id }}</h3>
                                    <span class="text-sm text-muted">
                                        Table {{ $editingOrder->table->Numero }}
                                    </span>
                                </div>
                                
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @forelse ($panier as $item)
                                        <div class="flex items-center justify-between p-3 bg-primary-dark rounded {{ isset($modifiedItems[$item['id']]) ? 'border border-accent' : '' }}">
                                            <div class="flex-1">
                                                <p class="font-medium">{{ $item['designation'] }}</p>
                                                <select wire:model="panier.{{ $item['id'] }}.message_id"
                                                    wire:change="updateMessageForItem('{{ $item['id'] }}', $event.target.value)"
                                                    class="text-xs bg-input text-white p-1 rounded">
                                                    <option value="">No message</option>
                                                    @foreach ($messages as $message)
                                                        <option value="{{ $message->id }}">{{ $message->message }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="text-xs text-gray-400 mt-1">
                                                    <span>Unit: {{ number_format($item['prix_ht'], 2) }} MAD</span>
                                                    <span class="ml-2">TVA: {{ $item['tva'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="updateQuantity('{{ $item['id'] }}', 'decrement')"
                                                    class="px-2 py-1 bg-input rounded hover:bg-secondary-dark">-</button>
                                                <input type="number" wire:model="panier.{{ $item['id'] }}.qte"
                                                    min="1" max="100"
                                                    class="w-12 text-center bg-input rounded">
                                                <button wire:click="updateQuantity('{{ $item['id'] }}', 'increment')"
                                                    class="px-2 py-1 bg-input rounded hover:bg-secondary-dark">+</button>
                                                <button wire:click="removeFromPanier('{{ $item['id'] }}')"
                                                    class="text-red-500 px-2 hover:text-red-400">×</button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-muted">
                                            No items in order
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Order Summary -->
                                @if (count($panier) > 0)
                                    <div class="mt-6 p-3 bg-secondary-dark rounded-lg">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Subtotal (HT):</span>
                                            <span>{{ number_format($totalHt, 2) }} MAD</span>
                                        </div>
                                        <div class="flex justify-between mt-1">
                                            <span class="font-medium">TVA:</span>
                                            <span>{{ number_format($totalTva, 2) }} MAD</span>
                                        </div>
                                        <div class="flex justify-between mt-2 pt-2 border-t border-gray-700">
                                            <span class="font-semibold text-accent">Total (TTC):</span>
                                            <span class="font-semibold text-accent">{{ number_format($totalTtc, 2) }} MAD</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Payment method selection -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium mb-2">Payment Method</label>
                                    <select wire:model="editingOrder.mode_paiement_id"
                                        class="w-full bg-input text-white rounded-lg p-2">
                                        <option value="">Select Payment Method</option>
                                        @foreach ($modesPaiement as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->mode_paiement }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-6 flex space-x-3">
                                    <button wire:click="saveEditedOrder"
                                        class="px-4 py-2 bg-accent rounded-lg hover:bg-accent-dark transition-colors"
                                        {{ count($panier) === 0 ? 'disabled' : '' }}>
                                        Save Changes
                                    </button>
                                    <button wire:click="cancelEdit"
                                        class="px-4 py-2 bg-input rounded-lg hover:bg-primary-dark transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>