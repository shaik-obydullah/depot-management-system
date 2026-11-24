<?php

namespace App\Models;

use CodeIgniter\Model;

class CashbookModel extends Model
{
    protected $table      = 'tbl_cashbook';
    protected $primaryKey = 'pk_cashbook_id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'fk_branch_id',
        'fk_product_id',
        'fk_stock_id',
        'fk_inventory_id',
        'transaction_date',
        'cashbook_description',
        'cash_in',
        'cash_out',
        'create_time',
        'create_date',
        'created_by',
        'update_time',
        'update_date',
        'updated_by',
    ];

    public function getCashbook(?array $args = null)
    {
        $builder = $this->db->table($this->table);

        if (isset($args['cash_in'])) {
            $builder->where('cash_in IS NOT NULL', null, false);
        } elseif (isset($args['cash_out'])) {
            $builder->where('cash_out IS NOT NULL', null, false);
        } else {
            $builder->orderBy($this->primaryKey, 'DESC');
        }

        return $builder->get()->getResult();
    }

    public function saveCashbook(array $data): int
    {
        $this->db->table($this->table)->insert($data);

        return $this->db->insertID();
    }
}
