<?php

namespace App\Helpers;

use App\Models\Ebook;
use App\Models\Notification;
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

      // Notifikasi reviewers sebelum h-3 jam deadline
      $deadlineNotif = date('Y-m-d H:i:s', strtotime('+3 hours'));
      $subThemeDeadlines = SubTheme::where('dueDate', '<=', $deadlineNotif)->get();
      foreach ($subThemeDeadlines as $deadline) {
        $ebook = $deadline->ebook()->first();

        if ($ebook) {
          // get all ebook reviewes
          $reviews = $ebook->reviews()->get();

          // create notification for each reviewer
          foreach ($reviews as $review) {
            if ($review->acc !== 1) {
              // check if notification already exists
              $payload = [
                'ebookId' => $ebook->id,
                'userId' => $review->reviewerId,
                'description' => "Deadline hingga 3 jam sebelum pengajuan " . $ebook->title . " berakhir.",
              ];
              $notification = Notification::where($payload)->first();

              if (!$notification) {
                Notification::create($payload);
              }
            }
          }
        }
      }
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
