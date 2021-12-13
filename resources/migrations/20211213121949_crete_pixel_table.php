<?php

use Phoenix\Migration\AbstractMigration;

class CretePixelTable extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('pixel', 'id')
        ->setCharset('utf8mb4')
        ->setCollation('utf8mb4_unicode_ci')
        ->addColumn('id', 'integer', ['autoincrement' => true])
        ->addColumn('pixelType', 'string', ['null' => true])
        ->addColumn('userId', 'integer', ['null' => true])
        ->addColumn('occuredOn', 'timestamp', ['null' => true])
        ->addColumn('portalId', 'integer', ['null' => true])
        ->create();
    }

    protected function down(): void
    {
        $this->table('pixel')
        ->drop();
    }
}
