<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="accounts.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Accounts
                </a>
                <a class="nav-link" href="view-register.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    View Student Assistants
                </a>
                <div class="sb-sidenav-menu-heading">Manage</div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Department
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Engineering
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="techno.php">Information Technology</a>
                                <a class="nav-link" href="compe.php">Computer Engineering</a>
                                <a class="nav-link" href="ee.php">Electrical Engineering</a>
                            </nav>
                        </div>
                        <a class="nav-link" href="bsba.php">Business Accountancy</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Attendance
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Time in / Time out
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="tito_offices.php">Offices</a>
                                <a class="nav-link" href="tito_lab.php">Laboratory</a>
                                <a class="nav-link" href="tito_manpower.php">Manpower Services</a>
                            </nav>
                        </div>
                    </nav>
                </div>
                <a class="nav-link" href="student_schedule.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                        Schedule
                    </a>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBins" aria-expanded="false" aria-controls="collapseBins">
                    <div class="sb-nav-link-icon"><i class="fas fa-trash"></i></div>
                    Bin
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse" id="collapseBins" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="restoreacc.php">Accounts Bins</a>
                        <a class="nav-link" href="restore.php">Student Assistant Bins</a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">Report</div>
                    <a class="nav-link" href="report.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Daily Time Record
                    </a>
                    <a class="nav-link" href="payroll.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-copy"></i></div>
                    Payroll
                    </a>
                </div>
        </div>

        
    </nav>
</div>