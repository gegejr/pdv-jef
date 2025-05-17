<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function showPurchasePage()
    {
        return view('subscription.purchase');
    }

    public function renew(Request $request)
    {
        $user = Auth::user();

        // Define a data de vencimento para o dia 10 do próximo mês
        $nextMonth = now()->addMonthNoOverflow();
        $dueDate = $nextMonth->copy()->day(10)->endOfDay();

        $user->update([
            'subscription_ends_at' => $dueDate,
            'is_subscription_active' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Assinatura renovada até ' . $dueDate->format('d/m/Y') . '!');
    }


    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if ($request->password !== 'liberar123') {
            return redirect()->back()->with('error', 'Senha incorreta.');
        }

        $user = Auth::user();

        $now = Carbon::now();
        // Define o dia 10 do mês atual, 23:59:59
        $nextDueDate = $now->copy()->day(10)->endOfDay();

        // Se já passou do dia 10, pula para o próximo mês
        if ($now->day > 10) {
            $nextDueDate = $nextDueDate->addMonthNoOverflow();
        }

        $user->subscription_ends_at = $nextDueDate;
        $user->is_subscription_active = true;
        $user->save();

        Auth::login($user->fresh());

        return redirect()->route('painel')->with('success', 'Assinatura renovada até o dia 10 de ' . $nextDueDate->format('m/Y'));
    }
}
