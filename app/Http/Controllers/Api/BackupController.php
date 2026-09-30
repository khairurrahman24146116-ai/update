<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __construct(protected BackupService $service) {}

    public function store(Request $request)
    {
        try {
            $result = $this->service->create($request->user()->id);

            return response()->json(['message' => 'Backup berhasil dibuat.', 'filename' => $result['filename'], 'size' => $result['size']]);
        } catch (\Throwable $e) {
            Log::error('backup failed', ['error' => $e->getMessage()]);
            try {
                ActivityLog::create(['user_id' => $request->user()->id, 'action' => 'backup.failed', 'model_type' => null, 'model_id' => null, 'payload' => ['status' => 'failed']]);
            } catch (\Throwable) {
            }

            return response()->json(['message' => 'Backup gagal dibuat. Silakan coba lagi.'], 500);
        }
    }

    public function download(Request $request, string $filename)
    {
        $filename = basename($filename);
        if (! preg_match('/^madani-\d{8}-\d{6}\.sql\.gz$/', $filename)) {
            abort(404);
        }
        if (! Storage::disk('backups')->exists($filename)) {
            abort(404);
        }

        return Storage::disk('backups')->download($filename, $filename, ['Content-Type' => 'application/gzip']);
    }
}
