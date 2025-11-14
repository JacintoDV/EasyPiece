document.addEventListener("click", (e) => {
  const notificacion = e.target.closest(".contenedor-notificaciones");
  if (!notificacion) return;

  notificacion.classList.toggle("expanded");
  notificacion.classList.toggle("collapsed");
});



