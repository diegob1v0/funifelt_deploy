document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector("#searchUser");
  const resultsContainer = document.querySelector("#searchResults");
  const selectedList = document.querySelector("#selectedAdmins");
  const adminsInput = document.querySelector("#adminsInput");

  let selectedAdmins = [];

  if (typeof assignedAdmins !== "undefined" && Array.isArray(assignedAdmins)) {
    selectedAdmins = assignedAdmins;
    updateSelected();
  }

  searchInput.addEventListener("input", async (e) => {
    const query = e.target.value.trim();
    if (query.length < 2) {
      resultsContainer.innerHTML = "";
      return;
    }

    const res = await fetch(
      `/api/users/search?query=${encodeURIComponent(query)}`
    );
    const users = await res.json();

    resultsContainer.innerHTML = "";
    users.forEach((user) => {
      const item = document.createElement("div");
      item.innerHTML = `<strong>${user.name}</strong><br><small>${user.email}</small>`;
      item.addEventListener("click", () => selectUser(user));
      resultsContainer.appendChild(item);
    });
  });

  function selectUser(user) {
    if (selectedAdmins.find((u) => u.id === user.id)) return;

    selectedAdmins.push(user);
    updateSelected();
    resultsContainer.innerHTML = "";
    searchInput.value = "";
  }

  function updateSelected() {
    selectedList.innerHTML = "";
    selectedAdmins.forEach((user) => {
      const li = document.createElement("li");
      li.innerHTML = `
        <strong>${user.name}</strong>
        <small>${user.email}</small>
        <button type="button" data-id="${user.id}">&times;</button>
      `;
      li.querySelector("button").addEventListener("click", () => {
        selectedAdmins = selectedAdmins.filter((u) => u.id !== user.id);
        updateSelected();
      });
      selectedList.appendChild(li);
    });

    adminsInput.value = selectedAdmins.map((u) => u.id).join(",");
  }
});
