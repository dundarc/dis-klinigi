<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Sadece giriş yapmış kullanıcının bildirimlerini, en yeniden eskiye doğru getir
        $notifications = $request->user()->notifications()->latest()->paginate(15);
        
        // Okunmamışları işaretle vs. eklenebilir.
        
        return response()->json($notifications);
    }
}