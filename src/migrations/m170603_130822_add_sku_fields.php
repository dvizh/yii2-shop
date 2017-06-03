<?php

use yii\db\Migration;

class m170603_130822_add_sku_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_product}}', 'sku', $this->string(55));
        $this->addColumn('{{%shop_product_modification}}', 'sku', $this->string(55));
    }

    public function down()
    {
        $this->dropColumn('{{%shop_product}}', 'sku');
        $this->dropColumn('{{%shop_product_modification}}', 'sku');
    }
}
