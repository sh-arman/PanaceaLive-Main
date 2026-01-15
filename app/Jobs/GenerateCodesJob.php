<?php

namespace Panacea\Jobs;

use Panacea\Code;
use Panacea\Order;
use Panacea\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateCodesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order_id, $company_id, $medicine_id, $quantity, $prefix, $filename, $user_id;

    public function __construct($order_id, $company_id, $medicine_id, $quantity, $prefix, $filename, $user_id)
    {
        $this->order_id = $order_id;
        $this->company_id = $company_id;
        $this->medicine_id = $medicine_id;
        $this->quantity = $quantity;
        $this->prefix = $prefix;
        $this->filename = $filename;
        $this->user_id = $user_id;
    }

// public function handle()
// {
//     Log::info('JOB START | order_id=' . $this->order_id . ' | qty=' . $this->quantity);

//     $template = Template::where('med_id', $this->medicine_id)
//         ->where('flag', 'active')
//         ->first();

//     $codesDir = public_path('codes');
//     if (!is_dir($codesDir)) {
//         mkdir($codesDir, 0775, true);
//     }

//     $filePath = $codesDir . '/' . $this->filename;
//     $file = fopen($filePath, 'w+');

//     $new_message = null;
//     if ($template && !empty($template->template_message)) {
//         $new_message = explode('PBN/REN MCKRTWS', $template->template_message);
//     }

//     // 🔑 Chunk rule
//     $chunkSize = ($this->quantity <= 100000) ? $this->quantity : 80000;

//     $taken = 0;
//     $jobStart = microtime(true);

//     while ($taken < $this->quantity) {

//         $chunkStart = microtime(true);
//         $beforeTaken = $taken;

//         // --------- DB PHASE (LOCK + RESERVE FAST) ---------
//         $codes = DB::transaction(function () use ($chunkSize, $taken) {

//             $rows = Code::where('status', 0)
//                 ->whereRaw('CHAR_LENGTH(code) = 7')
//                 ->where('code', 'not like', '%0%')
//                 ->orderBy('id')
//                 ->limit(min($chunkSize, $this->quantity - $taken))
//                 ->lockForUpdate()
//                 ->get();

//             if ($rows->isEmpty()) {
//                 return collect();
//             }

//             // Reserve immediately (prevents duplicates)
//             Code::whereIn('id', $rows->pluck('id'))
//                 ->update(['status' => $this->order_id]);

//             return $rows;
//         });

//         if ($codes->isEmpty()) {
//             break;
//         }

//         // --------- FILE I/O PHASE (NO DB LOCKS) ---------
//         foreach ($codes as $code) {

//             if ($taken >= $this->quantity) {
//                 break;
//             }

//             if (Session::get('id') == "1929" && $this->medicine_id == "3") {
//                 fputcsv($file, ["SMS (REN {$code->code})"]);
//             } elseif (!$new_message) {
//                 fputcsv($file, ["REN {$code->code}"]);
//             } elseif ($this->prefix === "6spcae") {
//                 fputcsv($file, ["REN     {$code->code}"]);
//             } else {
//                 fputcsv($file, [
//                     $new_message[0] . "REN {$code->code}" . $new_message[1]
//                 ]);
//             }

//             $taken++;
//         }

//         // --------- PERFORMANCE LOGGING ---------
//         $chunkTime = round(microtime(true) - $chunkStart, 2);
//         $chunkTaken = $taken - $beforeTaken;
//         $rate = $chunkTime > 0 ? round($chunkTaken / $chunkTime, 2) : 0;

//         Log::info(sprintf(
//             'CHUNK DONE | taken=%d | total=%d/%d | time=%ss | rate=%s codes/sec',
//             $chunkTaken,
//             $taken,
//             $this->quantity,
//             $chunkTime,
//             $rate
//         ));
//     }

//     fclose($file);

//     Order::where('id', $this->order_id)
//         ->update(['status' => 'finished']);

//     $totalTime = round(microtime(true) - $jobStart, 2);

//     Log::info(sprintf(
//         'JOB END | order_id=%d | total=%d | time=%ss',
//         $this->order_id,
//         $taken,
//         $totalTime
//     ));
// }



    public function handle()
    {
        Log::info('Generating codes for order JOBBB ' . date('Y-m-d H:i:s'));
        $template = Template::where('med_id', $this->medicine_id)
            ->where('flag', 'active')
            ->first();

        $codesDir = public_path('codes');
        if (!is_dir($codesDir)) {
            @mkdir($codesDir, 0775, true);
        }

        $file = fopen($codesDir . '/' . $this->filename, 'w+');
        Log::info('Generating codes for order JOBBB ' . date('Y-m-d H:i:s'));
        $query = Code::select('id', 'code')
            ->where('status', 0)
            ->where(DB::raw('CHAR_LENGTH(code)'), '=', 7)
            ->where('code', 'not like', '%0%')
            ->orderBy('id', 'desc');

       $chunkSize = ($this->quantity < 100000)
        ? $this->quantity
        : min(100000, (int) ceil($this->quantity / 5));

        // $chunkSize = 60000; //60k per chunk im que
        $taken = 0;

        $new_message = null;
        Log::info('Generating codes for order JOBBB ' . date('Y-m-d H:i:s'));
        if ($template && $template->template_message != "") {
            $new_message = explode("PBN/REN MCKRTWS", $template->template_message);
        }
        Log::info('Before chunk ' . date('Y-m-d H:i:s'));
        $query->chunk($chunkSize, function ($codes) use (&$taken, $file, $new_message) {
            Log::info('In chunk ' . date('Y-m-d H:i:s') . '---' . count($codes));
            if ($taken >= $this->quantity) return false;

            foreach ($codes as $code) {

                if ($taken >= $this->quantity) break;

                // Write CSV row
                if (Session::get('id') == "1929" && $this->medicine_id == "3") {
                    fputcsv($file, ["SMS (REN " . $code->code . ")"]);
                } elseif (!$new_message) {
                    fputcsv($file, ['REN ' . $code->code]);
                } elseif ($this->prefix == "6spcae") {
                    fputcsv($file, ["REN \x20\x20\x20\x20 " . $code->code]);
                } else {
                    fputcsv($file, [
                        $new_message[0] . "REN " . $code->code . $new_message[1]
                    ]);
                }

                // Update code status
                Code::where('id', $code->id)->update(['status' => $this->order_id]);

                $taken++;
            }

            return $taken < $this->quantity;
        });

        fclose($file);

        // Mark order finished
        Order::where('id', $this->order_id)->update(['status' => 'finished']);
    }


}
