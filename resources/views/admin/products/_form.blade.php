<!-- Basic Information -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">01</span>
        Basic Information
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Product Name *</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $product->name ?? '') }}"
                placeholder="e.g. Samsung Galaxy S24"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('name') border-red-500 @enderror"
                required
            />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Category *</label>
            <select
                name="category_id"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-medium @error('category_id') border-red-500 @enderror"
                required
            >
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Brand *</label>
            <div class="space-y-3">
                <!-- Brand Selection Type -->
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="brand_type" value="predefined" class="radio radio-sm radio-primary" checked onchange="toggleBrandInput()">
                        <span class="ml-2 text-sm font-medium text-gray-700">Select from list</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="brand_type" value="custom" class="radio radio-sm radio-primary" onchange="toggleBrandInput()">
                        <span class="ml-2 text-sm font-medium text-gray-700">Enter custom brand</span>
                    </label>
                </div>
                
                <!-- Predefined Brand Dropdown -->
                <select
                    id="brand_select"
                    name="brand_select"
                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 transition-all font-medium"
                    onchange="updateBrandValue()"
                >
                    <option value="">Select a brand</option>
                    <optgroup label="Major Electronics Brands">
                        <option value="Samsung" {{ old('brand', $product->brand ?? '') == 'Samsung' ? 'selected' : '' }}>Samsung</option>
                        <option value="LG" {{ old('brand', $product->brand ?? '') == 'LG' ? 'selected' : '' }}>LG</option>
                        <option value="Sony" {{ old('brand', $product->brand ?? '') == 'Sony' ? 'selected' : '' }}>Sony</option>
                        <option value="Panasonic" {{ old('brand', $product->brand ?? '') == 'Panasonic' ? 'selected' : '' }}>Panasonic</option>
                        <option value="Philips" {{ old('brand', $product->brand ?? '') == 'Philips' ? 'selected' : '' }}>Philips</option>
                        <option value="Whirlpool" {{ old('brand', $product->brand ?? '') == 'Whirlpool' ? 'selected' : '' }}>Whirlpool</option>
                        <option value="Bosch" {{ old('brand', $product->brand ?? '') == 'Bosch' ? 'selected' : '' }}>Bosch</option>
                        <option value="Siemens" {{ old('brand', $product->brand ?? '') == 'Siemens' ? 'selected' : '' }}>Siemens</option>
                    </optgroup>
                    <optgroup label="Appliance Brands">
                        <option value="Electrolux" {{ old('brand', $product->brand ?? '') == 'Electrolux' ? 'selected' : '' }}>Electrolux</option>
                        <option value="Haier" {{ old('brand', $product->brand ?? '') == 'Haier' ? 'selected' : '' }}>Haier</option>
                        <option value="Midea" {{ old('brand', $product->brand ?? '') == 'Midea' ? 'selected' : '' }}>Midea</option>
                        <option value="Miele" {{ old('brand', $product->brand ?? '') == 'Miele' ? 'selected' : '' }}>Miele</option>
                        <option value="Dyson" {{ old('brand', $product->brand ?? '') == 'Dyson' ? 'selected' : '' }}>Dyson</option>
                        <option value="Shark" {{ old('brand', $product->brand ?? '') == 'Shark' ? 'selected' : '' }}>Shark</option>
                        <option value="Bissell" {{ old('brand', $product->brand ?? '') == 'Bissell' ? 'selected' : '' }}>Bissell</option>
                        <option value="Black+Decker" {{ old('brand', $product->brand ?? '') == 'Black+Decker' ? 'selected' : '' }}>Black+Decker</option>
                    </optgroup>
                    <optgroup label="TV & Audio Brands">
                        <option value="TCL" {{ old('brand', $product->brand ?? '') == 'TCL' ? 'selected' : '' }}>TCL</option>
                        <option value="Hisense" {{ old('brand', $product->brand ?? '') == 'Hisense' ? 'selected' : '' }}>Hisense</option>
                        <option value="Sharp" {{ old('brand', $product->brand ?? '') == 'Sharp' ? 'selected' : '' }}>Sharp</option>
                        <option value="Toshiba" {{ old('brand', $product->brand ?? '') == 'Toshiba' ? 'selected' : '' }}>Toshiba</option>
                        <option value="JBL" {{ old('brand', $product->brand ?? '') == 'JBL' ? 'selected' : '' }}>JBL</option>
                        <option value="Bose" {{ old('brand', $product->brand ?? '') == 'Bose' ? 'selected' : '' }}>Bose</option>
                        <option value="Yamaha" {{ old('brand', $product->brand ?? '') == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
                        <option value="Pioneer" {{ old('brand', $product->brand ?? '') == 'Pioneer' ? 'selected' : '' }}>Pioneer</option>
                    </optgroup>
                    <optgroup label="Kitchen Appliance Brands">
                        <option value="KitchenAid" {{ old('brand', $product->brand ?? '') == 'KitchenAid' ? 'selected' : '' }}>KitchenAid</option>
                        <option value="Cuisinart" {{ old('brand', $product->brand ?? '') == 'Cuisinart' ? 'selected' : '' }}>Cuisinart</option>
                        <option value="Breville" {{ old('brand', $product->brand ?? '') == 'Breville' ? 'selected' : '' }}>Breville</option>
                        <option value="Ninja" {{ old('brand', $product->brand ?? '') == 'Ninja' ? 'selected' : '' }}>Ninja</option>
                        <option value="Instant Pot" {{ old('brand', $product->brand ?? '') == 'Instant Pot' ? 'selected' : '' }}>Instant Pot</option>
                        <option value="Hamilton Beach" {{ old('brand', $product->brand ?? '') == 'Hamilton Beach' ? 'selected' : '' }}>Hamilton Beach</option>
                        <option value="Oster" {{ old('brand', $product->brand ?? '') == 'Oster' ? 'selected' : '' }}>Oster</option>
                        <option value="Vitamix" {{ old('brand', $product->brand ?? '') == 'Vitamix' ? 'selected' : '' }}>Vitamix</option>
                    </optgroup>
                </select>
                
                <!-- Custom Brand Input -->
                <input
                    type="text"
                    id="brand_custom"
                    name="brand_custom"
                    value="{{ old('brand', $product->brand ?? '') }}"
                    placeholder="Enter brand name"
                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all hidden"
                    onchange="updateBrandValue()"
                />
                
                <!-- Hidden field to store the actual brand value -->
                <input type="hidden" name="brand" id="brand_value" value="{{ old('brand', $product->brand ?? '') }}">
            </div>
            @error('brand')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Model</label>
            <input
                type="text"
                name="model"
                value="{{ old('model', $product->model ?? '') }}"
                placeholder="e.g. RF28R7351SG, LG-123"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('model') border-red-500 @enderror"
            />
            @error('model')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="space-y-1.5">
        <label class="text-xs font-bold uppercase text-gray-400">Description * (Min. 10 characters)</label>
        <textarea
            name="description"
            rows="4"
            placeholder="Describe your product in detail... (at least 10 characters)"
            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('description') border-red-500 @enderror"
            minlength="10"
            required
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="space-y-1.5">
        <label class="text-xs font-bold uppercase text-gray-400">Short Description</label>
        <textarea
            name="short_description"
            rows="2"
            placeholder="Brief product summary for listings..."
            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('short_description') border-red-500 @enderror"
        >{{ old('short_description', $product->short_description ?? '') }}</textarea>
        @error('short_description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</section>

<!-- Pricing & Inventory -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm">02</span>
        Pricing & Inventory
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Base Price (EUR) *</label>
            <input
                type="number"
                name="base_price"
                value="{{ old('base_price', $product->base_price ?? '') }}"
                step="0.01"
                min="0"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 @error('base_price') border-red-500 @enderror"
                required
            />
            @error('base_price')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Stock Quantity *</label>
            <input
                type="number"
                name="stock_quantity"
                value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}"
                min="0"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:border-orange-500 focus:ring-2 focus:ring-orange-500 @error('stock_quantity') border-red-500 @enderror"
                required
            />
            @error('stock_quantity')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">SKU (Stock Keeping Unit) *</label>
            <input
                type="text"
                name="sku"
                value="{{ old('sku', $product->sku ?? '') }}"
                placeholder="Leave empty to auto-generate"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono focus:border-orange-500 focus:ring-2 focus:ring-orange-500 @error('sku') border-red-500 @enderror"
                required
            />
            @error('sku')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Warranty Period</label>
            <input
                type="text"
                name="warranty_period"
                value="{{ old('warranty_period', $product->warranty_period ?? '') }}"
                placeholder="e.g. 2 years, 12 months"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all @error('warranty_period') border-red-500 @enderror"
            />
            @error('warranty_period')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Energy Rating</label>
            <select
                name="energy_rating"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all font-medium @error('energy_rating') border-red-500 @enderror"
            >
                <option value="">Select Energy Rating</option>
                <option value="1 Star" {{ old('energy_rating', $product->energy_rating ?? '') == '1 Star' ? 'selected' : '' }}>1 Star</option>
                <option value="2 Star" {{ old('energy_rating', $product->energy_rating ?? '') == '2 Star' ? 'selected' : '' }}>2 Star</option>
                <option value="3 Star" {{ old('energy_rating', $product->energy_rating ?? '') == '3 Star' ? 'selected' : '' }}>3 Star</option>
                <option value="4 Star" {{ old('energy_rating', $product->energy_rating ?? '') == '4 Star' ? 'selected' : '' }}>4 Star</option>
                <option value="5 Star" {{ old('energy_rating', $product->energy_rating ?? '') == '5 Star' ? 'selected' : '' }}>5 Star</option>
            </select>
            @error('energy_rating')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<!-- Product Images -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-sm">03</span>
        Product Specifications
    </h3>
    
    <div class="space-y-1.5">
        <label class="text-xs font-bold uppercase text-gray-400">Specifications (JSON format)</label>
        <textarea
            name="specifications"
            rows="6"
            placeholder='{"dimensions": "60x60x85 cm", "weight": "65 kg", "capacity": "300L", "color": "Stainless Steel"}'
            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('specifications') border-red-500 @enderror"
        >{{ old('specifications', $product && $product->specifications ? json_encode($product->specifications, JSON_PRETTY_PRINT) : '') }}</textarea>
        <p class="text-xs text-gray-500">Enter product specifications in JSON format. Leave empty if not applicable.</p>
        @error('specifications')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</section>

<!-- Product Images -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">04</span>
        Product Images
    </h3>
    
    @if($product && $product->images->count() > 0)
        <div class="mb-6">
            <h4 class="text-sm font-bold text-gray-600 mb-3 uppercase tracking-wide">Current Images</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                @foreach($product->images as $image)
                    <div class="relative aspect-square group rounded-2xl overflow-hidden border border-gray-100">
                        <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <label class="flex items-center gap-2 text-white text-xs cursor-pointer">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="rounded">
                                Remove
                            </label>
                        </div>
                        <div class="absolute top-2 left-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                            {{ $image->image_type === 'main' ? 'Main' : 'Gallery' }}
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-2">Check images to remove them when updating the product.</p>
        </div>
    @endif
    
    <div class="space-y-1.5">
        <label class="text-xs font-bold uppercase text-gray-400">Upload New Images</label>
        <input
            type="file"
            name="images[]"
            multiple
            accept="image/*"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('images') border-red-500 @enderror"
        />
        <p class="text-xs text-gray-500">You can select multiple images for the product. The first image will be set as the main image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per image.</p>
        @error('images')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</section>

<!-- Product Settings -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-sm">05</span>
        Product Settings
    </h3>
    
    <div class="flex flex-wrap gap-4">
        <label class="flex items-center gap-3 bg-gray-50 px-5 py-3 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
            <div class="relative inline-flex items-center cursor-pointer">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                    class="sr-only peer" 
                />
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
            </div>
            <span class="text-sm font-semibold text-gray-700">Active Product</span>
        </label>

        <label class="flex items-center gap-3 bg-gray-50 px-5 py-3 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
            <div class="relative inline-flex items-center cursor-pointer">
                <input 
                    type="checkbox" 
                    name="is_featured" 
                    value="1"
                    {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                    class="sr-only peer" 
                />
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
            </div>
            <span class="text-sm font-semibold text-gray-700">Featured Product</span>
        </label>
    </div>
</section>

<script>
function toggleBrandInput() {
    const brandType = document.querySelector('input[name="brand_type"]:checked').value;
    const brandSelect = document.getElementById('brand_select');
    const brandCustom = document.getElementById('brand_custom');
    
    if (brandType === 'predefined') {
        brandSelect.classList.remove('hidden');
        brandCustom.classList.add('hidden');
        brandCustom.value = '';
        updateBrandValue();
    } else {
        brandSelect.classList.add('hidden');
        brandCustom.classList.remove('hidden');
        brandSelect.value = '';
        updateBrandValue();
    }
}

function updateBrandValue() {
    const brandType = document.querySelector('input[name="brand_type"]:checked').value;
    const brandSelect = document.getElementById('brand_select');
    const brandCustom = document.getElementById('brand_custom');
    const brandValue = document.getElementById('brand_value');
    
    if (brandType === 'predefined') {
        brandValue.value = brandSelect.value;
    } else {
        brandValue.value = brandCustom.value;
    }
}

// Initialize the form based on existing brand value
document.addEventListener('DOMContentLoaded', function() {
    const existingBrand = document.getElementById('brand_value').value;
    const brandSelect = document.getElementById('brand_select');
    const brandCustom = document.getElementById('brand_custom');
    
    // Check if existing brand is in the dropdown
    let brandInDropdown = false;
    for (let option of brandSelect.options) {
        if (option.value === existingBrand) {
            brandInDropdown = true;
            break;
        }
    }
    
    if (existingBrand && !brandInDropdown) {
        // Set to custom if brand is not in dropdown
        document.querySelector('input[name="brand_type"][value="custom"]').checked = true;
        brandCustom.value = existingBrand;
        toggleBrandInput();
    } else if (existingBrand && brandInDropdown) {
        // Set to predefined if brand is in dropdown
        document.querySelector('input[name="brand_type"][value="predefined"]').checked = true;
        brandSelect.value = existingBrand;
        toggleBrandInput();
    } else {
        // Default to predefined
        toggleBrandInput();
    }
});
</script>