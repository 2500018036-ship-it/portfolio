<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;
use Throwable;

class Contact extends BaseController
{
    public function submit()
    {
        if ($this->request->getPost('website')) {
            return redirect()->to(site_url('/#contact'))->with('contact_error', 'Pesan tidak dapat diproses.');
        }

        if ($this->isRateLimited()) {
            return redirect()->to(site_url('/#contact'))->withInput()->with('contact_error', 'Terlalu banyak pesan. Coba lagi beberapa saat lagi.');
        }

        $rules = [
            'name'    => 'required|max_length[120]',
            'email'   => 'required|valid_email|max_length[120]',
            'subject' => 'permit_empty|max_length[180]',
            'message' => 'required|min_length[10]|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('/#contact'))->withInput()->with('contact_errors', $this->validator->getErrors());
        }

        $data = [
            'name'       => $this->cleanText($this->request->getPost('name'), 120),
            'email'      => strtolower($this->cleanText($this->request->getPost('email'), 120)),
            'subject'    => $this->cleanText($this->request->getPost('subject'), 180),
            'message'    => $this->cleanText($this->request->getPost('message'), 5000),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => substr((string) $this->request->getUserAgent(), 0, 255),
            'status'     => 'unread',
        ];

        (new ContactMessageModel())->skipValidation(true)->insert($data);
        session()->set('contact_last_submit_at', time());
        $this->sendNotification($data);

        return redirect()->to(site_url('/#contact'))->with('contact_success', 'Pesan berhasil dikirim.');
    }

    private function isRateLimited(): bool
    {
        $lastSubmit = (int) session('contact_last_submit_at');

        if ($lastSubmit > 0 && time() - $lastSubmit < 30) {
            return true;
        }

        $recentCount = (new ContactMessageModel())
            ->where('ip_address', $this->request->getIPAddress())
            ->where('created_at >=', date('Y-m-d H:i:s', time() - 600))
            ->countAllResults();

        return $recentCount >= 5;
    }

    private function cleanText($value, int $maxLength): string
    {
        $value = strip_tags((string) $value);
        $value = preg_replace('/[^\P{C}\r\n\t]+/u', '', $value) ?? '';
        $value = trim(preg_replace('/[ \t]+/', ' ', $value) ?? '');

        return function_exists('mb_substr') ? mb_substr($value, 0, $maxLength) : substr($value, 0, $maxLength);
    }

    private function sendNotification(array $message): void
    {
        $recipient = env('CONTACT_NOTIFICATION_EMAIL')
            ?: env('email.recipients')
            ?: env('email.fromEmail')
            ?: $this->publicSetting('contact_email')
            ?: '';

        if (! $recipient) {
            return;
        }

        try {
            $email = service('email');
            $from = env('CONTACT_FROM_EMAIL') ?: env('email.fromEmail') ?: $message['email'];
            $fromName = env('CONTACT_FROM_NAME') ?: env('email.fromName') ?: 'Portfolio Contact';
            $email->setFrom($from, $fromName);
            $email->setTo($recipient);
            $email->setReplyTo($message['email'], $message['name']);
            $email->setSubject('Portfolio Contact: ' . ($message['subject'] ?: 'New message'));
            $email->setMessage(
                "Name: {$message['name']}\n"
                . "Email: {$message['email']}\n"
                . "Subject: {$message['subject']}\n\n"
                . $message['message']
            );
            $email->send(false);
        } catch (Throwable $e) {
            log_message('warning', 'Contact email notification failed: ' . $e->getMessage());
        }
    }
}
