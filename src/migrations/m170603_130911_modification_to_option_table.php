<?php

use yii\db\Migration;
use yii\db\Schema;

class m170603_130911_modification_to_option_table extends Migration
{
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        try {
            $this->createTable('{{%shop_product_modification_to_option}}', [
                'id' => Schema::TYPE_PK . "",
                'modification_id' => Schema::TYPE_INTEGER . "(10)",
                'option_id' => Schema::TYPE_INTEGER . "(11)",
                'variant_id' => Schema::TYPE_INTEGER . "(11)",
            ], $tableOptions);
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

    public function down()
    {
        $this->dropTable('{{%shop_product_modification_to_option}}');
    }
}
