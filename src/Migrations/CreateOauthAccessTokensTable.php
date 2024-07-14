<?php

namespace App\Migrations;

require __DIR__ . '/../config.php';
require __DIR__ . '/../DB.php';

use App\DB;

class CreateOauthAccessTokensTable
{
    public static function up(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "
        CREATE TABLE IF NOT EXISTS oauth_access_tokens (
            id VARCHAR(100) PRIMARY KEY,
            client_id VARCHAR(80),
            user_id VARCHAR(80),
            scopes TEXT[],
            revoked BOOLEAN DEFAULT false,
            expires_at TIMESTAMP(0) WITHOUT TIME ZONE,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
        ";

        $pdo->exec($sql);

        echo "Migration completed.\n";
    }

    public static function down(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "DROP TABLE IF EXISTS oauth_access_tokens";

        $pdo->exec($sql);
    }
}

// Perform side effect (migration)
CreateOauthAccessTokensTable::up();
