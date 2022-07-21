<?php 
    require 'includes/funciones.php';
    incluirTemplate('header');
?>

    <section class="contenedor seccon">
        <h1>Conoce Sobre Nosotros</h1>
        <div class="contenedor-nosotros">
            <div class="contenedor-nosotros-imagen">
                <picture>
                    <source srcset="build/img/nosotros.webp" type="image/webp">
                    <source srcset="build/img/nosotros.jpg" type="image/jpeg">
                    <img src="build/img/nosotros.jpg" alt="Imagen Nosotros" loading="lazy">
                </picture>
            </div>
            <div class="contenedor-nosotros-experiencia">
                <blockquote>
                    25 Años de experiencia
                </blockquote>
                <p>
                Dolore anim sint cillum commodo do ut velit ea mollit tempor dolor. Voluptate exercitation amet excepteur ad excepteur esse in occaecat nostrud. Qui ullamco cillum pariatur minim id.
                Ullamco anim ipsum quis cillum Lorem officia. Minim irure laborum ullamco sit id mollit ea. Nulla Lorem ut dolor sint. Eu commodo deserunt laborum exercitation id laboris. Ullamco labore ut consequat non sunt velit. Minim qui officia id consequat et commodo aliquip irure adipisicing id aute. Lorem fugiat ut veniam duis minim adipisicing eiusmod ad amet laborum aliqua non.
                </p>
                <p>
                Dolore anim sint cillum commodo do ut velit ea mollit tempor dolor. Voluptate exercitation amet excepteur ad excepteur esse in occaecat nostrud. Qui ullamco cillum pariatur minim id.
                Ullamco anim ipsum quis cillum Lorem officia. Minim irure laborum ullamco sit id mollit ea. Nulla Lorem ut dolor sint. Eu commodo deserunt laborum exercitation id laboris. Ullamco labore ut consequat non sunt velit. Minim qui officia id consequat et commodo aliquip irure adipisicing id aute. Lorem fugiat ut veniam duis minim adipisicing eiusmod ad amet laborum aliqua non.
                </p>
            </div>
        </div>
        <div class="iconos-nosotros">
            <div class="icono">
                <img src="build/img/icono1.svg" alt="Icono seguridad" loading="lazy">
                <h3>Seguridad</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur ut tempora consequatur consectetur nulla praesentium alias, repellat ad debitis doloribus maxime, nihil minima? Temporibus porro officia nostrum eveniet, molestias obcaecati.</p>
            </div>
            <div class="icono">
                <img src="build/img/icono2.svg" alt="Icono Precio" loading="lazy">
                <h3>Precio</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur ut tempora consequatur consectetur nulla praesentium alias, repellat ad debitis doloribus maxime, nihil minima? Temporibus porro officia nostrum eveniet, molestias obcaecati.</p>
            </div>
            <div class="icono">
                <img src="build/img/icono3.svg" alt="Icono Tiempo" loading="lazy">
                <h3>A Tiempo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur ut tempora consequatur consectetur nulla praesentium alias, repellat ad debitis doloribus maxime, nihil minima? Temporibus porro officia nostrum eveniet, molestias obcaecati.</p>
            </div>
        </div>
    </section>

<?php 
    incluirTemplate('footer');
?>