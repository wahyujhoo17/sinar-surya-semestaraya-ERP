<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;

// Test script untuk verifikasi password
echo "=== Password Test Script ===\n";

// Ganti dengan password yang Anda gunakan untuk reset
$testPassword = 'password123'; // Ganti dengan password baru yang Anda set

// Ganti dengan hash yang ada di database setelah reset
$hashFromDatabase = ''; // Copy hash dari database setelah reset

if (empty($hashFromDatabase)) {
    echo "❌ Silakan isi \$hashFromDatabase dengan hash dari database\n";
    echo "Jalankan query: SELECT password FROM users WHERE id = [USER_ID]\n";
    exit;
}

echo "Testing password: $testPassword\n";
echo "Against hash: $hashFromDatabase\n";

// Test apakah password cocok dengan hash
$isValid = password_verify($testPassword, $hashFromDatabase);

if ($isValid) {
    echo "✅ Password COCOK dengan hash di database\n";
    echo "✅ Reset password BERHASIL\n";
} else {
    echo "❌ Password TIDAK COCOK dengan hash di database\n";
    echo "❌ Ada masalah dengan reset password\n";
}

// Generate hash baru untuk perbandingan
$newHash = password_hash($testPassword, PASSWORD_DEFAULT);
echo "\nHash baru yang dibuat: $newHash\n";
echo "Hash berbeda? " . ($hashFromDatabase !== $newHash ? "Ya (normal)" : "Tidak (aneh)") . "\n";
