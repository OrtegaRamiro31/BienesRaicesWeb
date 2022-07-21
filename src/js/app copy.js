document.addEventListener('DOMContentLoaded', function() {                  //Esperar a que el documento esté cargado, tanto el html, como el js, como el css.
//Tan pronto como esté cargado el documento, ejecuta todas las funciones
    eventListeners();                                       
    darkMode();
}); 

function darkMode() {

    const prefiereDarkMode  = window.matchMedia('(prefers-color-scheme: dark)');    // Con esta linea leemos en si las preferencias del sistema del usuario está activado un modo dark/oscuro. Retorna true/false 
    // console.log(prefiereDarkMode.matches);

    // --Esto solo funciona al recargar la página, es decir, si estamos dentro de la página y cambiamos la configuración del navegador o sistema a modo oscuro, no cambiará el color de la página hasta recargar--//
    if(prefiereDarkMode.matches){                                                   // Si se obtiene verdadero (activado dark mode) agregamos la clase dark-mode al body
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');                                // Sino  se obtiene falso quitamos la clase dark-mode al body
    }

    // --Esto cambia a la par en que nosotros cambiamos la configuración del sistema de nuestros navegadores/sistemas--//
    prefiereDarkMode.addEventListener('change', function() {
        if(prefiereDarkMode.matches){                                               // Si es verdadero (dark activado) agreamos clase dark-mode
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');                            // Si es falso (dark desactivado) agreamos clase dark-mode
        }
    }); 

    const botonDarkMode = document.querySelector('.dark-mode-boton');       // Seleccionamos la clase .dark-mode-boton y la asignamos a botonDarkMode      

    botonDarkMode.addEventListener('click', function() {                    // Agregamos un addEventListener para escuchar por el eveno click de botonDarkMode
        document.body.classList.toggle('dark-mode');                        // Asignamos/quitamos la clase al BODY dependiendo si está activa o no con toggle.
    
        //--Al dar click en el icono de modo oscuro se guardará localmente la elección--//
        if (document.body.contains('dark-mode')){                           // Verificamos si el body tiene la clase dark-mode. 
            localStorage.setItem('modo-oscuro','true');                       // Almacenamos localmente. El nombre del elemento será modo-oscuro y tendrá un valor de true
        } else {
            localStorage.setItem('modo-oscuro', 'false');                     // Almacenamos localmente. El nombre del elemento será modo-oscuro y tendrá un valor de false
        }
    });



}

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');  // Seleccionamos el elemento HTML que utilizaremos
    mobileMenu.addEventListener('click', navegacionResponsive); // Agregamos evento click y llamamos la funcion navegacionResponsive
}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');   // Seleccionamos la clase navegación
    navegacion.classList.toggle('mostrar');                     // Si navegacion contiene también la clase de mostrar la quita, sino la agrega, es lo que se logra con toggle
    
}