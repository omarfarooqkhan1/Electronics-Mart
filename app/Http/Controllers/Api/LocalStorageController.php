<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocalImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LocalStorageController extends Controller
{
    protected $localImageService;

    public function __construct(LocalImageService $localImageService)
    {
        $this->localImageService = $localImageService;
    }

    /**
     * Get storage usage statistics
     */
    public function getStorageUsage()
    {
        try {
            $publicPath = storage_path('app/public');
            $totalSize = 0;
            $fileCount = 0;
            
            // Count files and calculate total size
            $this->calculateDirectorySize($publicPath, $totalSize, $fileCount);
            
            // Get breakdown by directory
            $breakdown = [];
            $directories = ['images/products', 'images/categories', 'images/hero-images'];
            
            foreach ($directories as $dir) {
                $dirPath = $publicPath . '/' . $dir;
                if (is_dir($dirPath)) {
                    $dirSize = 0;
                    $dirFileCount = 0;
                    $this->calculateDirectorySize($dirPath, $dirSize, $dirFileCount);
                    
                    $breakdown[$dir] = [
                        'size' => $dirSize,
                        'size_formatted' => $this->formatBytes($dirSize),
                        'file_count' => $dirFileCount
                    ];
                }
            }
            
            return response()->json([
                'total_size' => $totalSize,
                'total_size_formatted' => $this->formatBytes($totalSize),
                'file_count' => $fileCount,
                'breakdown' => $breakdown,
                'disk_space_available' => disk_free_space($publicPath),
                'disk_space_available_formatted' => $this->formatBytes(disk_free_space($publicPath))
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get storage usage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up unused images
     */
    public function cleanupStorage()
    {
        try {
            $deletedFiles = [];
            $deletedSize = 0;
            
            // Get all image files in storage
            $allImages = Storage::disk('public')->allFiles('images');
            
            // Get all images referenced in database
            $referencedImages = collect();
            
            // Get product images
            $productImages = \App\Models\Image::where('imageable_type', 'App\Models\Product')
                ->orWhere('imageable_type', 'App\Models\ProductVariant')
                ->pluck('path')
                ->map(function ($path) {
                    return str_replace('/storage/', '', $path);
                });
            
            $referencedImages = $referencedImages->merge($productImages);
            
            // Find orphaned images
            foreach ($allImages as $imagePath) {
                if (!$referencedImages->contains($imagePath)) {
                    $fullPath = storage_path('app/public/' . $imagePath);
                    if (file_exists($fullPath)) {
                        $size = filesize($fullPath);
                        if (Storage::disk('public')->delete($imagePath)) {
                            $deletedFiles[] = $imagePath;
                            $deletedSize += $size;
                        }
                    }
                }
            }
            
            return response()->json([
                'message' => 'Storage cleanup completed',
                'deleted_files' => count($deletedFiles),
                'deleted_size' => $deletedSize,
                'deleted_size_formatted' => $this->formatBytes($deletedSize),
                'files' => $deletedFiles
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to cleanup storage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific image
     */
    public function deleteImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            $path = $request->input('path');
            
            // Remove /storage/ prefix if present
            $path = str_replace('/storage/', '', $path);
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                return response()->json([
                    'message' => 'Image deleted successfully',
                    'path' => $path
                ]);
            } else {
                return response()->json([
                    'error' => 'Image not found'
                ], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get optimized image URL
     */
    public function getOptimizedUrl(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'width' => 'nullable|integer|min:1|max:2000',
            'height' => 'nullable|integer|min:1|max:2000',
            'quality' => 'nullable|integer|min:1|max:100'
        ]);

        try {
            $path = $request->input('path');
            $width = $request->input('width');
            $height = $request->input('height');
            $quality = $request->input('quality', 85);
            
            // Remove /storage/ prefix if present
            $path = str_replace('/storage/', '', $path);
            
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'error' => 'Image not found'
                ], 404);
            }
            
            // For now, just return the original URL
            // In the future, you could implement image optimization here
            $optimizedUrl = Storage::disk('public')->url($path);
            
            return response()->json([
                'original_url' => Storage::disk('public')->url($path),
                'optimized_url' => $optimizedUrl,
                'width' => $width,
                'height' => $height,
                'quality' => $quality
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get optimized URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate directory size recursively
     */
    private function calculateDirectorySize($directory, &$size, &$fileCount)
    {
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $directory . '/' . $file;
                    if (is_dir($filePath)) {
                        $this->calculateDirectorySize($filePath, $size, $fileCount);
                    } else {
                        $size += filesize($filePath);
                        $fileCount++;
                    }
                }
            }
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}