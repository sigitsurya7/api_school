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
    }

    public function down()
    {
        $this->forge->dropTable('auth_role');
    }
}
