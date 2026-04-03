<?php

namespace App\Services\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryImageService
{
    public function syncHeroImage(Request $request, array &$payload, string $directory, ?Model $model = null): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        if ($model?->image) {
            Storage::disk('public')->delete($this->relativePath($model->image));
        }

        $payload['image'] = Storage::url($request->file('image')->store($directory, 'public'));
    }

    public function syncGallery(Request $request, Model $model, string $directory): void
    {
        $model->loadMissing('gallery');

        $imagesToRemove = $model->gallery
            ->whereIn('id', collect($request->input('remove_gallery_images', []))->map(fn ($id) => (int) $id))
            ->values();

        foreach ($imagesToRemove as $image) {
            Storage::disk('public')->delete($this->relativePath($image->path));
            $image->delete();
        }

        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $sortOrder = (int) $model->gallery()->max('sort_order');

        foreach ($request->file('gallery_images') as $file) {
            $sortOrder++;

            $model->gallery()->create([
                'path' => Storage::url($file->store($directory, 'public')),
                'sort_order' => $sortOrder,
            ]);
        }
    }

    public function deleteAll(Model $model): void
    {
        if ($model->image) {
            Storage::disk('public')->delete($this->relativePath($model->image));
        }

        foreach ($model->gallery as $image) {
            Storage::disk('public')->delete($this->relativePath($image->path));
        }
    }

    private function relativePath(string $path): string
    {
        return str_replace('/storage/', '', $path);
    }
}
