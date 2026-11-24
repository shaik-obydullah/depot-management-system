<?php

namespace App\Controllers;

use App\Models\InventoryModel;

class Inventory extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('Inventories', 'inventory/inventories', [
            'all_inventories' => model(InventoryModel::class)->getInventories(),
        ]);
    }
}
