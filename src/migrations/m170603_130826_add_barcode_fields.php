<?php

use yii\db\Migration;

class m170603_130826_add_barcode_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_product}}', 'barcode', $this->string(55));
        $this->addColumn('{{%shop_product_modification}}', 'barcode', $this->string(55));
    }

    public function down()
    {
        $this->dropColumn('{{%shop_product}}', 'barcode');
        $this->dropColumn('{{%shop_product_modification}}', 'barcode');
    }
}
