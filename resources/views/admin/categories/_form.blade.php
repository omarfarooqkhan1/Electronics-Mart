<!-- Basic Information -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">01</span>
        Basic Information
    </h3>
    
    <div class="space-y-6">
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Category Name *</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $category->name ?? '') }}"
                placeholder="e.g. Refrigerators, Air Conditioners, Televisions"
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('name') border-red-500 @enderror"
                required
            />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Description</label>
            <textarea
                name="description"
                rows="4"
                placeholder="Brief description of this category..."
                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none @error('description') border-red-500 @enderror"
            >{{ old('description', $category->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase text-gray-400">Category Image</label>
            @if($category && $category->image_url)
                <div class="mb-3">
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-500 mt-1">Current image</p>
                </div>
            @endif
            <input
                type="file"
                name="image"
                accept="image/*"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('image') border-red-500 @enderror"
            />
            <p class="text-xs text-gray-500">Upload a category image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</p>
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<!-- Category Settings -->
<section class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-sm">02</span>
        Category Settings
    </h3>
    
    <div class="flex flex-wrap gap-4">
        <label class="flex items-center gap-3 bg-gray-50 px-5 py-3 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
            <div class="relative inline-flex items-center cursor-pointer">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                    class="sr-only peer" 
                />
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
            </div>
            <span class="text-sm font-semibold text-gray-700">Active (visible to customers)</span>
        </label>
    </div>
</section>