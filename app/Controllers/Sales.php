<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\SalesModel;

/**
 * NOTE: the Sales workflow was already incomplete in the CI3 application:
 * the list reads tbl_sales while the save action wrote clients. Behaviour is
 * preserved here; completing the sale/detail flow is a separate task.
 */
class Sales extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('Sales', 'sale/sales', [
            'all_sales' => model(SalesModel::class)->getSales(),
        ]);
    }

    public function addSale($edit = null): string
    {
        $data = ['edit' => $edit];

        if ($edit !== null) {
            $data['title']     = 'Edit Sale';
            $data['sale_info'] = model(SalesModel::class)->getSales((int) $edit);
        } else {
            $data['title'] = 'Add Sale';
        }

        return $this->renderPage($data['title'], 'sale/add_sale', $data);
    }

    public function saveSale($edit = null)
    {
        $firstName = trim((string) $this->request->getPost('first_name'));

        if ($firstName === '') {
            return $this->response->setBody('invalid');
        }

        $data = [
            'client_type'    => 'client',
            'first_name'     => $firstName,
            'last_name'      => (string) $this->request->getPost('last_name'),
            'client_email'   => '',
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
            $this->logActivity('warning', session()->get('user_name') . ' updated a sale. Which ID -' . $edit, $userId);
        } else {
            $data['create_time'] = date('H:i:s');
            $data['create_date'] = date('Y-m-d');
            $data['created_by']  = $userId;

            $result = $model->insert($data);
            $this->logActivity('success', session()->get('user_name') . ' add a sale. Which ID -' . $model->getInsertID(), $userId);
        }

        return $this->response->setBody($result ? 'success' : 'invalid');
    }
}
