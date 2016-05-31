<?php

use yii\db\Migration;

class m160328_072309_adding_product_price_and_supplier_example_data extends Migration
{
    public function up()
    {
        $materialId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%material}}')
            ->min('id');

        $this->insert('{{%product}}', [
            'code' => 'DIN 6768',
            'name' => 'Шайба',
            'material' => $materialId,
            'dia' => 12,
            'package' => 5000,
        ]);
        $product1Id = $this->db->lastInsertID;

        $this->insert('{{%product}}', [
            'code' => 'DIN 6768',
            'name' => 'Шайба',
            'material' => $materialId,
            'dia' => 14,
            'package' => 4000,
        ]);
        $product2Id = $this->db->lastInsertID;

        $this->insert('{{%supplier}}', [
           'name' => 'Nicolas supplies',
        ]);
        $supplierId = $this->db->lastInsertID;

        $this->batchInsert('{{%price}}',
            ['created_at', 'updated_at', 'started_at', 'expire_at', 'product', 'supplier', 'value'],
        [
            ['1459150440', '1459150440', '1459150440', null,       $product1Id, $supplierId, '10.50'],
            ['1459150440', '1459150440', '1459150440', '1459150540', $product1Id, $supplierId, '10.60'],
            ['1459150440', '1459150440', '1459250440', '1459350440', $product1Id, $supplierId, '10.50'],

            ['1459150440', '1459150440', '1459150440', null,       $product2Id, $supplierId, '20.50'],
            ['1459150440', '1459150440', '1459150440', '1459150540', $product2Id, $supplierId, '20.60'],
            ['1459150440', '1459150440', '1459250440', '1459350440', $product2Id, $supplierId, '20.50'],
        ]);
    }

    public function down()
    {
        $this->delete('{{%price}}');
        $this->delete('{{%product}}');
    }

}
