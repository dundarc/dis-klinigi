<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemTrashController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:accessAdminFeatures');
    }

    public function index()
    {
        $trashedFiles = File::onlyTrashed()
            ->with(['patient:id,first_name,last_name,national_id', 'uploader:id,name'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(25);

        return view('system.trash-docs', compact('trashedFiles'));
    }

    public function restore($id)
    {
        $file = File::onlyTrashed()->findOrFail($id);
        $file->restore();

        return redirect()->back()->with('success', 'Dosya başarıyla geri yüklendi.');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->findOrFail($id);

        // Delete the actual file from storage
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->forceDelete();

        return redirect()->back()->with('success', 'Dosya kalıcı olarak silindi.');
    }

    public function bulkRestore(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:files,id'
        ]);

        File::onlyTrashed()
            ->whereIn('id', $request->file_ids)
            ->restore();

        return redirect()->back()->with('success', 'Seçili dosyalar başarıyla geri yüklendi.');
    }

    public function bulkForceDelete(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:files,id'
        ]);

        $files = File::onlyTrashed()->whereIn('id', $request->file_ids)->get();

        foreach ($files as $file) {
            // Delete the actual file from storage
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->forceDelete();
        }

        return redirect()->back()->with('success', 'Seçili dosyalar kalıcı olarak silindi.');
    }
}