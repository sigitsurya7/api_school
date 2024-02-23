<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                "type" => "INT",
                "constraint" => 15,
                "unsigned" => true,
                "auto_increment" => true
            ],
            "code_name" => [
                "type" => "VARCHAR",
                "constraint" => 255
            ],
            "role_name" => [
                "type" => "VARCHAR",
                "constraint" => 255
            ],
            "is_active" => [
                "type" => "TINYINT",
                'default' => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('auth_role');

        $data = [
            [
                'code_name' => 'admin',
                'role_name' => 'Admin',
                'is_active' => 1
            ],
            [
                'code_name' => 'guru',
                'role_name' => 'Guru',
                'is_active' => 1
            ],
            [
                'code_name' => 'walas',
                'role_name' => 'Wali Kelas',
                'is_active' => 1
            ],
            [
                'code_name' => 'kesiswaan',
                'role_name' => 'Kesiswaan',
                'is_active' => 1
            ],
            [
                'code_name' => 'bk',
                'role_name' => 'Balai Konseling',
                'is_active' => 1
            ],
            [
                'code_name' => 'tu',
                'role_name' => 'Tata Usaha',
                'is_active' => 1
            ],
            [
                'code_name' => 'murid',
                'role_name' => 'Murid',
                'is_active' => 1
            ],
            [
                'code_name' => 'wali_murid',
                'role_name' => 'Wali Murid',
                'is_active' => 1
            ],
        ];

        $this->db->table('auth_role')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('auth_role');
    }
}
