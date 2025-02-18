<?php
session_start();
include('authentication.php');
include('includes/header.php');
require_once('../includes/ConnectionLogger.php');

$logger = new ConnectionLogger();
$logs = $logger->getLogContents();
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Connection Logs</h4>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            MQTT Connection Log History
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <pre class="log-content" style="max-height: 500px; overflow-y: auto;">
                    <?php foreach($logs as $log): ?>
                        <?php echo htmlspecialchars($log); ?>
                    <?php endforeach; ?>
                </pre>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
