<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table      = 'tbl_clients';
    protected $primaryKey = 'pk_client_id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'client_type',
        'first_name',
        'last_name',
        'client_email',
        'client_mobile',
        'client_address',
        'create_time',
        'create_date',
        'created_by',
        'update_time',
        'update_date',
        'updated_by',
    ];

    /**
     * Clients are branches, dealers (and legacy sales) in one table.
     * Pass $type to scope a list to a single kind of client.
     */
    public function getClients($id = null, ?string $type = null)
    {
        $builder = $this->builder();

        if ($type !== null) {
            $builder->where('client_type', $type);
        }

        if ($id !== null) {
            return $builder->where($this->primaryKey, $id)->get()->getRow();
        }

        return $builder->orderBy($this->primaryKey, 'DESC')->get()->getResult();
    }

    public function paginateClients(int $perPage, ?string $type = null): array
    {
        $builder = $this->builder();

        if ($type !== null) {
            $builder->where('client_type', $type);
        }

        $builder->orderBy($this->primaryKey, 'DESC');

        return $this->paginate($perPage);
    }
}
