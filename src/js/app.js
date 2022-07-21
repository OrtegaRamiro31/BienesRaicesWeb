document.addEventListener('DOMContentLoaded', function() {                  //Esperar a que el documento esté cargado, tanto el html, como el js, como el css.
//Tan pronto como esté cargado el documento, ejecuta todas las funciones
    eventListeners();                                       
    darkMode();
    eliminarElemento();
}); 

function darkMode() {
    let darkLocal = window.localStorage.getItem('dark');        // Obtenemos el item dark del localstorage
    console.log(window.localStorage);                           // 
    if(darkLocal === 'true') {                                  // Comprobamos si su valor es verdadero
        document.body.classList.add('dark-mode');               // Si es verdadero, agregamos la clase dark-mode al body
    }
    
    document.querySelector('.dark-mode-boton').addEventListener('click', darkChange);   // Agregamos evento click que ejecuta la función darkChange. (Se da cada que clickeamos en el boton de modo oscuro)
}

//------- Guardamos con localstorage el valor elegido de si está activado el modo oscuro o no -------//
function darkChange(){
    let darkLocal = window.localStorage.getItem('dark');         // Obtenemos el item dark
    
    if(darkLocal === null || darkLocal === 'false') {            // Comprobamos si es nulo falso 
        window.localStorage.setItem('dark', true);               // Si se cumple algo de lo anterior, almacenamos en el localstorage el item dark con su valor true.
        document.body.classList.add('dark-mode');                // Agregamos la clase dark-mode al body
    } else {        
        window.localStorage.setItem('dark', false);              // Almacenamos en el localstorage el item dark con su valor false.
        document.body.classList.remove('dark-mode');             // Removemos la clase dark-mode al body
    }
}

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');  // Seleccionamos el elemento HTML que utilizaremos
    mobileMenu.addEventListener('click', navegacionResponsive); // Agregamos evento click y llamamos la funcion navegacionResponsive
}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');   // Seleccionamos la clase navegación
    navegacion.classList.toggle('mostrar');                     // Si navegacion contiene también la clase de mostrar la quita, sino la agrega, es lo que se logra con toggle
    
}

//------- Eliminamos el elemnto HTML con la clase .exito después de cierto tiempo-------//
function eliminarElemento() {
    const tiempo = 5000;
    eliminarAlertaExito(tiempo);
}

function eliminarAlertaExito(tiempo) {
    const alerta = document.querySelector('.exito');
    
    if(alerta != null) {
        setTimeout(() => {
            alerta.remove();
        }, tiempo);
    }
}

