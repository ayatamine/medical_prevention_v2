<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class AdminController extends Controller
{
    public function markNotificationsAsRead()
    {
        try {
            $notifications =auth()->user()->unreadNotifications->markAsRead();
            Notification::make()
                    ->title('Marked as read successfully')
                    ->icon('heroicon-o-document-text') 
                    ->iconColor('success') 
                    ->send();
            return back();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function markSingleNotificationsAsRead($id)
    {
        try {
            DB::table('notifications')->where('id',$id)->update(['read_at'=>Carbon::now()]);

            Notification::make()
                    ->title('Marked as read successfully')
                    ->icon('heroicon-o-document-text') 
                    ->iconColor('success') 
                    ->send();
            return back();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
