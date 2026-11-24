<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table      = 'tbl_inventories';
    protected $primaryKey = 'pk_inventory_id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'fk_product_id',
        'fk_stock_id',
        'fk_branch_id',
        'regular_quantity',
        'damage_quantity',
        'free_quantity',
        'buying_price',
        'selling_price',
        'create_time',
        'create_date',
        'created_by',
        'update_time',
        'update_date',
        'updated_by',
    ];

    private const QUANTITY_FIELDS = [
        'regular' => 'regular_quantity',
        'damage'  => 'damage_quantity',
        'free'    => 'free_quantity',
    ];

    public function getInventories($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tbl_inventories.*, tbl_products.product_name, '
            . 'tbl_products.product_unit, tbl_products.product_weight');
        $builder->join('tbl_products', 'tbl_products.pk_product_id = tbl_inventories.fk_product_id', 'left');

        if ($id !== null) {
            return $builder->where('tbl_inventories.fk_stock_id', $id)->get()->getRow();
        }

        return $builder->orderBy('tbl_inventories.pk_inventory_id', 'DESC')->get()->getResult();
    }

    public function paginateInventories(int $perPage): array
    {
        $builder = $this->builder();
        $builder->select('tbl_inventories.*, tbl_products.product_name, '
            . 'tbl_products.product_unit, tbl_products.product_weight');
        $builder->join('tbl_products', 'tbl_products.pk_product_id = tbl_inventories.fk_product_id', 'left');
        $builder->orderBy('tbl_inventories.pk_inventory_id', 'DESC');

        return $this->paginate($perPage);
    }

    public function saveInventory(array $data): int
    {
        $this->db->table($this->table)->insert($data);

        return $this->db->insertID();
    }

    /**
     * Add an incoming stock entry to the matching inventory row.
     * The lookup now includes the branch, matching the WHERE clause of
     * the update (the CI3 version omitted it and could silently lose stock).
     */
    public function applyStockIn(array $stock, int $stockId, string $time, string $date, int $userId): void
    {
        $field = self::QUANTITY_FIELDS[$stock['stock_type']] ?? 'regular_quantity';
        $qty   = (int) $stock['stock_quantity'];

        $existing = $this->db->table($this->table)
            ->where('fk_product_id', $stock['fk_product_id'])
            ->where('fk_branch_id', $stock['fk_branch_id'])
            ->where('buying_price', $stock['buying_price'])
            ->get()
            ->getRow();

        if ($existing !== null) {
            $this->db->table($this->table)
                ->set($field, $field . ' + ' . $qty, false)
                ->set([
                    'buying_price'  => $stock['buying_price'],
                    'selling_price' => $stock['selling_price'],
                    'update_time'   => $time,
                    'update_date'   => $date,
                    'updated_by'    => $userId,
                ])
                ->where('pk_inventory_id', $existing->pk_inventory_id)
                ->update();

            return;
        }

        $inventory = [
            'fk_product_id'  => $stock['fk_product_id'],
            'fk_stock_id'    => $stockId,
            'fk_branch_id'   => $stock['fk_branch_id'],
            'buying_price'   => $stock['buying_price'],
            'selling_price'  => $stock['selling_price'],
            $field           => $qty,
            'create_time'    => $time,
            'create_date'    => $date,
            'created_by'     => $userId,
        ];

        $this->saveInventory($inventory);
    }

    /**
     * Replace the inventory figures belonging to one stock entry after an edit.
     */
    public function replaceForStock(array $stock, int $stockId, string $time, string $date, int $userId): void
    {
        $field = self::QUANTITY_FIELDS[$stock['stock_type']] ?? 'regular_quantity';
        $qty   = (int) $stock['stock_quantity'];

        $existing = $this->db->table($this->table)
            ->where('fk_stock_id', $stockId)
            ->get()
            ->getRow();

        if ($existing !== null) {
            $this->db->table($this->table)
                ->set([
                    $field          => $qty,
                    'buying_price'  => $stock['buying_price'],
                    'selling_price' => $stock['selling_price'],
                    'update_time'   => $time,
                    'update_date'   => $date,
                    'updated_by'    => $userId,
                ])
                ->where('pk_inventory_id', $existing->pk_inventory_id)
                ->update();

            return;
        }

        $this->saveInventory([
            'fk_product_id' => $stock['fk_product_id'],
            'fk_stock_id'   => $stockId,
            'fk_branch_id'  => $stock['fk_branch_id'],
            'buying_price'  => $stock['buying_price'],
            'selling_price' => $stock['selling_price'],
            $field          => $qty,
            'create_time'   => $time,
            'create_date'   => $date,
            'created_by'    => $userId,
        ]);
    }
}
