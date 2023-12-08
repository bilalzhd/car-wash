if ($(".alert")) {
  $(".alert").fadeOut(5000);
}

document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".edit-user-button");
  const submitButtons = document.querySelectorAll(".submit-edit-button");
  const cancelButtons = document.querySelectorAll(".cancel-edit-button");

  editButtons.forEach((editButton) => {
    editButton.addEventListener("click", function () {
      const userId = this.getAttribute("data-id");
      const row = document.querySelector(`tr[data-id="${userId}"]`);
      // Display input fields and hide regular text
      row.querySelectorAll("td:not(.w-1/3)").forEach((td) => {
        const text = td.textContent.trim();
        td.innerHTML = `<input type="text" value="${text}">`;
      });

      // Toggle button visibility
      toggleButtons(userId);
    });
  });

  submitButtons.forEach((submitButton) => {
    submitButton.addEventListener("click", function () {
      const userId = this.getAttribute("data-id");
      const row = document.querySelector(`tr[data-id="${userId}"]`);

      // Gather data from input fields and update the row
      row.querySelectorAll("td:not(.w-1/3)").forEach((td, index) => {
        const input = td.querySelector("input");
        const value = input.value;
        td.innerHTML = value;
      });

      // Toggle button visibility
      toggleButtons(userId);
    });
  });

  cancelButtons.forEach((cancelButton) => {
    cancelButton.addEventListener("click", function () {
      const userId = this.getAttribute("data-id");
      const row = document.querySelector(`tr[data-id="${userId}"]`);

      // Revert back to regular text
      row.querySelectorAll("td:not(.w-1/3)").forEach((td) => {
        const input = td.querySelector("input");
        const text = input.defaultValue; // Get the initial value
        td.innerHTML = text;
      });

      // Toggle button visibility
      toggleButtons(userId);
    });
  });

  function toggleButtons(userId) {
    const editButton = document.querySelector(
      `.edit-user-button[data-id="${userId}"]`
    );
    const submitButton = document.querySelector(
      `.submit-edit-button[data-id="${userId}"]`
    );
    const cancelButton = document.querySelector(
      `.cancel-edit-button[data-id="${userId}"]`
    );

    editButton.classList.toggle("hidden");
    submitButton.classList.toggle("hidden");
    cancelButton.classList.toggle("hidden");
  }
});
