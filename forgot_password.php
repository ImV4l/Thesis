<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
?>
<div class="py-5" style="background-image: url('assets/witbg.jpg'); background-size: cover; background-position: center; height: 94vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card" style="background-color: rgba(255, 255, 255, 0.5); border: none; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    <div style="background-color: #F16E04;" class="card-header">
                        <h4 class="text-white">Forgot Password</h4>
                    </div>
                    <div class="card-body" style="background-color: rgba(255, 255, 255, 0.3);">
                        <form action="send_reset_link.php" method="POST">
                            <div class="form-group mb-3">
                                <label>Enter your Email</label>
                                <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="send_reset_link_btn" class="btn btn-primary">Send Reset Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include('includes/footer.php');
?>