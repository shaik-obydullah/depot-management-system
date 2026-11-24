<?php

use App\Libraries\RolePolicy;

if (! function_exists('is_superadmin')) {
    /**
     * Convenience check for views: master-data links are only shown to
     * superadmins. Route filters still enforce the permission server-side.
     */
    function is_superadmin(): bool
    {
        return RolePolicy::canManageMasterData(session()->get('user_type'));
    }
}
