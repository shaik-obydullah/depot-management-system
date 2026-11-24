<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('Products', 'product/products', [
            'all_products' => model(ProductModel::class)->getProducts(),
        ]);
    }

    public function selectProduct($id)
    {
        $product = model(ProductModel::class)->getProducts((int) $id);

        if ($product === null) {
            return $this->response->setStatusCode(404)->setBody('Product not found');
        }

        return view('product/productInfo', ['product_info' => $product]);
    }

    public function addProduct($edit = null): string
    {
        $data = ['edit' => $edit];

        if ($edit !== null) {
            $data['title']       = 'Edit Product';
            $data['product_info'] = model(ProductModel::class)->getProducts((int) $edit);
        } else {
            $data['title'] = 'Add Product';
        }

        return $this->renderPage($data['title'], 'product/add_product', $data);
    }

    public function saveProduct($edit = null)
    {
        $name = trim((string) $this->request->getPost('name'));

        if ($name === '') {
            return $this->response->setBody('invalid');
        }

        $data = [
            'product_name'   => $name,
            'product_weight' => $this->request->getPost('weight'),
            'product_unit'   => $this->request->getPost('unit'),
        ];

        $userId = (int) session()->get('user_id');
        $time   = date('H:i:s');
        $date   = date('Y-m-d');

        if ($edit !== null) {
            $data['update_time'] = $time;
            $data['update_date'] = $date;
            $data['updated_by']  = $userId;

            $result = model(ProductModel::class)->update((int) $edit, $data);
            $this->logActivity('warning', session()->get('user_name') . ' updated a product. Which ID -' . $edit, $userId);
        } else {
            $data['create_time'] = $time;
            $data['create_date'] = $date;
            $data['created_by']  = $userId;

            $result = model(ProductModel::class)->insert($data);
            $this->logActivity('success', session()->get('user_name') . ' add a product. Which ID -' . model(ProductModel::class)->getInsertID(), $userId);
        }

        return $this->response->setBody($result ? 'success' : 'invalid');
    }
}
