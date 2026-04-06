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

        // Get the model name to determine the gallery relationship
        $modelClass = class_basename($model) ?: 'Trek';
        $galleryRelation = strtolower($modelClass) . '_images';

        // If model exists and has a hero image, remove the old one
        if ($model) {
            $heroImage = $model->images()->where('sort_order', 0)->first();
            if ($heroImage) {
                Storage::disk('public')->delete($this->relativePath($heroImage->path));
                $heroImage->delete();
            }

            // Store new hero image with sort_order = 0 in the images table
            $path = Storage::url($request->file('image')->store($directory, 'public'));
            $model->images()->create([
                'path' => $path,
                'sort_order' => 0,
            ]);
        }
        // Store in payload for creation flow (will be created after model exists)
        // For new models, we'll handle this in the controller after creation
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

    public function syncUnifiedMedia(Request $request, Model $model, string $directory): void
    {
        $model->loadMissing('images');

        // Remove images the user checked
        $imagesToRemove = $model->images
            ->whereIn('id', collect($request->input('remove_gallery_images', []))->map(fn ($id) => (int) $id))
            ->values();

        foreach ($imagesToRemove as $image) {
            Storage::disk('public')->delete($this->relativePath($image->path));
            $image->delete();
        }

        // Add newly uploaded images
        $newFiles = $request->file('gallery_images') ?? [];
        $newlyCreatedIds = [];

        foreach ($newFiles as $index => $file) {
            $created = $model->images()->create([
                'path' => Storage::url($file->store($directory, 'public')),
                'sort_order' => 999, // Temp sort order
            ]);
            $newlyCreatedIds["new_$index"] = $created->id;
        }

        // Determine logical primary
        $primaryRaw = $request->input('primary_image');
        $primaryId = null;

        if (is_string($primaryRaw) && str_starts_with($primaryRaw, 'new_')) {
            $primaryId = $newlyCreatedIds[$primaryRaw] ?? null;
        } else {
            $primaryId = (int) $primaryRaw;
        }

        // Finalize sort_orders
        $model->load('images');
        $counter = 1;
        foreach ($model->images as $image) {
            if ($image->id === $primaryId) {
                $image->update(['sort_order' => 0]);
            } else {
                $image->update(['sort_order' => $counter]);
                $counter++;
            }
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
