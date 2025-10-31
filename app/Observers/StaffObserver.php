<?php

namespace App\Observers;

use App\Models\Staff;
use Illuminate\Support\Facades\Log;

class StaffObserver
{
    /**
     * Handle the Staff "created" and "updated" events.
     * Compress images after saving
     */
    public function saved(Staff $staff): void
    {
        // Check if photo exists
        if ($staff->photo) {
            $this->compressImage($staff->photo);
        }
    }

    /**
     * Compress the uploaded image using native GD library
     */
    protected function compressImage(string $photoPath): void
    {
        try {
            $fullPath = storage_path('app/public/' . $photoPath);

            // Check if file exists
            if (!file_exists($fullPath)) {
                return;
            }

            // Get file info
            $fileInfo = getimagesize($fullPath);
            if (!$fileInfo) {
                return;
            }

            $fileSizeInMB = filesize($fullPath) / 1024 / 1024;
            $width = $fileInfo[0];
            $height = $fileInfo[1];
            $mimeType = $fileInfo['mime'];

            // Always process to ensure final size is under 2MB
            // Skip only if already under 500KB and small dimensions
            if ($fileSizeInMB <= 0.5 && $width <= 1000 && $height <= 1000) {
                return;
            }

            // Load image based on type
            $sourceImage = match($mimeType) {
                'image/jpeg', 'image/jpg' => imagecreatefromjpeg($fullPath),
                'image/png' => imagecreatefrompng($fullPath),
                'image/gif' => imagecreatefromgif($fullPath),
                'image/webp' => imagecreatefromwebp($fullPath),
                default => null
            };

            if (!$sourceImage) {
                return;
            }

            // Calculate new dimensions to ensure file size under 2MB
            // For very large files, be more aggressive with compression
            if ($fileSizeInMB > 5) {
                $maxDimension = 1200; // More aggressive for large files
            } elseif ($fileSizeInMB > 2) {
                $maxDimension = 1500;
            } else {
                $maxDimension = 1920;
            }

            if ($width > $maxDimension || $height > $maxDimension) {
                if ($width > $height) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) ($height * ($maxDimension / $width));
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) ($width * ($maxDimension / $height));
                }
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            // Create new resized image
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            if ($mimeType === 'image/png') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
            }

            // Resize
            imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $width, $height
            );

            // Save compressed image with quality based on original file size
            // For larger files, use lower quality to ensure final size is under 2MB
            if ($fileSizeInMB > 5) {
                $quality = 70; // More compression for very large files
            } elseif ($fileSizeInMB > 3) {
                $quality = 75;
            } elseif ($fileSizeInMB > 2) {
                $quality = 80;
            } else {
                $quality = 85; // Best quality for smaller files
            }

            imagejpeg($resizedImage, $fullPath, $quality);

            // Free memory
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            // Verify final file size - if still over 2MB, compress more aggressively
            $finalSize = filesize($fullPath) / 1024 / 1024;
            if ($finalSize > 2) {
                // Reload and compress with even lower quality
                $sourceImage = imagecreatefromjpeg($fullPath);
                imagejpeg($sourceImage, $fullPath, 60);
                imagedestroy($sourceImage);
            }

        } catch (\Exception $e) {
            // Log error but don't break the save operation
            Log::error('Image compression failed: ' . $e->getMessage());
        }
    }
}
