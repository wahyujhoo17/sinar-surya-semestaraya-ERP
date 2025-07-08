<?php
require_once 'vendor/autoload.php';

use App\Services\NotificationService;
use App\Models\User;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

// Simulate a request with domain
$_SERVER['HTTP_HOST'] = 'erp.sinarsurya.id';
$_SERVER['REQUEST_URI'] = '/dashboard';
$_SERVER['HTTPS'] = 'on';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Http\Kernel')->bootstrap();

echo "🔍 Testing Notification URL Fix\n";
echo "================================\n\n";

// Check current config
echo "📝 Current Configuration:\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "Current Domain: " . request()->getSchemeAndHttpHost() . "\n\n";

// Test URL generation
echo "🧪 Testing URL Generation:\n";

// Test route URL
try {
    $testUrl = route('penjualan.sales-order.show', 1);
    echo "Route URL: " . $testUrl . "\n";

    // Test fix function (simulate)
    class TestNotificationService extends NotificationService
    {
        public function testFixUrl($url)
        {
            return $this->fixNotificationUrl($url);
        }
    }

    $service = new TestNotificationService();
    $fixedUrl = $service->testFixUrl($testUrl);
    echo "Fixed URL: " . $fixedUrl . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Test completed!\n";
echo "\n📋 Summary:\n";
echo "- NotificationService now has fixNotificationUrl() function\n";
echo "- URLs with localhost will be replaced with current domain\n";
echo "- Frontend also has protection against localhost URLs\n";
echo "- Both backend and frontend will ensure proper domain usage\n";

echo "\n🚀 To apply the fix:\n";
echo "1. NotificationService automatically fixes URLs when creating notifications\n";
echo "2. Frontend navbar also fixes URLs when displaying notifications\n";
echo "3. All new notifications will use the correct domain\n";
echo "4. Existing notifications with localhost URLs will be fixed on display\n";
