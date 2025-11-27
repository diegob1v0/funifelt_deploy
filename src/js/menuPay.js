document.addEventListener("DOMContentLoaded", () => {
  eventListeners();

  const type = document.getElementById("type");
  if (type) {
    menuPay(type.value);
  }
});

function eventListeners() {
  const type = document.getElementById("type");
  if (!type) return;

  type.addEventListener("change", () => {
    menuPay(type.value);
  });
}

function menuPay(value) {
  const price = document.getElementById("group-price");
  const priceInput = document.getElementById("price");
  if (!price || !priceInput) return;

  if (value === "pay") {
    price.style.display = "flex";
  } else {
    price.style.display = "none";
    priceInput.value = "";
  }
}
