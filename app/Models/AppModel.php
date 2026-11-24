<?php

namespace App\Models;

use CodeIgniter\Model;

class AppModel extends Model
{
    protected $table         = 'tbl_users';
    protected $primaryKey    = 'pk_user_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'user_name',
        'user_password',
        'first_name',
        'last_name',
        'user_email',
        'user_type',
        'user_status',
    ];

    /**
     * Verify a login. Legacy plaintext passwords are accepted once and
     * transparently re-hashed so existing installations keep working.
     */
    public function userLoginCheck(string $userName, string $password)
    {
        $user = $this->db->table($this->table)
            ->where('user_name', $userName)
            ->where('user_status', 'active')
            ->get()
            ->getRow();

        if ($user === null) {
            return null;
        }

        $stored = (string) $user->user_password;

        if (password_verify($password, $stored)) {
            return $user;
        }

        if (hash_equals($stored, $password)) {
            $this->update($user->pk_user_id, [
                'user_password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            return $user;
        }

        return null;
    }

    /**
     * Dashboard totals from the cashbook.
     */
    public function getStatics(): array
    {
        $cashIn = $this->db->table('tbl_cashbook')
            ->selectSum('cash_in')
            ->get()
            ->getRow()
            ->cash_in;

        $cashOut = $this->db->table('tbl_cashbook')
            ->selectSum('cash_out')
            ->get()
            ->getRow()
            ->cash_out;

        return [
            'cash_in'  => (float) ($cashIn ?? 0),
            'cash_out' => (float) ($cashOut ?? 0),
        ];
    }

    public function saveActivity(array $data): bool
    {
        return $this->db->table('tbl_activities')->insert([
            'activity_type' => $data['activity_type'],
            'fk_user_id'    => $data['fk_user_id'] ?? null,
            'activity_name' => $data['activity_name'],
            'create_time'   => date('H:i:s'),
            'create_date'   => date('Y-m-d'),
            'created_by'    => $data['created_by'] ?? null,
        ]);
    }
}
