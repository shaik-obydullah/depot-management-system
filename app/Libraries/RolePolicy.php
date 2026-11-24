<?php

namespace App\Libraries;

/**
 * Single source of truth for the small permission model used by this app.
 *
 * Daily stock/sales work is available to every authenticated user, while
 * changes to product, branch and dealer master data are superadmin-only.
 */
final class RolePolicy
{
    public const MASTER_DATA_ROLE = 'superadmin';

    public static function canManageMasterData(?string $userType): bool
    {
        return $userType === self::MASTER_DATA_ROLE;
    }
}
