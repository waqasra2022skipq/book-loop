<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;

class BookImageService
{
    /**
     * Upload and save the cover image for a book.
     *
     * @param Book $book
     * @param UploadedFile|null $image
     * @return void
     */
    public static function uploadAndSave(Book $book, ?UploadedFile $image): void
    {
        if ($image) {
            // Delete old image if exists
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $image->store('cover_images', 'public');
            $book->cover_image = $path;
            $book->save();
        }
    }
}
