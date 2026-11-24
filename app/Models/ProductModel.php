<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'tbl_products';
    protected $primaryKey = 'pk_product_id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'product_name',
        'product_weight',
        'product_unit',
        'create_time',
        'create_date',
        'created_by',
        'update_time',
        'update_date',
        'updated_by',
    ];

    public function getProducts($id = null)
    {
        if ($id !== null) {
            return $this->where($this->primaryKey, $id)->get()->getRow();
        }

        return $this->orderBy($this->primaryKey, 'DESC')->get()->getResult();
    }
}
