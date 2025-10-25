<!DOCTYPE html>
<html>
<head>
    <title>QR Code Debug Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 12px;
        }
        .qr-container {
            text-align: center;
            padding: 20px;
            background: #fff;
            border: 2px solid #007bff;
            border-radius: 8px;
        }
        .qr-code {
            width: 120px;
            height: 120px;
            border: 1px solid #ddd;
            padding: 5px;
            margin: 10px auto;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin: 5px 0;
        }
        .status.success { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .test-info {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 QR Code Debug Test</h1>
        
        @if(isset($debugInfo))
            <div class="debug-info">
                <strong>Debug Information:</strong><br>
                <pre>{{ json_encode($debugInfo, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
        
        <div class="qr-container">
            <h2>QR Code Render Test</h2>
            
            @if(isset($whatsappQR) && $whatsappQR)
                <div class="status success">✅ QR Code Generated</div>
                <p>QR Code berhasil di-generate dan di-render di HTML!</p>
                
                <div>
                    <img src="{{ $whatsappQR }}" alt="WhatsApp QR Code" class="qr-code">
                </div>
                
                <p style="font-size: 12px; color: #666;">
                    Length: {{ strlen($whatsappQR) }} bytes<br>
                    Format: {{ strpos($whatsappQR, 'svg') !== false ? 'SVG' : 'PNG' }}
                </p>
            @else
                <div class="status error">❌ QR Code NOT Generated</div>
                <p>QR Code gagal di-generate atau NULL!</p>
                
                <div style="width: 120px; height: 120px; background: #f8d7da; margin: 10px auto; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #721c24; font-weight: bold;">NO QR</span>
                </div>
            @endif
        </div>
        
        <div class="test-info">
            <strong>📝 Test Purpose:</strong><br>
            Endpoint ini test apakah:
            <ul>
                <li>✓ QR Code library berfungsi</li>
                <li>✓ Helper function generate QR Code</li>
                <li>✓ Base64 image bisa di-render di HTML</li>
                <li>✓ Browser bisa load data URI images</li>
            </ul>
            
            <strong>🎯 Expected vs DomPDF:</strong><br>
            Kalau QR Code <strong>MUNCUL di halaman ini</strong> tapi <strong>TIDAK di PDF</strong>, 
            berarti masalahnya di DomPDF configuration, bukan di QR generation.
        </div>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="/test-qr" style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">
                JSON Test
            </a>
            <a href="/debug-qr-render" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">
                Generic QR Test
            </a>
        </div>
    </div>
</body>
</html>
