<?php

namespace App\Http\Controllers;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function analyze(Request $request)
    {
        $transactions = auth()->user()
            ->transactions()
            ->where('type', 'expense')
            ->latest()
            ->get(['category', 'amount', 'transaction_date']);

        if ($transactions->isEmpty()) {
            return response()->json([
                'result' => 'Belum ada data transaksi untuk dianalisis.'
            ]);
        }

        $dataJson = $transactions->map(fn($t) => [
            'category' => $t->category,
            'amount'   => $t->amount,
            'date'     => $t->transaction_date->format('Y-m-d'),
        ])->toJson(JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
Kamu adalah financial assistant yang membantu pengguna mengelola keuangan pribadi.

Analisis data transaksi pengeluaran berikut:
$dataJson

Berikan analisis dalam format berikut (gunakan bahasa Indonesia):
1. **Pengeluaran Terbesar** - kategori apa yang paling banyak menghabiskan uang
2. **Kebiasaan Buruk** - pola pengeluaran yang perlu diperbaiki
3. **Saran Penghematan** - 3 rekomendasi konkret
4. **Tips Finansial** - 1 tips motivasi keuangan

Gunakan emoji yang relevan. Jawab dengan ringkas dan actionable.
PROMPT;

        $result = $result = Gemini::generativeModel(model: 'gemini-2.5-flash')->generateContent($prompt);
        $text   = $result->text();

        return response()->json(['result' => $text]);
    }

    public function stream()
    {
        $transactions = auth()->user()
            ->transactions()
            ->where('type', 'expense')
            ->latest()
            ->get(['category', 'amount', 'transaction_date']);

        if ($transactions->isEmpty()) {
            return response()->stream(function () {
                echo "data: Belum ada data transaksi.\n\n";
                ob_flush(); flush();
            }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache']);
        }

        $dataJson = $transactions->map(fn($t) => [
            'category' => $t->category,
            'amount'   => $t->amount,
            'date'     => $t->transaction_date->format('Y-m-d'),
        ])->toJson(JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
Kamu adalah financial assistant. Analisis transaksi berikut:
$dataJson

Berikan:
1. Pengeluaran terbesar
2. Kebiasaan buruk
3. Saran penghematan (3 poin)
4. Tips finansial

Gunakan bahasa Indonesia dan emoji. Jawab ringkas.
PROMPT;

        return response()->stream(function () use ($prompt) {
            $stream = Gemini::generativeModel(model: 'gemini-2.5-flash')->streamGenerateContent($prompt);

            foreach ($stream as $response) {
                $text = $response->text();
                $text = str_replace("\n", '<br>', $text);
                echo "data: " . $text . "\n\n";
                ob_flush();
                flush();
            }

            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}