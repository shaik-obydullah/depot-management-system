<?php

namespace App\Controllers;

use App\Models\CashbookModel;
use App\Models\ClientModel;
use App\Models\InventoryModel;
use App\Models\ProductModel;
use App\Models\StockModel;

class Stock extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('Stocks', 'stock/stocks', [
            'all_stocks' => model(StockModel::class)->getStocks(),
        ]);
    }

    public function addStock($edit = null): string
    {
        $data = [
            'edit'         => $edit,
            'all_products' => model(ProductModel::class)->getProducts(),
            'all_branches' => model(ClientModel::class)->getClients(null, 'branch'),
        ];

        if ($edit !== null) {
            $data['title']      = 'Edit Stock';
            $data['stock_info'] = model(StockModel::class)->getStocks((int) $edit);
        } else {
            $data['title'] = 'Add Stock';
        }

        return $this->renderPage($data['title'], 'stock/add_stock', $data);
    }

    public function saveStock()
    {
        $userId = (int) session()->get('user_id');
        $time   = date('H:i:s');
        $date   = date('Y-m-d');

        $productId = (int) $this->request->getPost('product_id');
        $product   = model(ProductModel::class)->getProducts($productId);

        if ($product === null) {
            return $this->response->setBody('invalid');
        }

        $box       = (int) $this->request->getPost('box');
        $qty       = (int) $this->request->getPost('qty');
        $stockDate = (string) $this->request->getPost('transaction_date');
        $stockDate = $stockDate !== '' ? date('Y-m-d', strtotime($stockDate)) : $date;

        $data = [
            'fk_branch_id'    => (int) $this->request->getPost('branch_id'),
            'fk_product_id'   => $productId,
            'stock_type'      => (string) $this->request->getPost('type'),
            'buying_price'    => (float) $this->request->getPost('buying_price'),
            'selling_price'   => (float) $this->request->getPost('selling_price'),
            'stock_quantity'  => ((int) $product->product_unit * $box) + $qty,
            'transaction_date' => $stockDate,
            'create_time'     => $time,
            'create_date'     => $date,
            'created_by'      => $userId,
        ];

        $db = db_connect();
        $db->transStart();

        $stockId = model(StockModel::class)->insert($data);
        if (! $stockId) {
            $db->transRollback();

            return $this->response->setBody('invalid');
        }

        $this->logActivity(
            'success',
            session()->get('user_name') . ' add a stock. Which ID -' . $stockId,
            $userId
        );

        $cashIn = $data['stock_quantity'] * $data['buying_price'];
        $cashbookId = model(CashbookModel::class)->saveCashbook([
            'fk_branch_id'         => $data['fk_branch_id'],
            'fk_product_id'        => $data['fk_product_id'],
            'fk_stock_id'          => $stockId,
            'transaction_date'     => $stockDate,
            'cashbook_description' => 'Stock In',
            'cash_in'              => $cashIn,
            'create_time'          => $time,
            'create_date'          => $date,
            'created_by'           => $userId,
        ]);

        $this->logActivity(
            'success',
            session()->get('user_name') . ' add some stock and the amount of stock is '
                . $cashIn . ' Cashbook ID -' . $cashbookId,
            $userId
        );

        model(InventoryModel::class)->applyStockIn($data, (int) $stockId, $time, $date, $userId);

        $db->transComplete();

        return $this->response->setBody('success');
    }

    public function editStock($edit): string
    {
        return $this->renderPage('Edit Stock', 'stock/edit_stock', [
            'edit'         => $edit,
            'stock_info'   => model(StockModel::class)->getStocks((int) $edit),
            'all_products' => model(ProductModel::class)->getProducts(),
            'all_branches' => model(ClientModel::class)->getClients(null, 'branch'),
        ]);
    }

    public function updateStock()
    {
        $userId  = (int) session()->get('user_id');
        $stockId = (int) $this->request->getPost('stock_id');

        $productId = (int) $this->request->getPost('product_id');
        $product   = model(ProductModel::class)->getProducts($productId);

        if ($product === null || $stockId === 0) {
            return $this->response->setBody('invalid');
        }

        $time      = date('H:i:s');
        $date      = date('Y-m-d');
        $box       = (int) $this->request->getPost('box');
        $qty       = (int) $this->request->getPost('qty');
        $stockDate = (string) $this->request->getPost('transaction_date');
        $stockDate = $stockDate !== '' ? date('Y-m-d', strtotime($stockDate)) : $date;

        $data = [
            'fk_branch_id'     => (int) $this->request->getPost('branch_id'),
            'fk_product_id'    => $productId,
            'stock_type'       => (string) $this->request->getPost('type'),
            'buying_price'     => (float) $this->request->getPost('buying_price'),
            'selling_price'    => (float) $this->request->getPost('selling_price'),
            'stock_quantity'   => ((int) $product->product_unit * $box) + $qty,
            'transaction_date' => $stockDate,
            'update_time'      => $time,
            'update_date'      => $date,
            'updated_by'       => $userId,
        ];

        $db = db_connect();
        $db->transStart();

        $result = model(StockModel::class)->update($stockId, $data);
        if (! $result) {
            $db->transRollback();

            return $this->response->setBody('invalid');
        }

        $this->logActivity(
            'success',
            session()->get('user_name') . ' update a stock. Which ID -' . $stockId,
            $userId
        );

        $cashIn = $data['stock_quantity'] * $data['buying_price'];
        $db->table('tbl_cashbook')->where('fk_stock_id', $stockId)->update([
            'fk_branch_id'         => $data['fk_branch_id'],
            'fk_product_id'        => $data['fk_product_id'],
            'fk_stock_id'          => $stockId,
            'transaction_date'     => $stockDate,
            'cashbook_description' => 'Stock In',
            'cash_in'              => $cashIn,
            'update_time'          => $time,
            'update_date'          => $date,
            'updated_by'           => $userId,
        ]);

        $this->logActivity(
            'success',
            session()->get('user_name') . ' update some stock and the amount of stock is '
                . $cashIn . ' Stock ID -' . $stockId,
            $userId
        );

        model(InventoryModel::class)->replaceForStock($data, $stockId, $time, $date, $userId);

        $db->transComplete();

        return $this->response->setBody('success');
    }
}
