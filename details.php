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
        <nav class="navbar navbar-expand-xxl menu">
            <div class="container-fluid">
              <a class="navbar-brand" href="./test.html">
                <img src="./assets/img/Lexor-logo.svg" alt="">
              </a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <!-- btn  -->
                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                  </svg>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./search.html">Buscar abogado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./test.html#steps">Como funciona</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./test.html#servicies">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./test.html#faqs">Preguntas frecuentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link no-border" href="#">Registrarme</a>
                    </li>
                </ul>
              </div>
            </div>
          </nav>
        <!-- end nav -->
    </section>
   </header>
   <!-- main -->

<?php   
  require __DIR__ . '/panel/vendor/autoload.php';
  use App\Models\Affiliate;
  $affiliateModelo = new Affiliate(); 
  $idAffiliate = $_GET['id'];
  $lawyer = $affiliateModelo->get($idAffiliate);
  $id_specialization = $affiliateModelo->getSpecialties($idAffiliate);  
  $selectedSpecializations = array_column($id_specialization, 'description'); //  Extraer las descripciones de las especialidades seleccionadas y Cambia 'description' a 'id'
  $scheduleData = $affiliateModelo->getScheduleData($idAffiliate);
  
  /*
  echo "<pre>";
  var_dump($lawyer);
  */
  extract($lawyer);
   
   ?>
   <main>
    <section class="container bg-dark p-4 rounded-top mb-5 results-page">
    <!--    
    <div class="row mx-auto border-bottom pb-3">
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <label for="inputState" class="form-label d-none">Tipo de abogado</label>
                <select id="inputState" class="form-select">
                    <option selected>Tipo de abogado...</option>
                    <option>Civil</option>
                    <option>Penal</option>
                    <option>Laboral</option>
                    <option>Comercial</option>
                    <option>De Familia</option>
                    <option>Administrativo</option>
                    <option>Tributario</option>
                    <option>Ambiental</option>
                    <option>De Propiedad Intelectual</option>
                    <option>De Seguridad Social</option>
                    <option>De Contratos</option>
                    <option>Internacional</option>
                    <option>De Derecho Informático</option>
                </select>
            </div>
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <label for="inputState" class="form-label d-none">Provincia</label>
                <select id="inputState" class="form-select">
                    <option selected>Provincia...</option>
                    <option>Buenos Aires</option>
                    <option>Córdoba</option>
                    <option>Santa Fe</option>
                    <option>Mendoza</option>
                    <option>Entre Ríos</option>
                    <option>Salta</option>
                    <option>Misiones</option>
                    <option>Chaco</option>
                    <option>Corrientes</option>
                    <option>Santiago del Estero</option>
                    <option>Tucumán</option>
                    <option>San Juan</option>
                    <option>Jujuy</option>
                </select>
            </div> 
            <div class="col-md-3 ps-0 mt-2 mt-md-0">
                <label for="inputState" class="form-label d-none">Localidad</label>
                <select id="inputState" class="form-select">
                    <option selected>Localidad...</option>
                    <option>La Plata</option>
                    <option>Mar del Plata</option>
                    <option>Bahía Blanca</option>
                    <option>San Nicolás</option>
                    <option>Tandil</option>
                    <option>Quilmes</option>
                    <option>Morón</option>
                    <option>Avellaneda</option>
                    <option>San Isidro</option>
                    <option>Campana</option>
                </select>
            </div> 
            
            
            <div class="col-md-2 ps-0 mt-2 mt-md-0">
                <button class="btn-search btn-results">
                    <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-search me-1" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                    <span class="d-block d-md-none">
                        Buscar
                    </span>
                </button>
            </div>           
        </div>
        -->
        <!-- end row -->
        <div class="results pb-5">
            <h1 class="fs-3 main-font text-key mt-3">
                
                <?php if($gender == "F") 
                    echo "Dra."; 
                else 
                    echo "Dr.";
                echo $name . ' ' . $last_name; 
                ?>
            </h1>
            <section class="details">
            <?php 
            if ($url_file_image != null)
                $url_file_image = 'panel/afiliado/uploads/' . $url_file_image;
            else     
                $url_file_image = "panel/assets/img/avatar.png";
                    
            ?>
                <img src="<?= $url_file_image ?>" class="card-img-top" alt="<?= $name . ' ' . $last_name  ?>">
                <ul class="fs-6 d-flex text-white w-100">
                    <li>
                        <img src="./assets/img/briefcase-fill.svg" alt="">
                        <?php  foreach ($selectedSpecializations as $specialtiesActual){
                            echo " $specialtiesActual <br>";
                        } ?>
                    </li>
                    <li>
                        <img src="./assets/img/geo-alt-fill.svg" alt="">
                        <?= $province . ', ' . $locality;  ?>
                    </li>
                    <li>
                    
                        <img src="./assets/img/clock-history.svg" alt="">
                        <?php 
                    //  var_dump($scheduleData);
                    //  die;
                        foreach($scheduleData as $key => $value){
                        echo "$key: ";
                        if ($value['closed'])
                            echo "Cerrado";
                        else
                            echo $value['start_time'] . ' a ' . $value['end_time'];
                        echo "<br>";
                        }

                        ?>
                    </li>
                  
                    <li>
                        <img src="./assets/img/geo-alt-fill.svg" alt="">
                        <?= $consultantType;  ?>
                    </li>
                    <li>
                        <img src="./assets/img/clock-history.svg" alt="">
                        Disponible consultas urgentes
                    </li>
                </ul>
            </section>
            <h2 class="fs-5 text-white fw-bold main-font">Descripción</h2>
            <p class="fs-6 lh-3 text-white">
                <?= $about_me; ?>
            </p>  
            <!--
            <h2 class="fs-5 text-white fw-bold main-font">Información adicional</h2>
            <p class="fs-6 lh-3 text-white">
                Lorem ipsum dolor
            </p>
            -->
            <div class="d-block d-md-flex mt-3">
                <a href="https://wa.me/<?= $phone ?>" class="btn btn-key-outline me-2 mb-2 mb-md-0" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp me-1" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                      Contacto por whatsapp
                </a>
                <a href="mailto:<?= $email ?>" class="btn btn-key-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill me-1" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    Contacto por email
                </a> 
            </div>     
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

</body>
</html>