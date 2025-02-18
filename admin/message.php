<?php
if (isset($_SESSION['message'])) {
?>
    <H4><?= $_SESSION['message'] ?></H4>
<?php
    unset($_SESSION['message']);
}

?>