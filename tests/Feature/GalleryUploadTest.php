<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Trek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_trek_with_multiple_gallery_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.treks.store'), [
            'title' => 'Everest Base Camp',
            'base_price' => 1299,
            'difficulty' => 'Difficult',
            'duration_days' => 12,
            'max_altitude' => 5364,
            'status' => 'Active',
            'description' => 'Classic Everest region trek.',
            'image' => $this->fakePng('hero.png'),
            'gallery_images' => [
                $this->fakePng('gallery-1.png'),
                $this->fakePng('gallery-2.png'),
            ],
        ]);

        $response->assertRedirect();

        $trek = Trek::query()->where('title', 'Everest Base Camp')->firstOrFail();

        $this->assertCount(2, $trek->gallery);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $trek->image));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $trek->gallery[0]->path));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $trek->gallery[1]->path));
    }

    public function test_hotel_owner_can_create_a_hotel_with_multiple_gallery_images(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create([
            'role' => 'hotel_owner',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($owner)->post(route('hotel_owner.hotels.store'), [
            'name' => 'Summit Stay Lodge',
            'location' => 'Namche Bazaar',
            'description' => 'Warm hospitality for trekkers heading higher into the Khumbu.',
            'image' => $this->fakePng('hotel-hero.png'),
            'gallery_images' => [
                $this->fakePng('room.png'),
                $this->fakePng('view.png'),
            ],
        ]);

        $response->assertRedirect();

        $hotel = Hotel::query()->where('name', 'Summit Stay Lodge')->firstOrFail();

        $this->assertSame($owner->id, $hotel->owner_id);
        $this->assertSame('Pending', $hotel->status);
        $this->assertCount(2, $hotel->gallery);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $hotel->image));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $hotel->gallery[0]->path));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $hotel->gallery[1]->path));
    }

    protected function fakePng(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');

        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a7d0AAAAASUVORK5CYII='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
