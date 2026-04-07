<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Warehouse;

class CompanyWarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat perusahaan Mango
        $company = Company::create([
            'code' => 'MANGO001',
            'name' => 'PT Mango Logistics Indonesia',
            'tax_id' => '01.234.567.8-123.000',
            'address' => 'Jl. Industri Raya No. 123, Jakarta Selatan',
            'phone' => '(021) 12345678',
            'email' => 'info@mango.co.id',
            'is_active' => true,
        ]);
        
        // Buat gudang pertama
        Warehouse::create([
            'company_id' => $company->id,
            'code' => 'WH-JKT-01',
            'name' => 'Gudang Jakarta Pusat',
            'address' => 'Jl. Gudang No. 1, Jakarta Pusat',
            'phone' => '(021) 87654321',
            'manager_name' => 'Budi Santoso',
            'is_active' => true,
        ]);
        
        // Buat gudang kedua
        Warehouse::create([
            'company_id' => $company->id,
            'code' => 'WH-SBY-01',
            'name' => 'Gudang Surabaya',
            'address' => 'Jl. Raya Surabaya No. 45, Surabaya',
            'phone' => '(031) 98765432',
            'manager_name' => 'Siti Aminah',
            'is_active' => true,
        ]);
        
        // Update user admin dengan company dan warehouse
        $admin = \App\Models\User::where('email', 'admin@mangowms.com')->first();
        if ($admin) {
            $admin->update([
                'company_id' => $company->id,
                'warehouse_id' => 1,
                'position' => 'System Administrator',
                'is_active' => true,
            ]);
        }
        
        $this->command->info('Company and Warehouse seeded successfully!');
    }
}