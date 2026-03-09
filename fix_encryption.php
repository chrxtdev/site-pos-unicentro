<?php
use App\Models\Signin;
use Illuminate\Support\Facades\Log;

$camposSensiveis = ['cpf', 'telefone_celular', 'endereco', 'bairro', 'cep'];
$signins = Signin::all();
$corrigidos = 0;

foreach ($signins as $signin) {
    try {
        $mudou = false;
        foreach ($camposSensiveis as $campo) {
            $valorAtual = $signin->$campo;
            // Se possui prefixo s: e está serializado, vamos unserialize
            if (is_string($valorAtual) && preg_match('/^[a-z]:[0-9]+:/i', $valorAtual)) {
                $valorReal = @unserialize($valorAtual);
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
    } catch (\Exception $e) {
        echo "Erro no ID " . $signin->id . ": " . $e->getMessage() . "\n";
    }
}
echo "Total corrigidos: " . $corrigidos . "\n";
