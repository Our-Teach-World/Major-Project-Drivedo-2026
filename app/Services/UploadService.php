<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class UploadService
{
    public static function sanitizeUsername(string $username): string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $username);
    }

    public static function getUploadDirectory(string $username): string
    {
        $sanitized = self::sanitizeUsername($username);
        return public_path('uploads/' . $sanitized . '/');
    }

    public static function ensureUploadDirectory(string $username): string
    {
        $dir = self::getUploadDirectory($username);
        @mkdir($dir, 0755, true);
        return $dir;
    }

    public static function saveProfileImage(UploadedFile $file, string $username): string
    {
        $uploadDir = self::ensureUploadDirectory($username);
        $file->move($uploadDir, 'profile.jpg');
        return $uploadDir . 'profile.jpg';
    }
}
