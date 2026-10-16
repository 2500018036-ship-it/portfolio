<?php

use CodeIgniter\Exceptions\PageNotFoundException;

if (! function_exists('portfolio_asset_url')) {
    function portfolio_asset_url(?string $path, ?string $fallback = null): ?string
    {
        $path = trim((string) $path);

        if ($path === '' || preg_match('/(?:placeholder|(?:^|\/)demo[-_])/i', $path)) {
            return $fallback;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $normalized) || str_contains($normalized, '..')) {
            return $fallback;
        }

        if (str_starts_with($normalized, 'uploads/')) {
            return base_url($normalized);
        }

        if (is_file(FCPATH . 'uploads/' . str_replace('/', DIRECTORY_SEPARATOR, $normalized))) {
            return base_url('uploads/' . $normalized);
        }

        return base_url($normalized);
    }
}

if (! function_exists('portfolio_upload_path')) {
    function portfolio_upload_path(?string $relativePath, string $errorMessage = 'File not found.'): string
    {
        $relativePath = trim((string) $relativePath);

        if ($relativePath === '') {
            throw PageNotFoundException::forPageNotFound($errorMessage);
        }

        $normalized = ltrim(str_replace('\\', '/', $relativePath), '/');

        if (
            preg_match('/^[a-z][a-z0-9+.-]*:/i', $normalized)
            || str_contains($normalized, '..')
            || str_starts_with($normalized, '/')
        ) {
            throw PageNotFoundException::forPageNotFound($errorMessage);
        }

        $candidates = [];

        if (str_starts_with($normalized, 'uploads/')) {
            $candidates[] = [WRITEPATH . str_replace('/', DIRECTORY_SEPARATOR, $normalized), WRITEPATH . 'uploads'];
            $candidates[] = [FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $normalized), FCPATH . 'uploads'];
        } else {
            $legacyPath = 'uploads/' . $normalized;
            $candidates[] = [WRITEPATH . str_replace('/', DIRECTORY_SEPARATOR, $legacyPath), WRITEPATH . 'uploads'];
            $candidates[] = [FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $legacyPath), FCPATH . 'uploads'];
        }

        foreach ($candidates as [$candidate, $base]) {
            $basePath = realpath($base);
            $filePath = realpath($candidate);

            if (
                $basePath
                && $filePath
                && is_file($filePath)
                && str_starts_with($filePath, $basePath . DIRECTORY_SEPARATOR)
            ) {
                return $filePath;
            }
        }

        throw PageNotFoundException::forPageNotFound($errorMessage);
    }
}
