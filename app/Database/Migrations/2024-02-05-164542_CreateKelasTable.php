<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelasTable extends Migration
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
            "code_kelas" => [
                "type" => "VARCHAR",
                "constraint" => 12
            ],
            "name_kelas" => [
                "type" => "VARCHAR",
                "constraint" => 12
            ],
            "is_active" => [
                "type" => "TINYINT",
                'default' => 1
            ],
            "is_delete" => [
                "type" => "TINYINT",
                'default' => 0
            ],
            'created_by' => [
                "type" => "INT",
                "constraint" => 15
            ],
            'updated_by' => [
                "type" => "INT",
                "constraint" => 15
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('m_kelas');
    }

    public function down()
    {
        $this->forge->dropTable('m_kelas');
    }
}
