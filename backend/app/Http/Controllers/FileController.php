<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $files = $request->user()
            ->files()
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($files);
    }

    public function store(FileUploadRequest $request): JsonResponse
    {
        $user  = $request->user();
        $saved = [];

        foreach ($request->file('files') as $file) {
            $uuid     = (string) Str::uuid();
            $ext      = $file->getClientOriginalExtension();
            $diskPath = "uploads/{$user->id}/{$uuid}.{$ext}";

            Storage::disk('local')->put($diskPath, file_get_contents($file->getRealPath()));

            $saved[] = File::create([
                'user_id' => $user->id,
                'name'    => $file->getClientOriginalName(),
                'path'    => $diskPath,
                'size'    => $file->getSize(),
                'type'    => $file->getMimeType(),
            ]);
        }

        return response()->json($saved, 201);
    }

    public function download(Request $request, File $file): StreamedResponse
    {
        abort_if($file->user_id !== $request->user()->id, 403);
        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return Storage::disk('local')->download($file->path, $file->name);
    }

    public function destroy(Request $request, File $file): JsonResponse
    {
        abort_if($file->user_id !== $request->user()->id, 403);

        Storage::disk('local')->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
