

  function togglePassword() {
    var password = document.getElementsByClassName("pass")[0];
    if (password.type === "password") {
      password.type = "text";
    } else {
      password.type = "password";
    }
  }