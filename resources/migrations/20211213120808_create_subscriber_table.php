<?php

use Phoenix\Migration\AbstractMigration;

class CreateSubscriberTable extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('subscriber', 'id')
        ->setCharset('utf8mb4')
        ->setCollation('utf8mb4_unicode_ci')
        ->addColumn('id', 'integer', ['autoincrement' => true])
        ->addColumn('first_name', 'string', ['null' => true])
        ->addColumn('last_name', 'string', ['null' => true])
        ->addColumn('email', 'string', ['null' => true])
        ->addColumn('confirmed', 'boolean', ['default' => false])
        ->addColumn('hash', 'string', ['null' => true])
        ->addColumn('emailHash', 'string', ['null' => true])
        ->create();
    }

    protected function down(): void
    {
        $this->table('subscriber')
        ->drop();
    }
}
