document.addEventListener("click", (e) => {
  const notificacion = e.target.closest(".contenedor-notificaciones");
  if (!notificacion) return;

  notificacion.classList.toggle("expanded");
  notificacion.classList.toggle("collapsed");
});

document.addEventListener("DOMContentLoaded", () => {
    const scrollNotificaciones = document.querySelector('.scroll-notificaciones');

    console.log("Intentando conectar con el Service...");

    fetch('../services/leerNotificaciones.php')
        .then(response => {
            console.log("Respuesta recibida del servidor:", response);
            return response.json();
        })
        .then(data => {
            console.log("Datos convertidos a JSON:", data);

            if (data.success) {
                // Si la base de datos está vacía
                if (data.datos.length === 0) {
                    console.log("No hay notificaciones en la DB para este usuario.");
                    scrollNotificaciones.innerHTML = `
                        <div class="contenedor-notificaciones collapsed">
                            <h2 class="titulo-notificacion">Bandeja vacía</h2>
                            <p class="info-notificacion">
                                No tienes notificaciones registradas.
                            </p>
                        </div>`;
                    return;
                }

                // Si hay datos, limpiamos los de prueba y renderizamos los reales
                console.log("Renderizando " + data.datos.length + " notificaciones...");
                scrollNotificaciones.innerHTML = ""; 

                data.datos.forEach(n => {
                    const item = `
                        <div class="contenedor-notificaciones collapsed" data-id="${n.id}">
                            <h2 class="titulo-notificacion">${n.tipo}</h2>
                            <p class="info-notificacion">
                                ${n.mensaje}
                                <br><br>
                                <small>ID: ${n.id} | Fecha: ${n.fecha_creacion}</small>
                            </p>
                        </div>`;
                    scrollNotificaciones.innerHTML += item;
                });
            } else {
                console.error("El Service respondió con success: false", data.mensaje);
            }
        })
        .catch(error => {
            console.error("ERROR FATAL en el Fetch:", error);
        });

  document.getElementById("buscador").addEventListener("keyup", (e) => {
      const texto = e.target.value.toLowerCase();
      const notificaciones = document.querySelectorAll(".contenedor-notificaciones");

      notificaciones.forEach(notificacion => {
          const nombreNotificacion = notificacion.querySelector(".titulo-notificacion").textContent.toLowerCase();
          
          if (nombreNotificacion.includes(texto)) {
              notificacion.style.display = "block"; // Se muestra si coincide
          } else {
              notificacion.style.display = "none";  // Se oculta si no coincide
          }
      });
  });
});