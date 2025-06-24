document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("employeeForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const errors = {};
    document.querySelectorAll(".error").forEach(el => el.remove());

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const countryCode = form.country_code.value.trim();
    const dob = form.dob.value;
    const gender = form.gender.value;

    if (!name) errors.name = "Name is required.";
    if (!email || !/^\S+@\S+\.\S+$/.test(email)) errors.email = "Invalid email.";
    if (!/^\+\d{1,4}$/.test(countryCode)) errors.country_code = "Invalid country code (e.g. +60).";
    if (!/^\d{9,10}$/.test(phone)) errors.phone = "Phone number must be 9–10 digits.";
    if (!dob) errors.dob = "Date of birth is required.";
    if (!gender) errors.gender = "Gender is required.";

    if (Object.keys(errors).length > 0) {
      for (const field in errors) {
        const input = form.querySelector(`[name="${field}"]`);
        const error = document.createElement("div");
        error.className = "error";
        error.innerText = errors[field];
        input.insertAdjacentElement("afterend", error);
      }
      return;
    }

    const formData = new FormData(form);

fetch("/api/submit.php", {
  method: "POST",
  body: formData,
})
  .then(res => res.text()) // ← change from res.json() to res.text()
  .then(text => {
    console.log("Raw response from server:", text); // ← log it
    try {
      const data = JSON.parse(text); // manually try to parse
      alert(data.message);
      form.reset();
    } catch (e) {
      alert("Response is not valid JSON.");
      console.error("JSON parsing error:", e);
    }
  })
  .catch(err => {
    alert("Something went wrong.");
    console.error("Fetch error:", err);
  });


  });
});
