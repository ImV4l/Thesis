
<?php
session_start();
unset($_SESSION['sa_form_data']);
echo json_encode(['success' => true]);