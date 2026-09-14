<?php

namespace App\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function upload(
        UploadedFile $image,
        string $folder
    ): string {
        return $image->store($folder, 'public');
    }

    public static function delete(?string $imagePath): void
    {
        if (
            $imagePath &&
            Storage::disk('public')->exists($imagePath)
        ) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    public static function update(
        UploadedFile $image,
        ?string $oldImagePath,
        string $folder
    ): string {
        $newImagePath = self::upload($image, $folder);

        self::delete($oldImagePath);

        return $newImagePath;
    }
}