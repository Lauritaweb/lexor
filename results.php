<?php
// Recuperar valores enviados por POST
$abogadoType = $_GET['abogadoType'] ?? '';
$provinceId = $_GET['provinceId'] ?? '';
$localityId = $_GET['localityId'] ?? '';
$flag = $_GET['flag'] ?? '';

// Convertir el flag a booleano
$triggerSearch = ($flag === 'true') ? 'true' : 'false';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexor Abogados </title>
    <link rel="shortcut icon" href="./assets/img/favicon.svg" type="image/x-icon">
    <!-- css vendor -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- main css -->
    <link rel="stylesheet" href="./assets/css/main.css">
    
</head>
<body class="bg-alpha bg-dark">
   <div class="top-marquee bg-dark text-key main-font sticky-top">
        <p>
            Tu conexión directa con el abogado que necesitás           
        </p>
   </div><!-- end marquee -->
   <header>
        <!-- nav -->
        <nav class="navbar navbar-expand-lg menu">
            <div class="container-fluid">
              <a class="navbar-brand" href="index.html">
                <img src="./assets/img/Lexor-logo.svg" alt="">
              </a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <!-- btn  -->
                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./search.html">| Buscar abogado |</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#steps">| Como funciona |</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicies">| Servicios |</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faqs">| Preguntas frecuentes |</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link no-border" href="./panel/pages-register.html">| Registrarme |</a>
                    </li>
                    <li class="nav-item">
                        <a href="./panel/login.html" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
              </div>
            </div>
          </nav>
        <!-- end nav -->
    </section>
   </header>
   <!-- main -->
   <main>
    <section class="container bg-dark p-4 rounded-top mb-5 results-page">
        <div class="row mx-auto border-bottom pb-3">
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <label for="abogadoType" class="form-label d-none">Tipo de abogado</label>
                <select id="abogadoType" class="form-select">
                    <option value="" selected>Tipo de abogado...</option>
                   
                </select>
            </div>
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <label for="province" class="form-label d-none">Provincia</label>
                <select id="province" class="form-select">
                    <option value="" selected>Provincia...</option>
                </select>
            </div>
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <label for="locality" class="form-label d-none">Localidad</label>
                <select id="locality" class="form-select" disabled>
                    <option value="" selected>Localidad...</option>
                </select>
            </div>
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <button type="button" id="searchButton" class="btn btn-primary">Buscar</button>
            </div>
        </div>
        
        <div class="results" >
            <h1 class="fs-3 main-font text-key mt-3">
                Resultados de tu búsqueda
            </h1>
            <p class="text-white fs-6">
                Estos son los abogados que coinciden con tu búsqueda. Filtrá o ajustá los criterios para encontrar el profesional ideal.
            </p>
            <div class="row" id="resultsContainer">
               
            </div><!-- end row -->
        </div>
     
        
   </main>
   <!-- end main -->
    <footer class="bg-main text-white py-4">
        <div class="border-bottom container-fluid pb-3 pb-md-0">
            <div class="row aling-items-center py-1 text-start text-md-center">
                <div class="col-md-3">
                    <a href="./terminos-condiciones.html">
                        Terminos y condiciones 
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="./politica-privacidad.html">
                        Politíca de privacidad
                    </a>
                </div>
                <div class="col-md-3 mt-2 mt-md-0 mb-2 mb-md-0">
                    <a href="https://www.instagram.com/lexorabogados?igsh=cWFvenBsb2FzdDBx" target="_blank">
                        <img src="./assets/img/instagram.svg" alt="">
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="https://wa.me/5491171009227" target="_blank">
                        <img src="./assets/img/whatsapp.svg" alt="">
                    </a>
                </div>
            </div>
        </div>
        <p class="mt-3 text-white text-center">
            Copyright © 2024 Lexor Abogados
        </p>
    </footer>
    <!-- Lexi -->
    <div class="lexi">
            <a href="#">
                <img src="./assets/img/Burbuja_Lexi.png" alt="">
            </a>
    </div><!-- end lexi -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const navbarToggler = document.querySelector(".navbar-toggler");
        const navLinks = document.querySelectorAll(".nav-link");
        const navbarCollapse = document.querySelector(".navbar-collapse");

        navLinks.forEach(link => {
        link.addEventListener("click", function () {
            if (navbarCollapse.classList.contains("show")) {
            navbarToggler.click(); // Simula un clic en el botón hamburguesa para cerrar el menú
            }
            });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province');
        const localitySelect = document.getElementById('locality');
        const searchButton = document.getElementById('searchButton');
        const abogadoTypeSelect = document.getElementById('abogadoType');

        // Cargar provincias desde la base de datos
        fetch('getProvinces.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.province;
                    provinceSelect.appendChild(option);
                });
            });

        // Escuchar cambios en el selector de provincias
        provinceSelect.addEventListener('change', function () {
            const provinceId = provinceSelect.value;

            if (!provinceId) {
                localitySelect.innerHTML = '<option value="" selected>Localidad...</option>';
                localitySelect.disabled = true;
                return;
            }

            // Cargar localidades según la provincia seleccionada
            fetch(`getLocalities.php?provinceId=${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    localitySelect.innerHTML = '<option value="" selected>Localidad...</option>';
                    data.forEach(locality => {
                        const option = document.createElement('option');
                        option.value = locality.id;
                        option.textContent = locality.locality;
                        localitySelect.appendChild(option);
                    });
                    localitySelect.disabled = false;
                });
        });

        // Cargar provincias desde la base de datos
        fetch('getSpecialties.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(specialty => {
                    const option = document.createElement('option');
                    option.value = specialty.id;
                    option.textContent = specialty.description;
                    console.log(specialty.description);
                    abogadoType.appendChild(option);
                });
            });

        // Manejar el botón de búsqueda
        searchButton.addEventListener('click', function () {
            const abogadoType = abogadoTypeSelect.value;
            const provinceId = provinceSelect.value;
            const localityId = localitySelect.value;

            const requestData = {
                abogadoType,
                provinceId,
                localityId,
            };

            console.log(requestData);

            // Realizar la búsqueda
            fetch('buscar_abogados.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Resultados de búsqueda:', data);
                    // Aquí puedes mostrar los resultados en la página




            const resultsContainer = document.getElementById('resultsContainer');
            resultsContainer.innerHTML = ''; // Limpiar resultados previos

            // Verificar si hay resultados
            if (data.length > 0) {
                data.forEach(abogado => {
                    if (abogado.image === undefined || abogado.image === null)                     
                        abogado.image = 'panel/assets/img/avatar.png';
                    

                    // Crear el HTML de la tarjeta para cada resultado
                    const cardHTML = `
                        <div class="col-md-3">
                            <figure class="card">
                                <img src="${abogado.image}" class="card-img-top" alt="Imagen de ${abogado.name}">
                                <div class="card-body">
                                    <figcaption class="card-title fs-5">
                                        ${abogado.name}
                                    </figcaption>
                                    <ul class="fs-6">
                                        <li>
                                            <img src="./assets/img/briefcase-fill.svg" alt="">
                                            Especialista en ${abogado.specialty}
                                        </li>
                                        <li>
                                            <img src="./assets/img/geo-alt-fill.svg" alt="">
                                            ${abogado.province}, ${abogado.locality}
                                        </li>                                        
                                        <li class="text-key">
                                            <img src="./assets/img/clock-history.svg" alt="">
                                            Disponible consultas urgentes
                                        </li>
                                    </ul>
                                    <a href="./details.php?id=${abogado.id}" class="btn btn-key">Ver perfil</a>
                                </div>
                            </figure>
                        </div>
                    `;
                    // Insertar la tarjeta en el contenedor
                    resultsContainer.insertAdjacentHTML('beforeend', cardHTML);
                });
            } else {
                resultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
            }


                });
            });
        });

    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
// 2000 milisegundos = 2 segundos

            // Recuperar los valores de PHP
            const abogadoType = "<?php echo $abogadoType; ?>";
            const provinceId = "<?php echo $provinceId; ?>";
            const localityId = "<?php echo $localityId; ?>";
            const triggerSearch = <?php echo $triggerSearch; ?>;
            console.log("busco al tipo" + abogadoType);

            setTimeout(() => {
                document.getElementById('abogadoType').value = abogadoType;
                document.getElementById('province').value = provinceId;
                document.getElementById('locality').value = localityId;
                

                if (triggerSearch) {                    
                    // Trigger manual del evento 'change'
                    const provinceSelect = document.getElementById('province');
                    const changeEvent = new Event('change');
                    provinceSelect.dispatchEvent(changeEvent);    
                    document.getElementById('searchButton').click(); // Simular un clic en el botón de búsqueda
                  
                }
                console.log('Este mensaje aparece después de 2 segundos');
            }, 2000); 
            // Setear los valores de los desplegables
            
        });
    </script>

</body>
</html>