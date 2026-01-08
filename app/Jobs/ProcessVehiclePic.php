<?php

namespace App\Jobs;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ProcessVehiclePic implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $vehicleId, $files;

    public function __construct($vehicleId, $files)
    {
        $this->vehicleId = $vehicleId;
        $this->files = $files;
    }

    public function handle()
    {
        $vehicle = Vehicle::find($this->vehicleId);
        if (!$vehicle) return;

        $manager = new ImageManager(new GdDriver());
        $watermarkPath = public_path('assets/img/watermark.png');

        foreach ($this->files as $index => $file) {
            try {
                // original image
                $image = $manager->read($file);

                // resize with aspect ratio (crop nahi hoga)
                $image = $image->contain(1600, 900); 

                // canvas create karein
                $canvas = $manager->create(1600, 900, '#ffffff'); // white background

                // center me image place
                $canvas->place($image, 'center');

                // watermark add karein
                if (file_exists($watermarkPath)) {
                    $canvas->place($watermarkPath, 'bottom-right', 10, 10, 90); // 90 opacity
                }

                // file save name
                $fileName = "{$vehicle->id}-{$index}-" . time() . ".webp";
                $directory = public_path('assets/img/vehicle_images');

                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $fullPath = $directory . '/' . $fileName;

                // save as webp
                $canvas->encode(new WebpEncoder(quality: 90))->save($fullPath);

                // db entry
                $vehicle->vehicleImages()->create([
                    'image_url' => $fileName,
                    'image_order' => $index + 1,
                ]);
            } catch (\Exception $e) {
                Log::error("Error processing image: " . $e->getMessage());
            } finally {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}
