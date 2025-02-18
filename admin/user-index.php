<?php
include('authentication.php');
include('includes/user-header.php');
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Student Assistants</h4>
    <ol class="breadcrumb mb-4">
        
    </ol>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Attendance</h4>
                </div>
                <div class="card-body">
                    <table id="admin/myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Program</th>
                                <th>Year</th>
                                <th>Work In</th>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>6</td>
                            <td>Esinosa</td>
                            <td>Val Angelo</td>
                            <td>BSIT</td>
                            <td>4</td>
                            <td>Materials Testing Laboratory</td>
                            <td>9-17-2024</td>
                            <td>Tuesday</td>
                            <td>7:59 am</td>
                            <td>12:01 pm</td>
                            <td>Present</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

include('includes/scripts.php');
?>