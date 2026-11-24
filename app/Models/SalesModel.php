<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table      = 'tbl_sales';
    protected $primaryKey = 'pk_sale_id';
    protected $returnType = 'object';

    public function getSales($id = null)
    {
        if ($id !== null) {
            return $this->where($this->primaryKey, $id)->get()->getRow();
        }

        return $this->orderBy($this->primaryKey, 'DESC')->get()->getResult();
    }
}
