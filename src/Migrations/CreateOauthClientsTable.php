<?php

namespace App\Migrations;

require __DIR__ . '/../config.php';
require __DIR__ . '/../DB.php';

use App\DB;

class CreateOauthClientsTable
{
    public static function up(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "
        CREATE TABLE IF NOT EXISTS oauth_clients (
            id VARCHAR(80) PRIMARY KEY,
            secret VARCHAR(255),
            name VARCHAR(255),
            redirect_uri TEXT[],
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
        )
        ";

        $pdo->exec($sql);

        echo "Migration completed.\n";
    }

    public static function down(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "DROP TABLE IF EXISTS oauth_clients";

        $pdo->exec($sql);
    }
}

// Perform side effect (migration)
CreateOauthClientsTable::up();
