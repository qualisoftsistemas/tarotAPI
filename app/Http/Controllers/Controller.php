<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class Controller
{
    protected function timestampBr(): string
    {
        return Carbon::now()->format('d/m/Y H:i:s');
    }

    protected function cacheKey(string $description): string
    {
        return Str::slug(config('app.name') . '_' . static::class . '_' . $description);
    }

    protected function todasAsCartas(): Collection
    {
        $cacheKey = $this->cacheKey('todas_cartas');

        return Cache::rememberForever($cacheKey, function () {
            return Carta::all();
        });
    }
}
