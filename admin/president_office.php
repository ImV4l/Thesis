<?php 

include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
     <h4 class="mt-4">Time Log Report</h4>
     <ol class="breadcrumb mb-4">
     <li class="breadcrumb-item active">Attendance</li>
        <li class="breadcrumb-item active">Time in / Time Out</li>
        <li class="breadcrumb-item active">President Office</li>
    </ol>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">  
                    <h4>President Office</h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>z
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Program</th>
                                <th>Year</th>
                               
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
include('includes/footer.php');
include('includes/scripts.php');
?>