<?php
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
    <h6></h6>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Payroll</h4>
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
                                $hourly_rate = 15; // Example hourly rate
                                $query = "SELECT sa.id, sa.last_name, sa.first_name, sa.work, SUM(TIMESTAMPDIFF(HOUR, a.time_in, a.time_out)) AS total_hours
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
                                                <td>{$num_hour}</td>
                                                <td>&#8369;{$gross_salary}</td>
                                                <td>
                                                    <button class='btn btn-success btn-sm payroll-btn' data-id='{$row['id']}' data-name='{$row['first_name']} {$row['last_name']}' data-work='{$row['work']}' data-hours='{$num_hour}' data-rate='{$hourly_rate}' data-salary='{$gross_salary}'><i class='fa fa-print'></i> Payroll</button>
                                                    <button class='btn btn-primary btn-sm payslip-btn' data-id='{$row['id']}' data-name='{$row['first_name']} {$row['last_name']}' data-work='{$row['work']}' data-hours='{$num_hour}' data-rate='{$hourly_rate}' data-salary='{$gross_salary}'><i class='fa fa-print'></i> Payslip</button>
                                                    
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

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>