function togglePasswordVisibility(id) {
	const passwordField = document.getElementById(id);
	const toggleIcon = passwordField.nextElementSibling.querySelector("i");

	if (passwordField.type === "password") {
		passwordField.type = "text";
		toggleIcon.classList.remove("fa-eye-slash");
		toggleIcon.classList.add("fa-eye");
	} else {
		passwordField.type = "password";
		toggleIcon.classList.remove("fa-eye");
		toggleIcon.classList.add("fa-eye-slash");
	}
}
