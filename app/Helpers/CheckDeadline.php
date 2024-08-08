<?php

namespace App\Helpers;

use App\Models\SubTheme;

class CheckDeadline
{

  public static function run()
  {
    $subThemeDeadlines = SubTheme::where('dueDate', '<=', date('Y-m-d H:i:s'))->get();

    foreach ($subThemeDeadlines as $deadline) {
      $ebooks = $deadline->ebook()->where('draft', null)->get();

      foreach ($ebooks as $ebook) {
        $ebook->delete();
      }
    }
  }
}
