<?php
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
    <h6></h6>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Payroll
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#updateSalaryModal">
                            <i class="fa fa-edit"></i> Update Salary
                        </button>
                    </h4>
                </div>
                <div class="content-wrapper">
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Work Hour</th>
                                    <th>Gross Salary</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rate_file = 'salary_rate.txt';
                                if (file_exists($rate_file) && is_readable($rate_file)) {
                                    $file_content = file_get_contents($rate_file);
                                    $hourly_rate = is_numeric($file_content) ? floatval($file_content) : 15;
                                } else {
                                    $hourly_rate = 15; // Default value
                                }
                                $query = "SELECT sa.id, sa.last_name, sa.first_name, sa.work, 
                                          SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out) / 60.0) AS total_hours
                                          FROM student_assistant sa
                                          LEFT JOIN attendance a ON sa.id = a.sa_id
                                          WHERE sa.status != '2'
                                          GROUP BY sa.id";
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        $num_hour = max(0, $row['total_hours'] ?? 0);
                                        $gross_salary = max(0, round($num_hour * $hourly_rate, 2));

                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['last_name']}</td>
                                                <td>{$row['first_name']}</td>
                                                <td>" . number_format($num_hour, 2) . "</td>
                                                <td>&#8369;{$gross_salary}</td>
                                                <td>
                                                    <button class='btn btn-success btn-sm payroll-btn' data-id='{$row['id']}' data-name='{$row['first_name']} {$row['last_name']}' data-work='{$row['work']}' data-hours='" . number_format($num_hour, 2) . "' data-rate='{$hourly_rate}' data-salary='{$gross_salary}'><i class='fa fa-print'></i> Payroll</button>
                                                    <button class='btn btn-primary btn-sm payslip-btn' data-id='{$row['id']}' data-name='{$row['first_name']} {$row['last_name']}' data-work='{$row['work']}' data-hours='" . number_format($num_hour, 2) . "' data-rate='{$hourly_rate}' data-salary='{$gross_salary}'><i class='fa fa-print'></i> Payslip</button>
                                                    
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No Records Found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    // Payslip Generation
    document.querySelectorAll('.payslip-btn').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const work = this.getAttribute('data-work');
            const hours = this.getAttribute('data-hours');
            const rate = this.getAttribute('data-rate');
            const salary = this.getAttribute('data-salary');

            const startDate = new Date();
            const endDate = new Date();
            endDate.setDate(startDate.getDate() + 7);

            const content = `
                <div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #000;">
                    <h2 style="text-align: center;">Student Assistant Payslip</h2>
                    <p>Date Range: ${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}</p>
                    <hr>
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <p><strong>Name:</strong> ${name}</p>
                            <p><strong>Work In:</strong> ${work}</p>
                        </div>
                        <div>
                            <p><strong>Rate per Hour:</strong> &#8369;${rate}</p>
                            <p><strong>Total Hours:</strong> ${hours}</p>
                            <p><strong>Gross Pay:</strong> &#8369;${salary}</p>
                        </div>
                    </div>
                </div>
            `;

            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`<!DOCTYPE html>
                <html>
                <head>
                    <title>Payslip</title>
                </head>
                <body>
                    ${content}
                    <button onclick="window.print()" style="margin-top: 20px;">Print/Save as PDF</button>
                </body>
                </html>`);
            printWindow.document.close();
        });
    });

    // Payroll Generation
    document.querySelectorAll('.payroll-btn').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const work = this.getAttribute('data-work');
            const hours = this.getAttribute('data-hours');
            const salary = this.getAttribute('data-salary');

            const startDate = new Date();
            const endDate = new Date();
            endDate.setDate(startDate.getDate() + 7);

            const content = `
                <div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #000;">
                    <h2 style="text-align: center;">Student Assistant Payroll</h2>
                    <p>Date Range: ${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}</p>
                    <hr>
                    <table style="width: 100%; border-collapse: collapse; text-align: center;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #000; padding: 10px;">Employee Name</th>
                                <th style="border: 1px solid #000; padding: 10px;">Work In</th>
                                <th style="border: 1px solid #000; padding: 10px;">Gross Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #000; padding: 10px;">${name}</td>
                                <td style="border: 1px solid #000; padding: 10px;">${work}</td>
                                <td style="border: 1px solid #000; padding: 10px;">&#8369;${salary}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #000; padding: 10px;"><strong>Total</strong></td>
                                <td style="border: 1px solid #000; padding: 10px;">&#8369;${salary}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;

            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`<!DOCTYPE html>
                <html>
                <head>
                    <title>Payroll</title>
                </head>
                <body>
                    ${content}
                    <button onclick="window.print()" style="margin-top: 20px;">Print/Save as PDF</button>
                </body>
                </html>`);
            printWindow.document.close();
        });
    });
</script>

<!-- Update Salary Modal -->
<div class="modal fade" id="updateSalaryModal" tabindex="-1" aria-labelledby="updateSalaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSalaryModalLabel">Update Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateSalaryForm">
                    <div class="mb-3">
                        <label for="hourlyRate" class="form-label">Update Salary (Hourly Rate)</label>
                        <input type="number" class="form-control" id="hourlyRate" name="hourlyRate" value="<?= htmlspecialchars($hourly_rate) ?>" step="0.01" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSalaryBtn">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveSalaryBtn = document.getElementById('saveSalaryBtn');
    if (saveSalaryBtn) {
        saveSalaryBtn.addEventListener('click', function() {
            const hourlyRateInput = document.getElementById('hourlyRate');
            const newRate = hourlyRateInput.value;

            if (newRate === '' || isNaN(newRate) || parseFloat(newRate) < 0) {
                alert('Please enter a valid, non-negative number for the hourly rate.');
                return;
            }

            const formData = new FormData();
            formData.append('hourlyRate', newRate);

            fetch('update_salary.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the salary rate.');
            });
        });
    }
});
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
