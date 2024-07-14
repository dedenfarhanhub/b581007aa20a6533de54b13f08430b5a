<?php

namespace App\Seeders;

require __DIR__ . '/../config.php';
require __DIR__ . '/../DB.php';

use App\DB;

class SeedOAuthClients
{
    public static function run(): void
    {
        $pdo = DB::getInstance()->getConnection();

        $existingClients = $pdo->query("SELECT COUNT(*) FROM oauth_clients")->fetchColumn();
        if ($existingClients > 0) {
            echo "OAuth clients already seeded. Skipping...\n";
            return;
        }

        $clients = [
            [
                'id' => 'client1',
                'secret' => 'client1_secret',
                'name' => 'Client 1',
                'redirect_uri' => 'https://example.com/callback',
            ],
            [
                'id' => 'client2',
                'secret' => 'client2_secret',
                'name' => 'Client 2',
                'redirect_uri' => 'https://example.com/callback',
            ],
        ];

        $insertSql = "INSERT INTO oauth_clients (id, secret, name, redirect_uri) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertSql);

        foreach ($clients as $client) {
            $stmt->execute([
                $client['id'],
                $client['secret'],
                $client['name'],
                '{' . $client['redirect_uri'] . '}'
            ]);
        }

        echo "OAuth clients seeded successfully.\n";
    }
}

// Run the seeder
SeedOAuthClients::run();
