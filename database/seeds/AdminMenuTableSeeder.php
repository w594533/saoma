<?php

use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_menu')->delete();
        
        \DB::table('admin_menu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Index',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 0,
                'order' => 2,
                'title' => '管理员',
                'icon' => 'fa-tasks',
                'uri' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-10 05:49:45',
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => 2,
                'order' => 3,
                'title' => '用户',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
                'created_at' => NULL,
                'updated_at' => '2017-12-10 05:49:57',
            ),
            3 => 
            array (
                'id' => 6,
                'parent_id' => 2,
                'order' => 6,
                'title' => '菜单',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
                'created_at' => NULL,
                'updated_at' => '2017-12-10 05:50:20',
            ),
            4 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'order' => 0,
                'title' => '二维码',
                'icon' => 'fa-qrcode',
                'uri' => 'boxes',
                'created_at' => '2017-12-10 05:48:56',
                'updated_at' => '2017-12-10 05:51:33',
            ),
            5 => 
            array (
                'id' => 9,
                'parent_id' => 0,
                'order' => 0,
                'title' => '生成二维码',
                'icon' => 'fa-wrench',
                'uri' => 'boxes_generate',
                'created_at' => '2017-12-10 05:49:12',
                'updated_at' => '2017-12-10 05:53:44',
            ),
        ));
        
        
    }
}