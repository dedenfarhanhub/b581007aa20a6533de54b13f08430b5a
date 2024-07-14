<?php

namespace App\Migrations;

require __DIR__ . '/../config.php';
require __DIR__ . '/../DB.php';

use App\DB;

class CreateUsersTable
{
    public static function up(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
        ";

        $pdo->exec($sql);

        $sqlIndexUsername = "CREATE INDEX IF NOT EXISTS idx_users_username ON users (username)";
        $pdo->exec($sqlIndexUsername);

        $sqlIndexCreatedAt = "CREATE INDEX IF NOT EXISTS idx_users_created_at ON users (created_at)";
        $pdo->exec($sqlIndexCreatedAt);

        $sqlIndexUpdatedAt = "CREATE INDEX IF NOT EXISTS idx_users_updated_at ON users (updated_at)";
        $pdo->exec($sqlIndexUpdatedAt);

        echo "Migration completed.\n";
    }

    public static function down(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "DROP TABLE IF EXISTS users";

        $pdo->exec($sql);
    }
}

CreateUsersTable::up();
