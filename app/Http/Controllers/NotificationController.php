<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('sender')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function createSend()
    {
        $this->authorize('create', Notification::class);

        $recipients = collect();
        $user = Auth::user();

        if ($user->role === UserRole::ADMIN) {
            $recipients = User::whereIn('role', [UserRole::DENTIST, UserRole::RECEPTIONIST])->get();
        } elseif ($user->role === UserRole::DENTIST) {
            $recipients = User::where('role', UserRole::RECEPTIONIST)->get();
        }

        return view('notifications.send', compact('recipients'));
    }

    public function storeSend(Request $request)
    {
        $this->authorize('create', Notification::class);

        $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'string', 'in:info,task'],
        ]);

        $recipient = User::findOrFail($request->recipient_id);

        // Authorization check
        $user = Auth::user();
        if ($user->role === UserRole::DENTIST && $recipient->role !== UserRole::RECEPTIONIST) {
            abort(403, 'Doktorlar sadece resepsiyonistlere bildirim gönderebilir.');
        }

        Notification::create([
            'user_id' => $recipient->id,
            'sender_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'type' => $request->type,
        ]);

        return redirect()->route('notifications.index')->with('success', 'Bildirim başarıyla gönderildi.');
    }

    public function delivered()
    {
        $this->authorize('create', Notification::class);

        $sentNotifications = Notification::where('sender_id', Auth::id())
            ->with('user') // Eager load the recipient
            ->latest()
            ->paginate(20);

        return view('notifications.delivered', compact('sentNotifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);
        $notification->update(['read_at' => now()]);

        return back()->with('success', 'Bildirim okundu olarak işaretlendi.');
    }

    public function markAsUnread(Notification $notification)
    {
        $this->authorize('update', $notification);
        $notification->update(['read_at' => null]);

        return back()->with('success', 'Bildirim okunmadı olarak işaretlendi.');
    }

    public function markAsCompleted(Notification $notification)
    {
        $this->authorize('update', $notification);
        $notification->update(['completed_at' => now()]);

        return back()->with('success', 'İş emri tamamlandı olarak işaretlendi.');
    }

    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        if (!$notification->read_at) {
            return back()->with('error', 'Okunmamış bir bildirimi silemezsiniz.');
        }

        $notification->delete();

        return back()->with('success', 'Bildirim başarıyla silindi.');
    }
}
