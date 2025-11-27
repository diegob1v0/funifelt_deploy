document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("paypal-button-container");
  const btnComprar = document.getElementById("btn-shop");

  // Si no existe, es app gratis
  if (!container) return;

  // 1. Usuario hace clic en tu botón
  btnComprar.addEventListener("click", () => {
    btnComprar.style.display = "none"; // ocultar tu botón
    container.style.display = "block"; // mostrar PayPal
  });

  // 2. Configurar PayPal
  const price = container.dataset.price;
  const appName = container.dataset.name;
  const appId = container.dataset.id;
  const userID = container.dataset.user;

  paypal
    .Buttons({
      createOrder: function (data, actions) {
        return actions.order.create({
          purchase_units: [
            {
              amount: { value: price },
              description: `Compra de la app ${appName}`,
            },
          ],
        });
      },

      onApprove: function (data, actions) {
        actions.order.capture().then(function (details) {
          const payerEmail = details.payer.email_address;
          const currency =
            details.purchase_units[0].payments.captures[0].amount.currency_code;
          const amount =
            details.purchase_units[0].payments.captures[0].amount.value;

          fetch("/api/pays", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              order_id: data.orderID,
              payer_id: data.payerID,
              app_id: appId,
              user_id: userID,
              payer_email: payerEmail,
              amount: amount,
              currency: currency,
            }),
          })
            .then((r) => r.json())
            .then((response) => {
              if (response.success) {
                window.location.reload();
              } else {
                alert("Error registrando el pago.");
              }
            });
        });
      },
    })
    .render("#paypal-button-container");
});
