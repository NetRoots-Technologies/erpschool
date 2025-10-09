<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PermissionManager
{
    /**
     * Assign all permissions to specified roles
     *
     * @param array $roleNames
     * @return array
     */
    public function assignAllPermissionsToRoles($roleNames = ['Super Admin'])
    {
        $results = [];

        foreach ($roleNames as $roleName) {
            $result = $this->assignAllPermissionsToRole($roleName);
            $results[$roleName] = $result;
        }

        return $results;
    }

    /**
     * Assign all permissions to a specific role
     *
     * @param string $roleName
     * @return array
     */
    public function assignAllPermissionsToRole($roleName)
    {
        try {
            // Find or create the role
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Get all permissions from the database
            $allPermissions = Permission::all();

            if ($allPermissions->isEmpty()) {
                Log::warning("No permissions found in database for role: {$roleName}");
                return [
                    'success' => false,
                    'message' => 'No permissions found in database',
                    'role' => $roleName,
                    'permissions_count' => 0
                ];
            }

            // Get existing permissions for the role
            $existingPermissions = $role->permissions()->pluck('id')->toArray();

            // Get all permission IDs
            $allPermissionIds = $allPermissions->pluck('id')->toArray();

            // Check if there are new permissions that need to be assigned
            $newPermissions = array_diff($allPermissionIds, $existingPermissions);

            if (count($newPermissions) > 0) {
                // Assign all permissions to the role
                $role->syncPermissions($allPermissionIds);

                Log::info("Assigned {$newPermissions} new permissions to role: {$roleName}");

                return [
                    'success' => true,
                    'message' => "Successfully assigned all " . count($allPermissionIds) . " permissions",
                    'role' => $roleName,
                    'permissions_count' => count($allPermissionIds),
                    'new_permissions_assigned' => count($newPermissions)
                ];
            } else {
                return [
                    'success' => true,
                    'message' => "Role already has all " . count($allPermissionIds) . " permissions",
                    'role' => $roleName,
                    'permissions_count' => count($allPermissionIds),
                    'new_permissions_assigned' => 0
                ];
            }

        } catch (\Exception $e) {
            Log::error("Error assigning permissions to role {$roleName}: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'role' => $roleName,
                'permissions_count' => 0
            ];
        }
    }

    /**
     * Ensure Super Admin user exists and has all permissions
     *
     * @param array $userData
     * @return array
     */
    public function ensureSuperAdmin($userData = [])
    {
        try {
            $defaultData = [
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => bcrypt('12345678')
            ];

            $userData = array_merge($defaultData, $userData);

            // Find or create the user
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Ensure Super Admin role exists and has all permissions
            $this->assignAllPermissionsToRole('Super Admin');

            // Assign Super Admin role to user
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            if ($superAdminRole) {
                $user->assignRole($superAdminRole);
            }

            Log::info("Super Admin user ensured: " . $user->email);

            return [
                'success' => true,
                'message' => 'Super Admin user created/updated successfully',
                'user' => $user
            ];

        } catch (\Exception $e) {
            Log::error("Error creating Super Admin user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all permissions count
     *
     * @return int
     */
    public function getTotalPermissionsCount()
    {
        return Permission::count();
    }

    /**
     * Get role permissions summary
     *
     * @param string $roleName
     * @return array
     */
    public function getRolePermissionsSummary($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return [
                'role_exists' => false,
                'message' => "Role '{$roleName}' not found"
            ];
        }

        $totalPermissions = Permission::count();
        $rolePermissions = $role->permissions()->count();

        return [
            'role_exists' => true,
            'role_name' => $roleName,
            'total_permissions' => $totalPermissions,
            'role_permissions' => $rolePermissions,
            'has_all_permissions' => $totalPermissions === $rolePermissions,
            'missing_permissions' => $totalPermissions - $rolePermissions
        ];
    }

    /**
     * Sync permissions for all specified roles (useful when new permissions are added)
     *
     * @param array $roleNames
     * @return array
     */
    public function syncAllRolesPermissions($roleNames = ['Super Admin', 'Administrator'])
    {
        $results = [];

        foreach ($roleNames as $roleName) {
            $result = $this->assignAllPermissionsToRole($roleName);
            $results[$roleName] = $result;

            // Also ensure Super Admin role gets all permissions
            if ($roleName !== 'Super Admin') {
                $this->assignAllPermissionsToRole('Super Admin');
            }
        }

        return $results;
    }

    /**
     * Static method to sync admin permissions from anywhere in the application
     * Useful when new modules/permissions are added dynamically
     *
     * @param array $roleNames
     * @return array
     */
    public static function syncAdminPermissions($roleNames = ['Super Admin', 'Administrator'])
    {
        $instance = app(self::class);
        return $instance->syncAllRolesPermissions($roleNames);
    }

    /**
     * Check if a role has all current permissions and sync if needed
     *
     * @param string $roleName
     * @return bool
     */
    public function ensureRoleHasAllPermissions($roleName)
    {
        $summary = $this->getRolePermissionsSummary($roleName);

        if (!$summary['role_exists'] || !$summary['has_all_permissions']) {
            $result = $this->assignAllPermissionsToRole($roleName);
            return $result['success'];
        }

        return true;
    }
}
