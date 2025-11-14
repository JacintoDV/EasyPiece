let enDetalle = false; 

document.getElementById("imgNoti").addEventListener("click", () => {
  if (!enDetalle) {
    window.location.href = "Notificaciones.php"; // página de detalle
  } else {
    window.location.href = "Pagina_principal.php";
  }
  enDetalle = !enDetalle;
});
