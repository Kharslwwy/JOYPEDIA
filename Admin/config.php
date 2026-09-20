<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Ambil variabel
$cfApiKey = $_ENV['CF_API_KEY'];
$cfAccountId = $_ENV['CF_ACCOUNT_ID'];
$openAiKey = $_ENV['OPENAI_API_KEY'];

// Contoh cek API Key Cloudflare
echo "Cloudflare API Key: $cfApiKey\n";
echo "Cloudflare Account ID: $cfAccountId\n";

// Contoh cek OpenAI Moderation
echo "OpenAI API Key: $openAiKey\n";
