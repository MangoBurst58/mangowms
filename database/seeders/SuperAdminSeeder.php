<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Warehouse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. CREATE ROLES
        // ============================================
        
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create permissions
        $permissions = [
            // Products
            'view products', 'create products', 'edit products', 'delete products',
            // Batches
            'view batches', 'create batches', 'edit batches', 'delete batches',
            // Transactions
            'view transactions', 'create transactions', 'edit transactions', 'delete transactions',
            'approve transactions',
            // Reports
            'view reports', 'export reports',
            // Users
            'view users', 'create users', 'edit users', 'delete users',
            // Settings
            'manage settings', 'view audit logs',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $director = Role::firstOrCreate(['name' => 'director']);
        $warehouseManager = Role::firstOrCreate(['name' => 'warehouse_manager']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $auditor = Role::firstOrCreate(['name' => 'auditor']);
        
        // Assign permissions to roles
        $superAdmin->syncPermissions(Permission::all());
        
        $director->syncPermissions([
            'view products', 'view batches', 'view transactions', 'approve transactions',
            'view reports', 'export reports', 'view users', 'view audit logs'
        ]);
        
        $warehouseManager->syncPermissions([
            'view products', 'create products', 'edit products',
            'view batches', 'create batches', 'edit batches',
            'view transactions', 'create transactions', 'approve transactions',
            'view reports', 'export reports'
        ]);
        
        $supervisor->syncPermissions([
            'view products', 'view batches',
            'view transactions', 'create transactions',
            'view reports'
        ]);
        
        $staff->syncPermissions([
            'view products', 'view batches',
            'view transactions', 'create transactions'
        ]);
        
        $auditor->syncPermissions([
            'view products', 'view batches', 'view transactions',
            'view reports', 'export reports', 'view audit logs'
        ]);
        
        // ============================================
        // 2. CREATE SUPER ADMIN USER
        // ============================================
        
        // Get first company and warehouse
        $company = Company::first();
        $warehouse = Warehouse::first();
        
        // Create Super Admin user
        $superAdminUser = User::updateOrCreate(
            ['email' => 'superadmin@mangowms.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123456'),
                'company_id' => $company ? $company->id : null,
                'warehouse_id' => $warehouse ? $warehouse->id : null,
                'position' => 'System Administrator',
                'phone' => '021-12345678',
                'is_active' => true,
            ]
        );
        
        $superAdminUser->assignRole('super_admin');
        
        // ============================================
        // 3. CREATE SAMPLE USERS (Optional)
        // ============================================
        
        // Warehouse Manager
        $manager = User::updateOrCreate(
            ['email' => 'manager@mangowms.com'],
            [
                'name' => 'Warehouse Manager',
                'password' => Hash::make('Manager@123456'),
                'company_id' => $company ? $company->id : null,
                'warehouse_id' => $warehouse ? $warehouse->id : null,
                'position' => 'Warehouse Manager',
                'phone' => '021-87654321',
                'is_active' => true,
            ]
        );
        $manager->assignRole('warehouse_manager');
        
        // Staff
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@mangowms.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('Staff@123456'),
                'company_id' => $company ? $company->id : null,
                'warehouse_id' => $warehouse ? $warehouse->id : null,
                'position' => 'Warehouse Staff',
                'phone' => '021-11223344',
                'is_active' => true,
            ]
        );
        $staffUser->assignRole('staff');
        
        // Auditor
        $auditorUser = User::updateOrCreate(
            ['email' => 'auditor@mangowms.com'],
            [
                'name' => 'Internal Auditor',
                'password' => Hash::make('Auditor@123456'),
                'company_id' => $company ? $company->id : null,
                'warehouse_id' => null,
                'position' => 'Internal Auditor',
                'phone' => '021-99887766',
                'is_active' => true,
            ]
        );
        $auditorUser->assignRole('auditor');
        
        // ============================================
        // 4. OUTPUT INFO
        // ============================================
        
        $this->command->info('============================================');
        $this->command->info('MANGO WMS - USER ACCOUNTS');
        $this->command->info('============================================');
        $this->command->info('Super Admin: superadmin@mangowms.com / Admin@123456');
        $this->command->info('Manager:     manager@mangowms.com / Manager@123456');
        $this->command->info('Staff:       staff@mangowms.com / Staff@123456');
        $this->command->info('Auditor:     auditor@mangowms.com / Auditor@123456');
        $this->command->info('============================================');
        $this->command->info('');
        $this->command->info('Roles created: super_admin, director, warehouse_manager, supervisor, staff, auditor');
        $this->command->info('Permissions created: ' . count($permissions) . ' permissions');
        $this->command->info('============================================');
    }
}