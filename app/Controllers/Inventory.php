<?php

namespace App\Controllers;

use App\Models\InventoryModel;

class Inventory extends BaseController
{
    public function index(): string
    {
        $model = model(InventoryModel::class);

        return $this->renderPage('Inventories', 'inventory/inventories', [
            'all_inventories' => $model->paginateInventories(self::PER_PAGE),
            'pager'           => $model->pager,
        ]);
    }
}
