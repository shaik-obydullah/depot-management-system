<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table      = 'tbl_stocks';
    protected $primaryKey = 'pk_stock_id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'fk_branch_id',
        'fk_product_id',
        'stock_type',
        'buying_price',
        'selling_price',
        'stock_quantity',
        'transaction_date',
        'create_time',
        'create_date',
        'created_by',
        'update_time',
        'update_date',
        'updated_by',
    ];

    public function getStocks($id = null)
    {
        $builder = $this->builder();
        $builder->select('tbl_stocks.*, tbl_clients.first_name, tbl_clients.last_name, '
            . 'tbl_products.pk_product_id, tbl_products.product_name, '
            . 'tbl_products.product_unit, tbl_products.product_weight');
        $builder->join('tbl_clients', 'tbl_clients.pk_client_id = tbl_stocks.fk_branch_id', 'left');
        $builder->join('tbl_products', 'tbl_products.pk_product_id = tbl_stocks.fk_product_id', 'left');

        if ($id !== null) {
            return $builder->where('tbl_stocks.pk_stock_id', $id)->get()->getRow();
        }

        return $builder->orderBy('tbl_stocks.pk_stock_id', 'DESC')->get()->getResult();
    }

    public function paginateStocks(int $perPage): array
    {
        $builder = $this->builder();
        $builder->select('tbl_stocks.*, tbl_clients.first_name, tbl_clients.last_name, '
            . 'tbl_products.pk_product_id, tbl_products.product_name, '
            . 'tbl_products.product_unit, tbl_products.product_weight');
        $builder->join('tbl_clients', 'tbl_clients.pk_client_id = tbl_stocks.fk_branch_id', 'left');
        $builder->join('tbl_products', 'tbl_products.pk_product_id = tbl_stocks.fk_product_id', 'left');

        $builder->orderBy('tbl_stocks.pk_stock_id', 'DESC');

        return $this->paginate($perPage);
    }
}
