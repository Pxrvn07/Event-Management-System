document.getElementById("signup-form").addEventListener("submit", function (e) {
  e.preventDefault();

  let fullName = document.getElementById("full_name").value.trim();
  let rollNo = document.getElementById("roll_no").value.trim();
  let email = document.getElementById("email").value.trim();
  let password = document.getElementById("password").value.trim();
  let phone = document.getElementById("phone").value.trim();

  if (fullName.length < 3) {
    alert("Full Name must be at least 3 characters.");
    return;
  }

  if (rollNo.length < 1) {
    alert("Roll Number is required.");
    return;
  }

  let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
  if (!email.match(emailPattern)) {
    alert("Enter a valid email address.");
    return;
  }

  if (password.length < 6) {
    alert("Password must be at least 6 characters.");
    return;
  }

  if (phone && !/^[0-9]{10}$/.test(phone)) {
    alert("Phone number must be 10 digits.");
    return;
  }

  fetch("signup.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `full_name=${encodeURIComponent(fullName)}&roll_no=${encodeURIComponent(
      rollNo
    )}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(
      password
    )}&phone=${encodeURIComponent(phone)}`,
  })
    .then((response) => response.text())
    .then((message) => {
      alert(message);
      if (message.includes("Registration successful")) {
        window.location.href = "/EventManagementSystem/Login/login.html";
      }
    })
    .catch(() => {
      alert("An error occurred during registration. Please try again.");
    });
});
