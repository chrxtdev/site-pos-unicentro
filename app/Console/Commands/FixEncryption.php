<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Signin;
use Illuminate\Support\Facades\DB;

class FixEncryption extends Command
{
    protected $signature = 'security:fix-encryption';
    protected $description = 'Corrige dados criptografados que possuem wrapper de serialização (s:14...)';

    public function handle()
    {
        $this->info('Iniciando correção de encriptação serializada...');
        
        $camposSensiveis = ['cpf', 'telefone_celular', 'endereco', 'bairro', 'cep'];
        $corrigidos = 0;
        
        $this->withProgressBar(Signin::count(), function ($bar) use ($camposSensiveis, &$corrigidos) {
            Signin::query()->chunkById(500, function ($signins) use ($camposSensiveis, &$corrigidos, $bar) {
                foreach ($signins as $signin) {
                    $mudou = false;
                    foreach ($camposSensiveis as $campo) {
                        $valorDescriptografado = $signin->$campo;
                        
                        if (is_string($valorDescriptografado) && preg_match('/^[a-z]:[0-9]+:/i', $valorDescriptografado)) {
                            $valorReal = @unserialize($valorDescriptografado);
                            if ($valorReal !== false && is_string($valorReal)) {
                                $signin->$campo = $valorReal;
                                $mudou = true;
                            }
                        }
                    }
                    
                    if ($mudou) {
                        $signin->save();
                        $corrigidos++;
                    }
                    $bar->advance();
                }
            });
        });

        $this->newLine(2);
        $this->info("Total de registros corrigidos: {$corrigidos}");
        return 0;
    }
}
