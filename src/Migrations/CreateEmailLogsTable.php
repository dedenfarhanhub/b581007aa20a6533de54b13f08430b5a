<?php

namespace App\Migrations;

require __DIR__ . '/../config.php';
require __DIR__ . '/../DB.php';

use App\DB;

class CreateEmailLogsTable
{
    public static function up(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "
        CREATE TABLE IF NOT EXISTS email_logs (
            id SERIAL PRIMARY KEY,
            recipient VARCHAR(255) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            body TEXT NOT NULL,
            error TEXT,
            send_at TIMESTAMP(0) WITHOUT TIME ZONE,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
        )
        ";

        $pdo->exec($sql);

        $sqlIndexRecipient = "CREATE INDEX IF NOT EXISTS idx_email_logs_recipient ON email_logs (recipient)";
        $pdo->exec($sqlIndexRecipient);

        $sqlIndexSendAt = "CREATE INDEX IF NOT EXISTS idx_email_logs_send_at ON email_logs (send_at)";
        $pdo->exec($sqlIndexSendAt);

        $sqlIndexCreatedAt = "CREATE INDEX IF NOT EXISTS idx_email_logs_created_at ON email_logs (created_at)";
        $pdo->exec($sqlIndexCreatedAt);

        $sqlIndexUpdatedAt = "CREATE INDEX IF NOT EXISTS idx_email_logs_updated_at ON email_logs (updated_at)";
        $pdo->exec($sqlIndexUpdatedAt);

        echo "Migration completed.\n";
    }

    public static function down(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $sql = "DROP TABLE IF EXISTS email_logs";

        $pdo->exec($sql);
    }
}

// Perform side effect (migration)
CreateEmailLogsTable::up();
