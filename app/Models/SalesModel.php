<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table      = 'tbl_sales';
    protected $primaryKey = 'pk_sale_id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'sale_subtotal',
        'sale_discount',
        'ground_total',
        'create_time',
        'create_date',
        'created_by',
        'update_time',
        'update_date',
        'updated_by',
    ];

    public function getSales($id = null)
    {
        if ($id !== null) {
            return $this->where($this->primaryKey, $id)->get()->getRow();
        }

        return $this->orderBy($this->primaryKey, 'DESC')->get()->getResult();
    }

    public function paginateSales(int $perPage): array
    {
        return $this->orderBy($this->primaryKey, 'DESC')->paginate($perPage);
    }

    /**
     * Line items for one sale, with the product snapshot stored at sale time.
     */
    public function getDetails(int $saleId): array
    {
        return $this->db->table('sale_details')
            ->select('sale_details.*, tbl_products.product_name AS current_product_name')
            ->join('tbl_products', 'tbl_products.pk_product_id = sale_details.fk_product_id', 'left')
            ->where('sale_details.fk_sale_id', $saleId)
            ->orderBy('sale_details.sale_details_id', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Insert or replace a sale with its line items in one transaction.
     *
     * @param list<array<string, mixed>> $items
     *
     * @return int|false The sale ID, or false when the transaction fails.
     */
    public function saveSale(array $sale, array $items, ?int $saleId = null)
    {
        $db = $this->db;
        $db->transStart();

        if ($saleId !== null) {
            if (! $this->update($saleId, $sale)) {
                $db->transRollback();

                return false;
            }

            $db->table('sale_details')->where('fk_sale_id', $saleId)->delete();
        } else {
            $saleId = (int) $this->insert($sale);
        }

        if ($saleId <= 0) {
            $db->transRollback();

            return false;
        }

        foreach ($items as $item) {
            $db->table('sale_details')->insert(array_merge(
                ['fk_sale_id' => $saleId],
                $item
            ));
        }

        $db->transComplete();

        return $db->transStatus() ? $saleId : false;
    }
}
