<?php

namespace App\Controllers;

use App\Models\CashbookModel;

class Cashbook extends BaseController
{
    public function index(): string
    {
        return $this->cashbookPage('Cashbook', ['cashbook' => true]);
    }

    public function cashIn(): string
    {
        return $this->cashbookPage('Cash In', ['cash_in' => true]);
    }

    public function cashOut(): string
    {
        return $this->cashbookPage('Cash Out', ['cash_out' => true]);
    }

    private function cashbookPage(string $title, array $args): string
    {
        $model = model(CashbookModel::class);

        return $this->renderPage($title, 'cashbook/cashbook', [
            'args'     => $args,
            'cashbook' => $model->paginateCashbook(self::PER_PAGE, $args),
            'pager'    => $model->pager,
        ]);
    }
}
