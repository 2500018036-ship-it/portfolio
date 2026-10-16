<?php

namespace App\Controllers;

use App\Models\CertificateModel;

class Certificates extends BaseController
{
    public function show(string $slug): string
    {
        $certificate = $this->publishedCertificate($slug);
        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];
        $ogImage = $certificate['certificate_image']
            ? site_url('certificates/' . $certificate['slug'] . '/image')
            : $viewDefaults['defaultOgImage'];

        return view('public/certificates/show', array_merge($viewDefaults, [
            'title'           => $certificate['title'] . ' | ' . $siteTitle,
            'metaDescription' => $certificate['description'] ?: ($certificate['issuer'] ? 'Certificate issued by ' . $certificate['issuer'] : 'Certificate detail.'),
            'canonicalUrl'    => site_url('certificates/' . $certificate['slug']),
            'ogImage'         => $ogImage,
            'certificate'     => $certificate,
        ]));
    }

    public function image(string $slug)
    {
        $certificate = $this->publishedCertificate($slug);

        return $this->protectedInlineFile($certificate['certificate_image'] ?? null, $certificate['slug']);
    }

    public function download(string $slug)
    {
        $certificate = $this->publishedCertificate($slug);

        return $this->protectedDownload($certificate['certificate_file'] ?? null, $certificate['slug']);
    }

    private function publishedCertificate(string $slug): array
    {
        $certificate = (new CertificateModel())
            ->select('certificates.*, certificate_categories.name AS category_name')
            ->join('certificate_categories', 'certificate_categories.id = certificates.category_id', 'left')
            ->where('certificates.slug', $slug)
            ->where('certificates.status', 'published')
            ->where('certificates.is_dummy', 0)
            ->first();

        if (! $certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Certificate not found.');
        }

        return $certificate;
    }

    private function protectedInlineFile(?string $relativePath, string $fileName)
    {
        $path = $this->protectedPath($relativePath);
        $mimeType = mime_content_type($path) ?: 'application/octet-stream';

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '.' . pathinfo($path, PATHINFO_EXTENSION) . '"')
            ->setBody(file_get_contents($path));
    }

    private function protectedDownload(?string $relativePath, string $fileName)
    {
        $path = $this->protectedPath($relativePath);

        return $this->response->download($path, null)->setFileName($fileName . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    private function protectedPath(?string $relativePath): string
    {
        return portfolio_upload_path($relativePath, 'File not found.');
    }

}
