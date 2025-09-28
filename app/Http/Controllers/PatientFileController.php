<?php
namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class PatientFileController extends Controller
{
    public function show(File $file)
    {
        $this->authorize('view', $file);

        if (!Storage::disk('public')->exists($file->path)) {
            abort(404);
        }

        return response()->download(Storage::disk('public')->path($file->path), $file->original_filename ?? basename($file->path));
    }
}
