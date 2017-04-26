<?php

use yii\db\Migration;

class m170426_105633_modif_type_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_price}}', 'type', $this->char(1)->defaultValue('p'));
    }

    public function down()
    {
        $this->dropColumn('{{%shop_price}}', 'type');
    }
}
