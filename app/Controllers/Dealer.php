<?php

namespace App\Controllers;

use App\Models\DealerModel;

class Dealer extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('Dealers', 'dealer/dealers', [
            'all_dealers' => model(DealerModel::class)->getDealers(),
        ]);
    }

    public function addDealer($edit = null): string
    {
        $data = ['edit' => $edit];

        if ($edit !== null) {
            $data['title']       = 'Edit Dealer';
            $data['dealer_info'] = model(DealerModel::class)->getDealers((int) $edit);
        } else {
            $data['title'] = 'Add Dealer';
        }

        return $this->renderPage($data['title'], 'dealer/add_dealer', $data);
    }

    public function saveDealer($edit = null)
    {
        $firstName = trim((string) $this->request->getPost('first_name'));

        if ($firstName === '') {
            return $this->response->setBody('invalid');
        }

        $data = [
            'client_type'    => 'dealer',
            'first_name'     => $firstName,
            'last_name'      => (string) $this->request->getPost('last_name'),
            'client_email'   => '',
            'client_mobile'  => (string) $this->request->getPost('mobile'),
            'client_address' => (string) $this->request->getPost('address'),
        ];

        $userId = (int) session()->get('user_id');
        $model  = model(DealerModel::class);

        if ($edit !== null) {
            $data['update_time'] = date('H:i:s');
            $data['update_date'] = date('Y-m-d');
            $data['updated_by']  = $userId;

            $result = $model->update((int) $edit, $data);
            $this->logActivity('warning', session()->get('user_name') . ' updated a dealer. Which ID -' . $edit, $userId);
        } else {
            $data['create_time'] = date('H:i:s');
            $data['create_date'] = date('Y-m-d');
            $data['created_by']  = $userId;

            $result = $model->insert($data);
            $this->logActivity('success', session()->get('user_name') . ' add a dealer. Which ID -' . $model->getInsertID(), $userId);
        }

        return $this->response->setBody($result ? 'success' : 'invalid');
    }
}
