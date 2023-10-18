<?php

use yii\db\Migration;

/**
 * Table for Reader
 */
class m231016_121212_reader extends Migration
{
    public function up()
    {
        $this->createTable('reader', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function down()
    {
        $this->dropTable('reader');
    }
}
