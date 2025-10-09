<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PermissionManager;

class SyncAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync-admin {--roles= : Comma-separated list of roles to sync (default: Super Admin,Administrator)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all permissions to admin roles. Useful when new modules/permissions are added.';

    protected $permissionManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PermissionManager $permissionManager)
    {
        parent::__construct();
        $this->permissionManager = $permissionManager;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔄 Syncing Admin Permissions...');
        $this->newLine();

        // Get roles from option or use defaults
        $rolesOption = $this->option('roles');
        if ($rolesOption) {
            $roles = array_map('trim', explode(',', $rolesOption));
        } else {
            $roles = ['Super Admin', 'Administrator'];
        }

        $this->info('📋 Roles to sync: ' . implode(', ', $roles));
        $this->newLine();

        // Sync permissions for all roles
        $results = $this->permissionManager->syncAllRolesPermissions($roles);

        // Display results
        foreach ($results as $roleName => $result) {
            if ($result['success']) {
                $this->line("✅ <fg=green>{$roleName}:</fg=green> {$result['message']}");
                if (isset($result['new_permissions_assigned']) && $result['new_permissions_assigned'] > 0) {
                    $this->line("   📈 New permissions assigned: {$result['new_permissions_assigned']}");
                }
            } else {
                $this->line("❌ <fg=red>{$roleName}:</fg=red> {$result['message']}");
            }
        }

        // Ensure Super Admin user exists
        $this->info('👤 Ensuring Super Admin user...');
        $userResult = $this->permissionManager->ensureSuperAdmin();

        if ($userResult['success']) {
            $this->line("✅ <fg=green>Super Admin user:</fg=green> {$userResult['message']}");
        } else {
            $this->line("❌ <fg=red>Super Admin user:</fg=red> {$userResult['message']}");
        }

        $this->newLine();
        $this->info('🎉 Admin permissions sync completed!');
        $this->info('💡 Tip: Run this command whenever you add new modules to ensure admin roles get all permissions.');

        return 0;
    }
}
