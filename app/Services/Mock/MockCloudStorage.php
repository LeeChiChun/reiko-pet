<?php

namespace App\Services\Mock;

use App\Contracts\CloudStorageInterface;
use Illuminate\Support\Facades\Log;

class MockCloudStorage implements CloudStorageInterface
{
    public function upload(string $localPath, string $remotePath): string
    {
        Log::info("[Mock Storage] upload local={$localPath} remote={$remotePath}");
        return '/storage/' . $remotePath;
    }

    public function delete(string $remotePath): bool
    {
        Log::info("[Mock Storage] delete remote={$remotePath}");
        return true;
    }

    public function url(string $remotePath): string
    {
        return '/storage/' . $remotePath;
    }
}
