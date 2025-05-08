<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImpressaoComponent extends Component
{
    /**
     * Crie quaisquer propriedades públicas que quiser passar para a view aqui
     */

    public function __construct()
    {
        // Inicialize dados se necessário
    }

    /**
     * Retorna a view do componente.
     */
    public function render()
    {
        return view('layouts.impressao');
    }
}
