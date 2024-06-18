<?php
require_once(__DIR__.'/../php/vendor/autoload.php');

use Vendor\Whatsappweb\auth;
use Vendor\Whatsappweb\userData;
use Vendor\Whatsappweb\DBConnection;

session_start();

$VerifySession = auth::verify($_COOKIE['auth'] ?? NULL);

if (!$VerifySession['success']) {
    header('Location: index.html?sesion=expired');
    exit();
}else{
    $userId = $VerifySession['userId'];
    
    $dbConnection = new DBConnection();
    $connection = $dbConnection->getConnection();
    // Crear una instancia de userData
    $userDataInstance = new userData($connection);
    $GetCurrentUserData = $userDataInstance->GetCurrentUserData($userId);


    if (!$GetCurrentUserData['success']) {
        echo 'Error al obtener los datos del usuario';
    }else{
        $userName = $GetCurrentUserData['userName'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css" rel="stylesheet">  
    <!--<link rel="stylesheet" href="assets/css/dashboard.css">-->
    <title>Inicio</title>
</head>
<body>

    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="assets/img/whatsapp.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-mid">
            Envio de WhatsApp (Baileys)
          </a>
        </div>
    </nav>

    <nav class="sidebar" id="nav">

        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px; min-height: calc(100vh);">
            <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item py-1">
                <a href="dashboard.php" class="btn btn-primary d-flex align-items-center justify-content-start"><i class="bi bi-house-fill px-3"></i>Inicio</a>
            </li>
            <!--<li class="py-1">                 
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                <i class="bi bi-person-badge-fill px-3"></i>Alumnos
                </button>                
                <div class="collapse" id="collapseStudents">
                    <div class="card card-body">
                        <div class="list-group">                            
                            <a href="alumnos/altas.php" class="list-group-item list-group-item-action">Agregar</a>
                            <a href="alumnos.php" class="list-group-item list-group-item-action">Lista</a>
                            <a href="alumnos/usuarios.php" class="list-group-item list-group-item-action">Usuarios</a>
                        </div>                       
                    </div>
                </div>                                                    
            </li>
            <li class="py-1">
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTeachers" aria-expanded="false" aria-controls="collapseTeachers">
                <i class="bi bi-person-workspace px-3"></i>Profesores
                </button>                
                <div class="collapse" id="collapseTeachers">
                    <div class="card card-body">
                        <div class="list-group">                            
                            <a href="profesores/altas.php" class="list-group-item list-group-item-action">Agregar</a>
                            <a href="profesores.php" class="list-group-item list-group-item-action">Lista</a>
                            <a href="profesores/usuarios.php" class="list-group-item list-group-item-action">Usuarios</a>
                        </div>                          
                    </div>
                </div> 
            </li>
            <li class="py-1">
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGroups" aria-expanded="false" aria-controls="collapseGroups">
                <i class="bi bi-person-badge-fill px-3"></i>Grupos
                </button>                
                <div class="collapse" id="collapseGroups">
                    <div class="card card-body">
                        <div class="list-group">                            
                            <a href="grupos/altas.php" class="list-group-item list-group-item-action">Agregar</a>
                            <a href="grupos.php" class="list-group-item list-group-item-action">Lista</a>
                        </div>                          
                    </div>
                </div>  
            </li>
            <li class="py-1">                    
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCarreers" aria-expanded="false" aria-controls="collapseCarreers">
                <i class="bi bi-mortarboard-fill px-3"></i>Carreras
                </button>                
                <div class="collapse" id="collapseCarreers">
                    <div class="card card-body">
                        <div class="list-group">                            
                            <a href="carreras/altas.php" class="list-group-item list-group-item-action">Agregar</a>
                            <a href="carreras.php" class="list-group-item list-group-item-action">Lista</a>
                        </div>                          
                    </div>
                </div>  
            </li>
            <li class="py-1">
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubjects" aria-expanded="false" aria-controls="collapseSubjects">
                <i class="bi bi-book-half px-3"></i>Materias
                </button>                
                <div class="collapse" id="collapseSubjects">
                    <div class="card card-body">
                        <div class="list-group">                            
                            <a href="materias/altas.php" class="list-group-item list-group-item-action">Agregar</a>
                            <a href="materias.php" class="list-group-item list-group-item-action">Lista</a>
                        </div>                          
                    </div>
                </div>  
            </li>
            <li class="py-1">
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                <i class="bi bi-person-lines-fill px-3"></i>Usuarios
                </button>                
                <div class="collapse" id="collapseUsers">
                    <div class="card card-body">
                        <div class="list-group">                            
                            <a href="#" class="list-group-item list-group-item-action">Agregar</a>
                            <a href="#" class="list-group-item list-group-item-action">Lista</a>
                        </div>                          
                    </div>
                </div>  

            </li>-->
            </ul>
            <hr>
            <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $userName ?></strong>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                <li><a class="dropdown-item" href="#">Cerrar Sesión</a></li>
            </ul>
            </div>
        </div>
    </nav>
      
    <section class="home" id="home">           
        <div class="text">Inicio</div>
        <hr class="border-top border-2 border-dark mx-auto w-25">

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">  
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="studentsList" data-bs-toggle="tab" data-bs-target="#sendIndividual" type="button" role="tab" aria-controls="sendIndividual" aria-selected="true">Mensaje individual</button>
                        </li>
                        <li class="nav-item">
                        <button class="nav-link" id="studentsList" data-bs-toggle="tab" data-bs-target="#sendExcel" type="button" role="tab" aria-controls="sendExcel" aria-selected="true">Excel</button>
                        </li>                       
                        </ul>
                    </div>                  
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            
                            <div class="tab-pane fade show active" id="sendIndividual" role="tabpanel" aria-labelledby="sendIndividual" tabindex="0">
                                <h4 class="card-title py-3">Envio por número de teléfono</h4>
                        
                                <form id="sendWhatsApp">
                                    <div class="row g-2">
                                        <div class="col-md py-1">
                                            <div class="form-floating">
                                            <select class="form-select" id="countryCode" name="countryCode" >
                                                <option selected value="0">País</option>  
                                                <option value="521">México</option>
                                                <option value="011">USA</option>
                                                <option value="571">Colombia</option>
                                                <option value="541">Argentina</option>
                                            </select>
                                            <label for="floatingSelect">Selecciona</label>
                                            </div>
                                            <label id="countryCode-error" class="error text-bg-danger" for="countryCode" style="font-size: 12px; border-radius: 10px; padding: 0px 5px; display:none;"></label>                    
                                        </div>
                                        <div class="col-md py-1">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Número de teléfono">
                                                <label for="floatingInput">Número de teléfono</label>
                                            </div>
                                            <div>
                                                <p class="py-1">                   
                                                <label id="phoneNumber-error" class="error text-bg-danger" for="phoneNumber" style="font-size: 12px; border-radius: 10px; padding: 0px 5px; display:none;"></label>
                                                </p>
                                            </div>    
                                        </div>                                                          
                                    </div>
                                    <div class="row g-2">
                                    <div class="col-md py-1">
                                            <div class="form-floating">
                                                <textarea class="form-control" id="message" name="message" placeholder="Número de teléfono"></textarea>
                                                <label for="floatingInput">Mensaje</label>
                                            </div> 
                                            <div>
                                                <p class="py-1">                   
                                                <label id="message-error" class="error text-bg-danger" for="message" style="font-size: 12px; border-radius: 10px; padding: 0px 5px; display:none;"></label>
                                                </p>
                                            </div>                                    
                                        </div>        
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md py-1">
                                            <button type="submit" class="btn btn-primary">Enviar</button>
                                        </div>
                                    </div>
                                </form>  
                            </div>   
                            
                            <div class="tab-pane fade show" id="sendExcel" role="tabpanel" aria-labelledby="sendExcel" tabindex="0">
                                <h4 class="card-title py-3">Enviar desde Excel</h4>
                                <form id="sendExcelData" enctype="multipart/form-data">
                                    <div class="row g-2">                                
                                        <div class="col-md">    
                                            <label for="formFile" class="form-label">Sube tu archivo Excel</label>
                                            <input class="form-control" name="file" type="file" id="excelData" accept=".xlsx,.xls,.csv">                                                                                                                       
                                        </div>                                                                                
                                    </div>
                                    <div class="col-md py-3">
                                        <button type="submit" class="btn btn-primary">Cargar</button>
                                    </div>
                                </form>

                                <div class="row g-2">
                                    <div class="col-md py-1">  
                                        <h5 class="card-title py-3">Tabla de números</h5>                                       
                                        <div class="table-responsive" style="background: aliceblue; border-radius: 10px; padding: 10px;">                                            
                                            <table class="table table-striped" id="phonesExcel">
                                                <thead>
                                                    <tr>             
                                                        <th class="text-center">Nombre</th>                           
                                                        <th class="text-center">Teléfono</th>
                                                        <th class="text-center">Selección</th>
                                                    </tr>
                                                </thead>                                                    
                                                <tbody>                                                    
                                                </tbody>    
                                            </table>
                                        </div>
                                        <div class="row g-2 py-3">                                
                                            <div class="col-md py-3" id="messageExcel">  
                                                <h5>Mensaje a enviar</h5>                                           
                                                <textarea class="form-control" id="messageExcelArea" name="messageExcel" placeholder="Mensaje" rows="3"></textarea>
                                            </div>
                                        </div> 
                                        <div class="row g-2 py-2" id="mediaSpace">                                
                                            <div class="col-md py-2">  
                                                <h5>Archivo multimedia</h5>                                           
                                                <input class="form-control" type="file" id="whatsappFile" name="whatsappFile">
                                            </div>
                                        </div> 
                                        <div class="row g-2">                                
                                            <div class="col-md py-3 d-flex justify-content-between">
                                                <button type="button" id="sendExcelMen" class="btn btn-success">Enviar selección</button>
                                                <button type="button" id="clearexcel" class="btn btn-warning">Limpiar tabla</button>
                                            </div>
                                        </div>                                                                             
                                    </div>
                            </div>

                        </div>
                    </div>
                </div> 
            </div>
        </div>

    </section>

</body>
</html>

<!-- Boostrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" integrity="sha256-J8ay84czFazJ9wcTuSDLpPmwpMXOm573OUtZHPQqpEU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- js-->
<script type="module" src="assets/js/dashboard.js?v=2"></script>

<!-- validate js-->
<script src="assets/js/validate.js"></script>

<!-- datables -->
<script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>