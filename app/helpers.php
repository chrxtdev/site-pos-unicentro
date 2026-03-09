<?php

use App\Models\AppConfig;

if (!function_exists('get_config')) {
    /**
     * Resgata uma configuração global da aplicação armazenada em banco (com Cache).
     *
     * @param string $key Chave da configuração.
     * @param mixed $default Valor padrão caso não exista.
     * @return mixed
     */
    function get_config($key, $default = null)
    {
        return AppConfig::getValor($key, $default);
    }
}
