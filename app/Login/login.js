document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault();

  let role = document.getElementById("user-type").value;
  let rollNo = document.getElementById("roll_no").value.trim();
  let password = document.getElementById("password").value.trim();

  if (rollNo === "") {
    alert("Please enter your Roll Number.");
    return;
  }
  if (password === "") {
    alert("Please enter your password.");
    return;
  }

  const bodyData = `role=${encodeURIComponent(role)}&roll_no=${encodeURIComponent(
    rollNo
  )}&password=${encodeURIComponent(password)}`;

  fetch("login.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: bodyData,
  })
    .then((response) => response.text())
    .then((message) => {
      alert(message);
      if (message.includes("Login successful")) {
        sessionStorage.setItem('roll_no', rollNo);
        if (role === "student") {
          // Fetch student info from backend
          fetch(`../Student/get_user_info.php?roll_no=${encodeURIComponent(rollNo)}`)
            .then(res => res.json())
            .then(data => {
              if (data && data.email && data.phone) {
                sessionStorage.setItem('email', data.email);
                sessionStorage.setItem('phone', data.phone);
              }
              if (data && data.full_name) {
                sessionStorage.setItem('full_name', data.full_name);
              }
              if (data && data.profile_photo) {
                sessionStorage.setItem('profile_photo', data.profile_photo);
              }
              if (data && data.id) {
                sessionStorage.setItem('user_id', data.id);
              }
              window.location.href = "../Student/student_dashboard.html";
            })
            .catch(() => {
              window.location.href = "../Student/student_dashboard.html";
            });
        } else if (role === "admin") {
          // For admin, store user_id from login response (not available, so skip)
          window.location.href = "../Admin/admin_dashboard.html";
        } else {
          // For staff, store user_id from login response (not available, so skip)
          window.location.href = "../Staff/staff_dashboard.html";
        }
      }
    })
    .catch(() => {
      alert("An error occurred during login. Please try again.");
    });
});
