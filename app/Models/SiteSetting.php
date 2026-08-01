<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'sort_order',
    ];

    public static function getCcEmailsByKey(string $key): array
    {
        $value = self::where('key', $key)->value('value');
        if (empty($value)) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $value)));
    }
}
