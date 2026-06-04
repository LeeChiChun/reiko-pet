<?php

namespace App\Contracts;

interface CloudStorageInterface
{
    public function upload(string $localPath, string $remotePath): string;

    public function delete(string $remotePath): bool;

    public function url(string $remotePath): string;
}
