<?php

use yii\db\Schema;
use yii\db\Migration;

class m161126_112619_Mass extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $connection = Yii::$app->db;
        try {
            $this->createTable('{{%shop_product}}', [
                'id' => Schema::TYPE_PK . "",
                'category_id' => Schema::TYPE_INTEGER . "(10)",
                'amount' => Schema::TYPE_INTEGER . "(11)",
                'name' => Schema::TYPE_STRING . "(200) NOT NULL",
                'code' => Schema::TYPE_STRING . "(155)",
                'price' => Schema::TYPE_DECIMAL . "(11, 2)",
                'text' => Schema::TYPE_TEXT . " ",
                'is_new' => "enum('yes','no')" . " DEFAULT 'no'",
                'is_popular' => "enum('yes','no')" . " DEFAULT 'no'",
                'is_promo' => "enum('yes','no')" . " DEFAULT 'no'",
                'images' => Schema::TYPE_TEXT . "",
                'available' => "enum('yes','no')" . " DEFAULT 'yes'",
                'sort' => Schema::TYPE_INTEGER . "(11)",
                ], $tableOptions);

            $this->createIndex('category_id', '{{%shop_product}}', 'category_id', 0);

            $this->createTable('{{%shop_category}}', [
                'id' => Schema::TYPE_PK . "",
                'name' => Schema::TYPE_STRING . "(55) NOT NULL",
                'code' => Schema::TYPE_STRING . "(155)",
                'text' => Schema::TYPE_TEXT . "",
                'image' => Schema::TYPE_TEXT . "",
                'sort' => Schema::TYPE_INTEGER . "(11)",
                ], $tableOptions);

            $this->createTable( '{{%shop_incoming}}',[
                'id' => Schema::TYPE_PK . "",
                'date' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'product_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'amount' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'price' => Schema::TYPE_DECIMAL . "(11, 2)",
            ], $tableOptions);

            $this->addForeignKey(
                'fk_category', '{{%shop_product}}', 'category_id', '{{%shop_category}}', 'id', 'CASCADE', 'CASCADE'
            );
            
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

    public function safeDown() {
        $connection = Yii::$app->db;
        try {
            $this->dropTable('{{%shop_product}}');
            $this->dropTable('{{%shop_category}}');
            $this->dropTable('{{%shop_incoming}}');
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' :(((((((( ';
        }
    }

}
