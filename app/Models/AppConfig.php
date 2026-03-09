<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Cache;

class AppConfig extends Model
{
    protected $table = 'app_configs';
    protected $fillable = ['key', 'value'];

    /**
     * Resgata uma configuração rapidamente usando Cache.
     */
    public static function getValor(string $key, $default = null)
    {
        return Cache::rememberForever("app_config_{$key}", function () use ($key, $default) {
            $config = self::where('key', $key)->first();
            return $config ? $config->value : $default;
        });
    }

    /**
     * Define ou atualiza uma configuração e limpa seu cache.
     */
    public static function setValor(string $key, $value)
    {
        $config = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("app_config_{$key}");
        
        return $config;
    }
}
