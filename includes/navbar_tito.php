<nav style="background-color: #F16E04;" class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand text-white" href="index.php" style="display: flex; align-items: center;">
      <img src="includes/witlogo.png" alt="WIT Logo" style="height: 40px; margin-right: 10px;" onerror="this.onerror=null; this.src='default-logo.png';">
      WIT Student Assistant
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item d-flex align-items-center me-4">
          <div class="datetime-container text-white d-flex align-items-center gap-3">
            <div class="date-group">
              <div id="day" style="font-weight: bold; font-size: 1.1rem; line-height: 1.2;"></div>
              <div id="date" style="font-size: 0.9rem; opacity: 0.9;"></div>
            </div>
            <div id="time" style="font-family: 'Courier New', monospace; font-size: 1.2rem; font-weight: bold; border-left: 2px solid rgba(255,255,255,0.3); padding-left: 12px;"></div>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="register.php">Register</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
function updateDateTime() {
    const now = new Date();
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    
    // Format date
    const options = { month: 'long', day: 'numeric', year: 'numeric' };
    const dateFormatted = now.toLocaleDateString('en-US', options);
    
    // Format time with leading zeros
    const timeFormatted = now.toLocaleTimeString('en-US', { 
        hour12: true,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    document.getElementById('day').innerHTML = days[now.getDay()];
    document.getElementById('date').innerHTML = dateFormatted;
    document.getElementById('time').innerHTML = timeFormatted;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>
