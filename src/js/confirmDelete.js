document.addEventListener("DOMContentLoaded", () => {
  const langCode = navigator.language.startsWith("es") ? "es" : "en";
  const deleteButtons = document.querySelectorAll('button[type="button"]');

  const messages = {
    es: {
      title: "¿Eliminar aplicación?",
      text: "Esta acción no se puede deshacer.",
      confirm: "Eliminar",
      cancel: "Cancelar",
    },
    en: {
      title: "Delete app?",
      text: "This action cannot be undone.",
      confirm: "Delete",
      cancel: "Cancel",
    },
  };

  const t = messages[langCode];
  deleteButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const form = button.closest("form");

      Swal.fire({
        title: t.title,
        text: t.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: t.confirm,
        cancelButtonText: t.cancel,
        customClass: {
          popup: "swal-popup",
          title: "swal-title",
          htmlContainer: "swal-text",
          confirmButton: "swal-confirm",
          cancelButton: "swal-cancel",
        },
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
