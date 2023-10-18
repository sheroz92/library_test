<?php

use yii\db\Migration;

/**
 * Table for Book
 */
class m231016_134533_book extends Migration
{
    public function up()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'author' => $this->string(),
            'alias' => $this->string(100)->unique(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function down()
    {
        $this->dropTable('reader');
    }
}
