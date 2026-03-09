<?php

namespace App\Console\Commands;

use App\Models\Signin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EncryptExistingData extends Command
{
    protected $signature = 'security:encrypt-existing-data';
    protected $description = 'Encripta dados sensíveis (CPF, telefone, endereço) de registros existentes na tabela signins';

    public function handle()
    {
        $this->info('Iniciando encriptação de dados existentes...');
        $this->warn('ATENÇÃO: Este comando deve ser executado APENAS UMA VEZ, ANTES de ativar os casts encrypted no Model.');
        
        if (!$this->confirm('Deseja prosseguir? Isso irá re-salvar todos os registros de Signin com criptografia.')) {
            $this->info('Operação cancelada.');
            return 0;
        }

        $camposSensiveis = ['cpf', 'telefone_celular', 'endereco', 'bairro', 'cep'];
        $total = DB::table('signins')->count();
        $bar = $this->output->createProgressBar($total);
        $erros = 0;

        // Buscar registros crus via DB (sem casts do Model)
        DB::table('signins')->orderBy('id')->chunk(50, function ($registros) use ($camposSensiveis, $bar, &$erros) {
            foreach ($registros as $registro) {
                try {
                    $updates = [];
                    foreach ($camposSensiveis as $campo) {
                        $valor = $registro->$campo ?? null;
                        if ($valor && !$this->isEncrypted($valor)) {
                            $updates[$campo] = encrypt($valor);
                        }
                    }

                    if (!empty($updates)) {
                        DB::table('signins')->where('id', $registro->id)->update($updates);
                    }
                } catch (\Exception $e) {
                    $erros++;
                    Log::error('Erro ao encriptar registro.', [
                        'signin_id' => $registro->id,
                        'error' => $e->getMessage(),
                    ]);
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        if ($erros > 0) {
            $this->error("Concluído com {$erros} erros. Verifique os logs.");
        } else {
            $this->info('Todos os registros foram encriptados com sucesso!');
            $this->info('Agora você pode ativar os casts encrypted no Model Signin.');
        }

        return 0;
    }

    private function isEncrypted(string $value): bool
    {
        // Dados encriptados pelo Laravel começam com eyJ (base64 de JSON)
        return str_starts_with($value, 'eyJ');
    }
}
