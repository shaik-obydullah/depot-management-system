<?php

use App\Libraries\RolePolicy;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class RolePolicyTest extends CIUnitTestCase
{
    public function testSuperadminCanManageMasterData(): void
    {
        $this->assertTrue(RolePolicy::canManageMasterData('superadmin'));
    }

    public function testAdminCannotManageMasterData(): void
    {
        $this->assertFalse(RolePolicy::canManageMasterData('admin'));
        $this->assertFalse(RolePolicy::canManageMasterData(null));
    }
}
