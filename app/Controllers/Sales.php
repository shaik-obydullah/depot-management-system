<?php

namespace App\Controllers;

use App\Libraries\SaleCalculator;
use App\Models\ProductModel;
use App\Models\SalesModel;

/**
 * Sales are recorded in tbl_sales with their line items in sale_details.
 * Stock/cashbook movements remain separate, as in the legacy application.
 */
class Sales extends BaseController
{
    public function index(): string
    {
        $model = model(SalesModel::class);
        $sales = $model->paginateSales(self::PER_PAGE);

        return $this->renderPage('Sales', 'sale/sales', [
            'all_sales' => $sales,
            'pager'     => $model->pager,
        ]);
    }

    public function viewSale($id)
    {
        $saleId = (int) $id;
        $model  = model(SalesModel::class);
        $sale   = $model->getSales($saleId);

        if ($sale === null) {
            return $this->response->setStatusCode(404)->setBody('Sale not found');
        }

        return $this->renderPage('Sale Details', 'sale/sale_details', [
            'sale_info'   => $sale,
            'sale_details' => $model->getDetails($saleId),
        ]);
    }

    public function addSale($edit = null)
    {
        $saleId = $edit !== null ? (int) $edit : null;
        $model  = model(SalesModel::class);
        $data   = [
            'edit'         => $saleId,
            'all_products' => model(ProductModel::class)->getProducts(),
        ];

        if ($saleId !== null) {
            $data['title']        = 'Edit Sale';
            $data['sale_info']    = $model->getSales($saleId);
            $data['sale_details'] = $model->getDetails($saleId);

            if ($data['sale_info'] === null) {
                return $this->response->setStatusCode(404)->setBody('Sale not found');
            }
        } else {
            $data['title'] = 'Add Sale';
        }

        return $this->renderPage($data['title'], 'sale/add_sale', $data);
    }

    public function saveSale($edit = null)
    {
        $itemsInput = $this->request->getPost('items');

        if (! is_array($itemsInput)) {
            return $this->plain('invalid');
        }

        $productModel = model(ProductModel::class);
        $items        = [];

        foreach ($itemsInput as $row) {
            $productId = (int) ($row['product_id'] ?? 0);

            if ($productId <= 0) {
                continue;
            }

            $product = $productModel->getProducts($productId);
            if ($product === null) {
                return $this->plain('invalid');
            }

            $box      = max(0, (int) ($row['box'] ?? 0));
            $quantity = max(0, (int) ($row['qty'] ?? 0));
            $totalQty = ((int) $product->product_unit * $box) + $quantity;

            if ($totalQty <= 0) {
                continue;
            }

            $items[] = [
                'fk_product_id'   => $productId,
                'product_name'    => $product->product_name,
                'product_quantity' => $totalQty,
                'product_weight'  => (string) $product->product_weight,
                'box_unit'        => (int) $product->product_unit,
                'sale_price'      => max(0, (float) ($row['sale_price'] ?? 0)),
            ];
        }

        if ($items === []) {
            return $this->plain('invalid');
        }

        $totals = SaleCalculator::totals(
            array_map(static fn (array $item): array => [
                'quantity' => $item['product_quantity'],
                'price'    => $item['sale_price'],
            ], $items),
            (float) $this->request->getPost('discount')
        );

        $userId = (int) session()->get('user_id');
        $time   = date('H:i:s');
        $date   = date('Y-m-d');
        $saleId = $edit !== null ? (int) $edit : null;

        $sale = [
            'sale_subtotal' => $totals['subtotal'],
            'sale_discount' => $totals['discount'],
            'ground_total'  => $totals['total'],
        ];

        if ($saleId !== null) {
            $sale['update_time'] = $time;
            $sale['update_date'] = $date;
            $sale['updated_by']  = $userId;
        } else {
            $sale['create_time'] = $time;
            $sale['create_date'] = $date;
            $sale['created_by']  = $userId;
        }

        $savedId = model(SalesModel::class)->saveSale($sale, $items, $saleId);
        if ($savedId === false) {
            return $this->plain('invalid');
        }

        $this->logActivity(
            $saleId !== null ? 'warning' : 'success',
            session()->get('user_name') . ($saleId !== null ? ' updated a sale. Which ID -' : ' add a sale. Which ID -') . $savedId,
            $userId
        );

        return $this->plain('success');
    }
}
