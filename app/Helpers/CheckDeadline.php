<?php

namespace App\Helpers;

use App\Models\Ebook;
use App\Models\SubTheme;

class CheckDeadline
{

  public static function run()
  {
    try {
      // Hapus yang deadline-nya habis
      $subThemeDeadlines = SubTheme::where('dueDate', '<=', date('Y-m-d H:i:s'))->get();
      foreach ($subThemeDeadlines as $deadline) {
        $ebooks = $deadline->ebook()->where('draft', null)->get();

        foreach ($ebooks as $ebook) {
          $ebook->delete();
        }
      }

      // Hapus yang belum konfirmasi pembayaran
      $ebooks = Ebook::where('proofOfPayment', null)
        ->where('status', Ebook::STATUS_PAYMENT)
        ->where('dueDate', '<=', date('Y-m-d H:i:s'))
        ->get();
      foreach ($ebooks as $ebook) {
        $ebook->delete();
      }
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
