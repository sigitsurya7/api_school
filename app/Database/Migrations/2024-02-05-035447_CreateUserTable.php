<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
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
            "name" => [
                "type" => "VARCHAR",
                "constraint" => 255
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => 255
            ],
            "username" => [
                "type" => "VARCHAR",
                "constraint" => 255
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => 100
            ],
            "role" => [
                "type" => "VARCHAR",
                "constraint" => 8
            ],
            "is_active" => [
                "type" => "TINYINT",
                'default' => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'

        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('auth_users');
    }

    public function down()
    {
        $this->forge->dropTable('auth_users');
    }
}
