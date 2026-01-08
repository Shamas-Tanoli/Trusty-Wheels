<?php
namespace App\Jobs;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVehiclePicUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicleId;
    protected $tempFiles;
    protected $sortedImages;
    protected $lastOrder;

    public function __construct($vehicleId, array $tempFiles, array $sortedImages, int $lastOrder)
    {
        $this->vehicleId = $vehicleId;
        $this->tempFiles = $tempFiles;
        $this->sortedImages = $sortedImages;
        $this->lastOrder = $lastOrder;
    }

    public function handle()
    {
        $vehicle = Vehicle::find($this->vehicleId);
        if (!$vehicle) return;

        $manager = new ImageManager(new GdDriver());
        $targetWidth = 1600;
        $targetHeight = 900;

        $watermarkPath = public_path('assets/img/watermark.png');

        $sortedNewImages = array_filter($this->sortedImages, fn($img) => empty($img['id']));
        usort($sortedNewImages, fn($a, $b) => $a['order'] - $b['order']);

        foreach ($sortedNewImages as $index => $imageData) {
            $tempFilePath = array_shift($this->tempFiles);
            if (!$tempFilePath) continue;

            $filePath = storage_path('app/' . $tempFilePath);
            if (!file_exists($filePath)) continue;

            try {
                $fileName = "{$vehicle->id}-" . $imageData['order'] . "-" . time() . ".webp";
                $image = $manager->read($filePath);

                // scale
                if ($image->height() > $image->width()) {
                    $image = $image->scale(height: $targetHeight);
                } else {
                    $image = $image->scale(width: $targetWidth);
                }

                // canvas white background
                $canvas = $manager->create($targetWidth, $targetHeight, '#ffffff');
                $x = ($targetWidth - $image->width()) / 2;
                $y = ($targetHeight - $image->height()) / 2;
                $canvas->place($image, 'top-left', (int)$x, (int)$y);

                // watermark add
                if (file_exists($watermarkPath)) {
                    $canvas->place($watermarkPath, 'bottom-right', 10, 10, 90);
                }

                // encode & save
                $encodedImage = $canvas->encode(new WebpEncoder(quality: 90));
                $relativePath = "assets/img/vehicle_images/{$fileName}";
                $fullPath = public_path($relativePath);
                file_put_contents($fullPath, $encodedImage->toString());

                // db entry
                $vehicle->vehicleImages()->create([
                    'image_url' => $fileName,
                    'image_order' => $imageData['order']
                ]);

            } catch (\Exception $e) {
                Log::error("Image update error: " . $e->getMessage());
            } finally {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }
}
