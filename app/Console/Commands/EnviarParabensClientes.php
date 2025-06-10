<?php

namespace App\Console\Commands;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class EnviarParabensClientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-parabens-clientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoje = now()->format('m-d');

        $clientes = Cliente::whereRaw("DATE_FORMAT(data_nascimento, '%m-%d') = ?", [$hoje])->get();

        foreach ($clientes as $cliente) {
            if ($cliente->relacionamento?->tem_whatsapp) {
                Http::post('https://api.z-api.io/instances/3E28372FE24150EA84892A9B2850D0B6/token/3D1D1A89F97F705C7092ABC6/phone-exists', [
                    'phone' => preg_replace('/\D/', '', $cliente->telefone),
                    'message' => "🎉 Olá {$cliente->nome}, parabéns pelo seu aniversário! 🎂 Que seu dia seja incrível!",
                ]);
            }
        }

        $this->info("Mensagens de parabéns enviadas com sucesso.");
    }
}
