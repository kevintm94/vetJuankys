<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CURSOS
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>course-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CURSO</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>course-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CURSOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>course-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CURSO</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/cursoControlador.php";
        $ins_curso = new cursoControlador();

        echo $ins_curso->paginador_curso_controlador($pagina[1], PAGINA, $_SESSION['rol_auth'], $pagina[0],"");
    ?>
</div>