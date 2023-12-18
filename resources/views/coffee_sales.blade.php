<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New ☕️ Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 sm:px-6 lg:px-8 mt-4"> 
                    <form method="POST">
                        <x-coffee-sales.form-validation-errors class="mb-4" :errors="$errors" />
                        <x-coffee-sales.success-message class="mb-4" :success="session('success')" />
                        <div x-data="coffeeSales" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                            @csrf
                            <div class="sm:col-span-2">
                                <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Product</label>
                                <select
                                    id="coffee_product_id"
                                    name="coffee_product_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                >
                                    <option value="">Select a product</option>
                                    @foreach($coffeeProducts as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                                <input
                                    id="quantity"
                                    type="number"
                                    name="quantity"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="{{ old('quantity') }}"
                                    x-on:keyUp="calculateSellingPrice()"
                                />
                            </div>

                            <div class="sm:col-span-2">
                                <label for="unitcost" class="block text-gray-700 text-sm font-bold mb-2">Unit Cost (&pound;)</label>
                                <input
                                    id="unitcost"
                                    type="number"
                                    name="unit_cost"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="{{ old('unit_cost') }}"
                                    x-on:keyUp="calculateSellingPrice()"
                                />
                            </div>

                            <div class="sm:col-span-1">
                                <div class="text-gray-700 text-sm font-bold mb-2">Selling price</div>
                                <div id="sellingprice" class="mt-4" x-model="sellingPrice">
                                    <span x-text="sellingPrice"></span>
                                </div>
                            </div>

                            <div class="sm:col-span-1 mt-6">
                                <button 
                                    type="submit"  
                                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 float-right"
                                >
                                    Record Sale
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Quantity</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unit Cost</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Selling Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($coffeeSales as $sale)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                    {{ $sale->quantity }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    @money($sale->unit_cost, $currency)
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    @money($sale->selling_price, $currency)
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </div>

    <script>

        const formatCurrency = (value) => {
            return new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(value);
        }

        document.addEventListener('alpine:init', () => {
            let timer;
            Alpine.data('coffeeSales', () => ({
                sellingPrice: formatCurrency(0.00),
                calculateSellingPrice() {
                    if(quantity.value == 0 || unitcost.value == 0) {
                        return;
                    }

                    // Use setTimeout to limit calculations
                    // 250ms seems sensible
                    clearTimeout(timer);
                    timer = setTimeout(async () => {
                        let response = await axios.get('/api/sellingprice', {
                            params: {
                                coffee_product_id: coffee_product_id.value,
                                quantity: quantity.value,
                                unit_cost: unitcost.value
                            }
                        });

                        this.sellingPrice = formatCurrency(response?.data?.sellingPrice / 100 ?? 0.00);
                    }, 250);
                    
                },
            }));
        });
    </script>
</x-app-layout>
