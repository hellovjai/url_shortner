<?php

namespace App\Observers;

use App\Models\ShortUrl;
use Illuminate\Support\Str;

class UrlObserver
{
    public function creating($model)
    {
        do {
            $model->short_code = Str::random(6);
        } while (ShortUrl::where('short_code', $model->short_code)->exists());
    }
}
