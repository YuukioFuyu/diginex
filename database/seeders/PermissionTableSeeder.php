<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder populates the permissions table with predefined permissions.
     * It also creates a super-admin role and assigns all permissions to it.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define the permissions
        $permissions = [

            ['category' => 'dashboard', 'name' => 'dashboard-view'],
            ['category' => 'dashboard', 'name' => 'statistics_card'],
            ['category' => 'dashboard', 'name' => 'transaction_chart'],
            ['category' => 'dashboard', 'name' => 'top_services_chart'],
            ['category' => 'dashboard', 'name' => 'latest_user'],
            ['category' => 'dashboard', 'name' => 'latest_order_chart'],

            ['category' => 'user-management', 'name' => 'user-list'],
            ['category' => 'user-management', 'name' => 'user-manage'],
            ['category' => 'user-management', 'name' => 'user-balance-manage'],
            ['category' => 'user-management', 'name' => 'user-as-login'],
            ['category' => 'user-management', 'name' => 'user-send-notification'],

            ['category' => 'staff-management', 'name' => 'staff-list'],
            ['category' => 'staff-management', 'name' => 'staff-create'],
            ['category' => 'staff-management', 'name' => 'staff-edit'],

            ['category' => 'role-management', 'name' => 'role-list'],
            ['category' => 'role-management', 'name' => 'role-create'],
            ['category' => 'role-management', 'name' => 'role-edit'],
            ['category' => 'role-management', 'name' => 'role-delete'],

            ['category' => 'payment-gateway', 'name' => 'payment-gateway-list'],
            ['category' => 'payment-gateway', 'name' => 'payment-gateway-manage'],

            ['category' => 'deposit', 'name' => 'deposit-method-list'],
            ['category' => 'deposit', 'name' => 'deposit-method-manage'],
            ['category' => 'deposit', 'name' => 'deposit-list'],
            ['category' => 'deposit', 'name' => 'deposit-action'],
            ['category' => 'deposit', 'name' => 'deposit-notification'],

            ['category' => 'service', 'name' => 'service-list'],
            ['category' => 'service', 'name' => 'service-manage'],
            ['category' => 'service', 'name' => 'order-list'],
            ['category' => 'service', 'name' => 'order-manage'],
            ['category' => 'service', 'name' => 'service-notification'],

            ['category' => 'transaction', 'name' => 'transaction-list'],

            ['category' => 'subscription', 'name' => 'plan-list'],
            ['category' => 'subscription', 'name' => 'plan-manage'],
            ['category' => 'subscription', 'name' => 'subscription-list'],
            ['category' => 'subscription', 'name' => 'subscription-manage'],
            ['category' => 'subscription', 'name' => 'subscription-notification'],

            ['category' => 'settings', 'name' => 'site-settings-view'],
            ['category' => 'settings', 'name' => 'site-settings-manage'],
            ['category' => 'settings', 'name' => 'plugin-list'],
            ['category' => 'settings', 'name' => 'plugin-manage'],
            ['category' => 'settings', 'name' => 'mail-provider-manage'],
            ['category' => 'settings', 'name' => 'notification-plugin-manage'],
            ['category' => 'settings', 'name' => 'exchange-rate-plugin-manage'],

            ['category' => 'language', 'name' => 'language-list'],
            ['category' => 'language', 'name' => 'language-manage'],

            ['category' => 'notification', 'name' => 'list-notification'],
            ['category' => 'notification', 'name' => 'notification-template-list'],
            ['category' => 'notification', 'name' => 'notification-template-manage'],

            ['category' => 'site-essentials', 'name' => 'theme-list'],
            ['category' => 'site-essentials', 'name' => 'theme-manage'],
            ['category' => 'site-essentials', 'name' => 'navigation-list'],
            ['category' => 'site-essentials', 'name' => 'navigation-manage'],
            ['category' => 'site-essentials', 'name' => 'page-list'],
            ['category' => 'site-essentials', 'name' => 'page-manage'],
            ['category' => 'site-essentials', 'name' => 'component-list'],
            ['category' => 'site-essentials', 'name' => 'component-manage'],
            ['category' => 'site-essentials', 'name' => 'blog-category-list'],
            ['category' => 'site-essentials', 'name' => 'blog-category-manage'],
            ['category' => 'site-essentials', 'name' => 'blog-list'],
            ['category' => 'site-essentials', 'name' => 'blog-manage'],
            ['category' => 'site-essentials', 'name' => 'seo-list'],
            ['category' => 'site-essentials', 'name' => 'seo-manage'],
            ['category' => 'site-essentials', 'name' => 'social-list'],
            ['category' => 'site-essentials', 'name' => 'social-manage'],

            ['category' => 'support-ticket', 'name' => 'support-category-list'],
            ['category' => 'support-ticket', 'name' => 'support-category-manage'],
            ['category' => 'support-ticket', 'name' => 'support-list'],
            ['category' => 'support-ticket', 'name' => 'support-manage'],
            ['category' => 'support-ticket', 'name' => 'support-notification'],

            ['category' => 'application', 'name' => 'email-subscriber'],
            ['category' => 'application', 'name' => 'site-customize'],
            ['category' => 'application', 'name' => 'app-optimize'],
            ['category' => 'application', 'name' => 'app-clear-cache'],
            ['category' => 'application', 'name' => 'app-info'],
        ];

        // Truncate the permissions table and insert the new permissions
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = collect($permissions)->map(function ($permission) {
            return ['name' => $permission['name'], 'category' => $permission['category'], 'guard_name' => 'admin'];
        });
        Permission::insert($permissions->toArray());

        // Create or retrieve the super-admin role
        $superRole = Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'super-admin']);

        // Assign all permissions to the super-admin role
        $superRole->givePermissionTo(Permission::all());
    }
}
