<?php
/**
 * Server Diagnostic Script
 * Run this on the machine at 192.168.202.100 to diagnose connection issues
 */

// #region agent log
$logData = [
    'sessionId' => 'debug-session',
    'runId' => 'run1',
    'hypothesisId' => 'A',
    'location' => 'check-server.php:10',
    'message' => 'Diagnostic script started',
    'data' => [
        'php_version' => PHP_VERSION,
        'server_addr' => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
        'server_port' => $_SERVER['SERVER_PORT'] ?? 'Unknown',
        'http_host' => $_SERVER['HTTP_HOST'] ?? 'Unknown',
        'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ],
    'timestamp' => round(microtime(true) * 1000)
];
file_put_contents('d:\\AI\\Cursor\\.cursor\\debug.log', json_encode($logData) . "\n", FILE_APPEND);
// #endregion

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Server Diagnostics</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        h2 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 5px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Server Diagnostic Report</h1>
    
    <div class="section">
        <h2>1. PHP Information</h2>
        <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
        <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
        <p><strong>Server Address:</strong> <?php echo $_SERVER['SERVER_ADDR'] ?? 'Unknown'; ?></p>
        <p><strong>Server Port:</strong> <?php echo $_SERVER['SERVER_PORT'] ?? 'Unknown'; ?></p>
        <p><strong>HTTP Host:</strong> <?php echo $_SERVER['HTTP_HOST'] ?? 'Unknown'; ?></p>
        <p><strong>Remote Address:</strong> <?php echo $_SERVER['REMOTE_ADDR'] ?? 'Unknown'; ?></p>
    </div>
    
    <div class="section">
        <h2>2. Network Interface Check</h2>
        <?php
        // #region agent log
        $logData = [
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'B',
            'location' => 'check-server.php:NETWORK_CHECK',
            'message' => 'Checking network interfaces',
            'data' => [],
            'timestamp' => round(microtime(true) * 1000)
        ];
        file_put_contents('d:\\AI\\Cursor\\.cursor\\debug.log', json_encode($logData) . "\n", FILE_APPEND);
        // #endregion
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo "<p><strong>Windows detected</strong></p>";
            echo "<pre>";
            exec('ipconfig', $output);
            echo htmlspecialchars(implode("\n", $output));
            echo "</pre>";
        } else {
            echo "<pre>";
            exec('ifconfig 2>/dev/null || ip addr', $output);
            echo htmlspecialchars(implode("\n", $output));
            echo "</pre>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>3. Port Listening Check</h2>
        <?php
        // #region agent log
        $logData = [
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'B',
            'location' => 'check-server.php:PORT_CHECK',
            'message' => 'Checking listening ports',
            'data' => [],
            'timestamp' => round(microtime(true) * 1000)
        ];
        file_put_contents('d:\\AI\\Cursor\\.cursor\\debug.log', json_encode($logData) . "\n", FILE_APPEND);
        // #endregion
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo "<pre>";
            exec('netstat -an | findstr :80', $output);
            if (empty($output)) {
                echo "<span class='error'>Port 80 is NOT listening!</span>\n";
            } else {
                echo "<span class='success'>Port 80 is listening:</span>\n";
                echo htmlspecialchars(implode("\n", $output));
            }
            echo "</pre>";
        } else {
            echo "<pre>";
            exec('netstat -tuln | grep :80 || ss -tuln | grep :80', $output);
            if (empty($output)) {
                echo "<span class='error'>Port 80 is NOT listening!</span>\n";
            } else {
                echo "<span class='success'>Port 80 is listening:</span>\n";
                echo htmlspecialchars(implode("\n", $output));
            }
            echo "</pre>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>4. PHP Extensions</h2>
        <?php
        $required = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
        echo "<ul>";
        foreach ($required as $ext) {
            $loaded = extension_loaded($ext);
            $status = $loaded ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
            echo "<li>$status $ext</li>";
        }
        echo "</ul>";
        ?>
    </div>
    
    <div class="section">
        <h2>5. File Permissions</h2>
        <?php
        $files_to_check = ['install.php', 'config.php'];
        echo "<ul>";
        foreach ($files_to_check as $file) {
            if (file_exists($file)) {
                $readable = is_readable($file) ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
                $writable = is_writable($file) ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
                echo "<li>$file - Read: $readable Write: $writable</li>";
            } else {
                echo "<li>$file - <span class='warning'>Not found</span></li>";
            }
        }
        echo "</ul>";
        ?>
    </div>
    
    <div class="section">
        <h2>6. Recommendations</h2>
        <ul>
            <li>If port 80 is not listening: Start your web server (Apache/Nginx)</li>
            <li>If server is only listening on 127.0.0.1: Configure it to listen on 0.0.0.0 or your network IP</li>
            <li>Check Windows Firewall: Allow port 80 for inbound connections</li>
            <li>Verify network: Ensure both machines are on the same network (192.168.202.x)</li>
        </ul>
    </div>
</body>
</html>
