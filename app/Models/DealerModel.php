<?php

namespace App\Models;

class DealerModel extends ClientModel
{
    public function getDealers($id = null)
    {
        return $this->getClients($id, 'dealer');
    }

    public function paginateDealers(int $perPage): array
    {
        return $this->paginateClients($perPage, 'dealer');
    }
}
