<?php

namespace App\Controllers\Admin;

use App\Models\ContactMessageModel;

class MessageController extends AdminController
{
    public function index()
    {
        $status = trim((string) $this->request->getGet('status'));
        $model = new ContactMessageModel();

        if (in_array($status, ['unread', 'read', 'archived'], true)) {
            $model->where('status', $status);
        }

        return view('admin/messages/index', [
            'title'      => 'Messages',
            'breadcrumb' => ['Messages'],
            'status'     => $status,
            'messages'   => $model->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $model = new ContactMessageModel();
        $message = $model->find($id);

        if (! $message) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Message tidak ditemukan.');
        }

        if ($message['status'] === 'unread') {
            $model->skipValidation(true)->update($id, [
                'status'  => 'read',
                'read_at' => date('Y-m-d H:i:s'),
            ]);
            $message = $model->find($id);
        }

        return view('admin/messages/show', [
            'title'      => 'Message Detail',
            'breadcrumb' => ['Messages', 'Detail'],
            'message'    => $message,
        ]);
    }

    public function markRead(int $id)
    {
        (new ContactMessageModel())->skipValidation(true)->update($id, [
            'status'  => 'read',
            'read_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/messages'))->with('success', 'Message ditandai read.');
    }

    public function markUnread(int $id)
    {
        (new ContactMessageModel())->skipValidation(true)->update($id, [
            'status'  => 'unread',
            'read_at' => null,
        ]);

        return redirect()->to(site_url('admin/messages'))->with('success', 'Message ditandai unread.');
    }

    public function archive(int $id)
    {
        (new ContactMessageModel())->skipValidation(true)->update($id, [
            'status'      => 'archived',
            'archived_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/messages'))->with('success', 'Message berhasil diarsipkan.');
    }

    public function delete(int $id)
    {
        (new ContactMessageModel())->delete($id);

        return redirect()->to(site_url('admin/messages'))->with('success', 'Message berhasil dihapus.');
    }
}
