<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Permissions ---
        $permissionGroups = [
            'members' => ['view', 'create', 'edit', 'approve', 'export'],
            'finance' => ['view', 'create', 'approve', 'export'],
            'trade' => ['view', 'create', 'edit', 'export'],
            'policy' => ['view', 'create', 'publish'],
            'events' => ['view', 'create', 'edit', 'manage-attendance'],
            'documents' => ['view', 'upload', 'approve', 'delete'],
            'governance' => ['view', 'upload', 'vote'],
            'kpi' => ['view', 'configure'],
            'users' => ['view', 'create', 'assign-role'],
            'settings' => ['view', 'edit'],
            'audit' => ['view'],
            'corridors' => ['view', 'create', 'edit'],
            'chatbot' => ['view', 'configure'],
            'cms' => ['view', 'edit', 'publish'],
        ];

        foreach ($permissionGroups as $group => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$group}.{$action}"]);
            }
        }

        // --- Roles (ordered by authority) ---
        $roles = [
            'super-admin' => [], // gets all via gate-before

            'global-governing-council' => [
                'members.view', 'members.export',
                'finance.view', 'finance.export',
                'trade.view', 'trade.export',
                'policy.view', 'policy.publish',
                'events.view',
                'documents.view', 'documents.approve',
                'governance.view', 'governance.upload', 'governance.vote',
                'kpi.view', 'kpi.configure',
                'users.view',
                'audit.view',
                'corridors.view',
            ],

            'advisory-council' => [
                'members.view',
                'trade.view',
                'policy.view',
                'governance.view', 'governance.vote',
                'kpi.view',
                'documents.view',
            ],

            'risk-audit-unit' => [
                'audit.view',
                'finance.view', 'finance.export',
                'members.view', 'members.export',
                'documents.view',
                'governance.view',
            ],

            'global-secretariat' => [
                'members.view', 'members.create', 'members.edit', 'members.export',
                'finance.view', 'finance.create', 'finance.export',
                'trade.view', 'trade.create', 'trade.edit', 'trade.export',
                'policy.view', 'policy.create', 'policy.publish',
                'events.view', 'events.create', 'events.edit',
                'documents.view', 'documents.upload', 'documents.approve',
                'governance.view', 'governance.upload',
                'kpi.view', 'kpi.configure',
                'users.view', 'users.create', 'users.assign-role',
                'settings.view', 'settings.edit',
                'audit.view',
                'corridors.view', 'corridors.create', 'corridors.edit',
                'chatbot.view', 'chatbot.configure',
                'cms.view', 'cms.edit',
            ],

            'chapter-president' => [
                'members.view', 'members.approve', 'members.export',
                'finance.view', 'finance.approve',
                'trade.view', 'trade.export',
                'policy.view', 'policy.publish',
                'events.view', 'events.create', 'events.edit',
                'documents.view', 'documents.approve',
                'governance.view', 'governance.upload', 'governance.vote',
                'kpi.view',
                'users.view',
                'audit.view',
                'corridors.view',
                'cms.view', 'cms.edit', 'cms.publish',
            ],

            'director-general' => [
                'members.view', 'members.create', 'members.edit', 'members.approve', 'members.export',
                'finance.view', 'finance.create', 'finance.approve', 'finance.export',
                'trade.view', 'trade.create', 'trade.edit', 'trade.export',
                'policy.view', 'policy.create', 'policy.publish',
                'events.view', 'events.create', 'events.edit',
                'documents.view', 'documents.upload', 'documents.approve',
                'governance.view', 'governance.upload', 'governance.vote',
                'kpi.view', 'kpi.configure',
                'users.view', 'users.create',
                'audit.view',
                'corridors.view', 'corridors.create', 'corridors.edit',
                'cms.view', 'cms.edit', 'cms.publish',
            ],

            'pillar-head' => [
                'members.view', 'members.export',
                'trade.view', 'trade.create', 'trade.edit',
                'policy.view', 'policy.create',
                'events.view', 'events.create',
                'documents.view', 'documents.upload',
                'governance.view',
                'kpi.view',
                'corridors.view', 'corridors.edit',
            ],

            'finance-officer' => [
                'finance.view', 'finance.create', 'finance.approve', 'finance.export',
                'members.view',
                'audit.view',
            ],

            'membership-officer' => [
                'members.view', 'members.create', 'members.edit', 'members.approve', 'members.export',
                'finance.view',
            ],

            'trade-bureau-officer' => [
                'trade.view', 'trade.create', 'trade.edit', 'trade.export',
                'corridors.view', 'corridors.create', 'corridors.edit',
                'members.view',
            ],

            'policy-analyst' => [
                'policy.view', 'policy.create', 'policy.publish',
                'documents.view', 'documents.upload',
                'trade.view',
            ],

            'admin-officer' => [
                'members.view',
                'events.view', 'events.create', 'events.edit', 'events.manage-attendance',
                'documents.view', 'documents.upload',
                'governance.view',
                'cms.view', 'cms.edit',
            ],

            'corridor-lead' => [
                'corridors.view', 'corridors.create', 'corridors.edit',
                'trade.view',
                'members.view',
            ],

            'sme-platform-manager' => [
                'members.view', 'members.edit',
                'trade.view', 'trade.create',
            ],

            'anchor-investor-coordinator' => [
                'trade.view', 'trade.create', 'trade.edit',
                'documents.view', 'documents.upload',
                'members.view',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (! empty($permissions)) {
                $role->syncPermissions($permissions);
            }
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
