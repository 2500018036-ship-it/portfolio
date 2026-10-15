<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Files\UploadedFile;
use DOMDocument;
use DOMElement;
use DOMNode;
use RuntimeException;

abstract class AdminController extends BaseController
{
    protected function storeUploadedFile(string $field, string $directory, array $allowedExtensions, int $maxSizeKb = 2048): ?string
    {
        $file = $this->request->getFile($field);

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return $this->storeSingleUploadedFile($file, $directory, $allowedExtensions, $maxSizeKb);
    }

    protected function storeProtectedFile(string $field, string $directory, array $allowedExtensions, int $maxSizeKb = 4096): ?string
    {
        $file = $this->request->getFile($field);

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return $this->storeSingleProtectedFile($file, $directory, $allowedExtensions, $maxSizeKb);
    }

    /**
     * @return list<string>
     */
    protected function storeUploadedFiles(string $field, string $directory, array $allowedExtensions, int $maxSizeKb = 2048): array
    {
        $files = $this->request->getFileMultiple($field);

        if (! is_array($files)) {
            return [];
        }

        $stored = [];

        foreach ($files as $file) {
            if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $stored[] = $this->storeSingleUploadedFile($file, $directory, $allowedExtensions, $maxSizeKb);
        }

        return $stored;
    }

    protected function storeSingleUploadedFile(UploadedFile $file, string $directory, array $allowedExtensions, int $maxSizeKb = 2048): string
    {
        $this->assertUploadedFileIsSafe($file, $allowedExtensions, $maxSizeKb);

        $directory = trim(str_replace('\\', '/', $directory), '/');

        if ($directory === '' || str_contains($directory, '..')) {
            throw new RuntimeException('Direktori upload tidak valid.');
        }

        $target = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $directory);

        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $fileName = $file->getRandomName();
        $file->move($target, $fileName);

        return 'uploads/' . $directory . '/' . $fileName;
    }

    protected function storeSingleProtectedFile(UploadedFile $file, string $directory, array $allowedExtensions, int $maxSizeKb = 4096): string
    {
        $this->assertUploadedFileIsSafe($file, $allowedExtensions, $maxSizeKb);

        $directory = trim(str_replace('\\', '/', $directory), '/');

        if ($directory === '' || str_contains($directory, '..')) {
            throw new RuntimeException('Direktori upload tidak valid.');
        }

        $target = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $directory);

        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $fileName = $file->getRandomName();
        $file->move($target, $fileName);

        return 'uploads/' . $directory . '/' . $fileName;
    }

    protected function assertUploadedFileIsSafe(UploadedFile $file, array $allowedExtensions, int $maxSizeKb): void
    {
        if (! $file->isValid()) {
            throw new RuntimeException($file->getErrorString());
        }

        $extension = strtolower($file->getClientExtension());
        $allowedExtensions = array_map('strtolower', $allowedExtensions);

        if (! in_array($extension, $allowedExtensions, true)) {
            throw new RuntimeException('Tipe file tidak diizinkan.');
        }

        if (in_array($extension, ['php', 'php3', 'php4', 'php5', 'phtml', 'phar', 'exe', 'bat', 'cmd', 'com', 'sh', 'js', 'html', 'svg'], true)) {
            throw new RuntimeException('File executable tidak diizinkan.');
        }

        if ($file->getSizeByUnit('kb') > $maxSizeKb) {
            throw new RuntimeException('Ukuran file terlalu besar.');
        }

        $mimeType = strtolower((string) $file->getMimeType());
        $allowedMimeTypes = $this->allowedMimeTypes($allowedExtensions);

        if ($allowedMimeTypes !== [] && ! in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException('MIME type file tidak sesuai.');
        }

        if ($this->containsImageExtension($allowedExtensions) && @getimagesize($file->getTempName()) === false) {
            throw new RuntimeException('File gambar tidak valid.');
        }
    }

    protected function removePublicUpload(?string $relativePath): void
    {
        if (! $relativePath || ! str_starts_with($relativePath, 'uploads/')) {
            return;
        }

        $base = realpath(FCPATH . 'uploads');
        $path = realpath(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));

        if ($base && $path && str_starts_with($path, $base . DIRECTORY_SEPARATOR) && is_file($path)) {
            unlink($path);
        }
    }

    protected function removeProtectedUpload(?string $relativePath): void
    {
        if (! $relativePath || ! str_starts_with($relativePath, 'uploads/')) {
            return;
        }

        $base = realpath(WRITEPATH . 'uploads');
        $path = realpath(WRITEPATH . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));

        if ($base && $path && str_starts_with($path, $base . DIRECTORY_SEPARATOR) && is_file($path)) {
            unlink($path);
        }
    }

    protected function slugFromInput(string $title, ?string $slug = null): string
    {
        $source = trim((string) ($slug ?: $title));
        $generated = url_title($source, '-', true);

        return $generated !== '' ? $generated : uniqid('item-', false);
    }

    protected function sanitizeContentHtml(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $allowedTags = [
            'a'          => ['href', 'title', 'target', 'rel'],
            'blockquote' => ['title'],
            'br'         => [],
            'code'       => ['class'],
            'em'         => ['title'],
            'h1'         => ['title'],
            'h2'         => ['title'],
            'h3'         => ['title'],
            'h4'         => ['title'],
            'h5'         => ['title'],
            'h6'         => ['title'],
            'hr'         => [],
            'i'          => ['title'],
            'img'        => ['src', 'alt', 'title', 'width', 'height', 'loading', 'decoding'],
            'li'         => ['title'],
            'ol'         => ['title'],
            'p'          => ['title'],
            'pre'        => ['class'],
            'strong'     => ['title'],
            'table'      => ['class'],
            'tbody'      => [],
            'td'         => ['colspan', 'rowspan'],
            'th'         => ['colspan', 'rowspan'],
            'thead'      => [],
            'tr'         => [],
            'u'          => ['title'],
            'ul'         => ['title'],
        ];
        $removeWithContent = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'link', 'meta', 'base'];
        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="sanitize-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('sanitize-root');

        if (! $root) {
            return '';
        }

        $this->sanitizeHtmlNode($root, $allowedTags, $removeWithContent);

        return trim($this->nodeInnerHtml($root));
    }

    private function sanitizeHtmlNode(DOMNode $node, array $allowedTags, array $removeWithContent): void
    {
        for ($child = $node->firstChild; $child !== null;) {
            $next = $child->nextSibling;

            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);

                if (! isset($allowedTags[$tag])) {
                    if (in_array($tag, $removeWithContent, true)) {
                        $node->removeChild($child);
                    } else {
                        $this->sanitizeHtmlNode($child, $allowedTags, $removeWithContent);

                        while ($child->firstChild) {
                            $node->insertBefore($child->firstChild, $child);
                        }

                        $node->removeChild($child);
                    }
                } else {
                    $this->sanitizeHtmlAttributes($child, $allowedTags[$tag]);
                    $this->sanitizeHtmlNode($child, $allowedTags, $removeWithContent);
                }
            } elseif ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
            }

            $child = $next;
        }
    }

    private function sanitizeHtmlAttributes(DOMElement $element, array $allowedAttributes): void
    {
        $tag = strtolower($element->tagName);
        $attributes = [];

        foreach ($element->attributes as $attribute) {
            $attributes[] = $attribute->name;
        }

        foreach ($attributes as $attribute) {
            $name = strtolower($attribute);
            $value = trim(html_entity_decode((string) $element->getAttribute($attribute), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            if (str_starts_with($name, 'on') || $name === 'style' || ! in_array($name, $allowedAttributes, true)) {
                $element->removeAttribute($attribute);
                continue;
            }

            if (in_array($name, ['href', 'src'], true) && ! $this->isSafeHtmlUrl($value, $tag)) {
                $element->removeAttribute($attribute);
                continue;
            }

            if ($name === 'target' && ! in_array($value, ['_blank', '_self'], true)) {
                $element->removeAttribute($attribute);
                continue;
            }

            if (in_array($name, ['width', 'height', 'colspan', 'rowspan'], true) && ! ctype_digit($value)) {
                $element->removeAttribute($attribute);
            }
        }

        if ($tag === 'a' && $element->hasAttribute('href')) {
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private function isSafeHtmlUrl(string $value, string $tag): bool
    {
        if ($value === '' || preg_match('/[\x00-\x1F\x7F]/', $value)) {
            return false;
        }

        if (preg_match('/^([a-z][a-z0-9+.-]*):/i', $value, $matches)) {
            $scheme = strtolower($matches[1]);

            if ($tag === 'img') {
                return in_array($scheme, ['http', 'https'], true);
            }

            return in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
        }

        return str_starts_with($value, '/')
            || str_starts_with($value, '#')
            || str_starts_with($value, './')
            || str_starts_with($value, '../')
            || ! str_contains($value, ':');
    }

    private function nodeInnerHtml(DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument?->saveHTML($child) ?: '';
        }

        return $html;
    }

    private function allowedMimeTypes(array $allowedExtensions): array
    {
        $map = [
            'jpg'  => ['image/jpeg', 'image/pjpeg'],
            'jpeg' => ['image/jpeg', 'image/pjpeg'],
            'png'  => ['image/png'],
            'webp' => ['image/webp'],
            'pdf'  => ['application/pdf', 'application/x-pdf'],
            'doc'  => ['application/msword', 'application/vnd.ms-office', 'application/x-ole-storage'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
            'txt'  => ['text/plain'],
        ];

        $mimeTypes = [];

        foreach ($allowedExtensions as $extension) {
            $mimeTypes = array_merge($mimeTypes, $map[$extension] ?? []);
        }

        return array_values(array_unique($mimeTypes));
    }

    private function containsImageExtension(array $allowedExtensions): bool
    {
        return array_intersect($allowedExtensions, ['jpg', 'jpeg', 'png', 'webp']) !== [];
    }
}
