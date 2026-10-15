<?php

namespace App\Controllers;

use App\Models\DocumentModel;

class Documents extends BaseController
{
    public function download(int $id)
    {
        $document = (new DocumentModel())
            ->where('id', $id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->first();

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Document not found.');
        }

        $path = $this->protectedPath($document['file'] ?? null);
        $fileName = url_title($document['title'], '-', true);
        $version = $document['version'] ? '-' . url_title($document['version'], '-', true) : '';

        return $this->response->download($path, null)->setFileName($fileName . $version . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    private function protectedPath(?string $relativePath): string
    {
        return portfolio_upload_path($relativePath, 'Document file not found.');
    }
}
