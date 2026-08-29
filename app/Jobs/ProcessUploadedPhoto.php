<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessUploadedPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $tempPath;
    protected string $field;
    protected string $unidad;

    public function __construct(string $tempPath, string $field, string $unidad)
    {
        $this->tempPath = $tempPath;
        $this->field    = $field;
        $this->unidad   = $unidad;
    }

    public function handle()
    {
        $fullTemp = storage_path("app/public/{$this->tempPath}");

        // 1) Procesar imagen (resize, thumbnails, etc.)
        $img = Image::make($fullTemp)
                    ->resize(1200, null, fn($c) => $c->aspectRatio());

        // 2) Guardar en ruta definitiva
        $month    = now()->format('Y-m');
        $filename = basename($this->tempPath);
        $finalDir = "medios/{$month}/{$this->unidad}";

        Storage::disk('public')->put("{$finalDir}/{$filename}", (string)$img->encode());

        // 3) Borrar temporal
        Storage::disk('public')->delete($this->tempPath);
    }
}