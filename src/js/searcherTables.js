document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searcher");
  const rows = document.querySelectorAll(".admin-content tbody tr");

  searchInput.addEventListener("input", function () {
    const value = this.value.toLowerCase();

    rows.forEach((row) => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(value) ? "" : "none";
    });
  });
});
