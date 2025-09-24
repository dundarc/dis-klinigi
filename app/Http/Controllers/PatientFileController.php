<?php
namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class PatientFileController extends Controller
{
    public function show(File $file)
    {
        $this->authorize('view', $file);

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($file->file_path, basename($file->file_path));
    }
}
