<?php

use yii\db\Migration;

/**
 * Table for Journal
 */
class m231016_141513_journal extends Migration
{
    public function up()
    {
        $this->createTable('journal', [
            'id' => $this->primaryKey(),
            'reader_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'expected_return_date' => $this->date(),
            'return_date' => $this->date(),
            'issue_date' => $this->date(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addForeignKey('journal_reader_fk', 'journal', 'reader_id', 'reader', 'id', 'CASCADE');
        $this->addForeignKey('journal_book_fk', 'journal', 'book_id', 'book', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('reader');
    }
}
