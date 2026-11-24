<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Branch extends BaseController
{
    public function index(): string
    {
        $model = model(ClientModel::class);

        return $this->renderPage('Branches', 'branch/branches', [
            'all_branches' => $model->paginateClients(self::PER_PAGE, 'branch'),
            'pager'        => $model->pager,
        ]);
    }

    public function addBranch($edit = null): string
    {
        $data = ['edit' => $edit];

        if ($edit !== null) {
            $data['title']       = 'Edit Branch';
            $data['branch_info'] = model(ClientModel::class)->getClients((int) $edit);
        } else {
            $data['title'] = 'Add Branch';
        }

        return $this->renderPage($data['title'], 'branch/add_branch', $data);
    }

    public function saveBranch($edit = null)
    {
        $name = trim((string) $this->request->getPost('first_name'));

        if ($name === '') {
            return $this->plain('invalid');
        }

        $data = [
            'client_type'    => 'branch',
            'first_name'     => $name,
            'last_name'      => '',
            'client_email'   => (string) $this->request->getPost('email'),
            'client_mobile'  => (string) $this->request->getPost('mobile'),
            'client_address' => (string) $this->request->getPost('address'),
        ];

        $userId = (int) session()->get('user_id');
        $model  = model(ClientModel::class);

        if ($edit !== null) {
            $data['update_time'] = date('H:i:s');
            $data['update_date'] = date('Y-m-d');
            $data['updated_by']  = $userId;

            $result = $model->update((int) $edit, $data);
            $this->logActivity('warning', session()->get('user_name') . ' updated a branch. Which ID -' . $edit, $userId);
        } else {
            $data['create_time'] = date('H:i:s');
            $data['create_date'] = date('Y-m-d');
            $data['created_by']  = $userId;

            $result = $model->insert($data);
            $this->logActivity('success', session()->get('user_name') . ' add a branch. Which ID -' . $model->getInsertID(), $userId);
        }

        return $this->response->setBody($result ? 'success' : 'invalid');
    }
}
