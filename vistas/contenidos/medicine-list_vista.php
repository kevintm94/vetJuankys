<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE MEDICINAS
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>medicine-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR MEDICINA</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>medicine-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE MEDICINAS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>medicine-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR MEDICINA</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/medicinaControlador.php";
        $ins_medicina = new medicinaControlador();

        echo $ins_medicina->paginador_medicina_controlador($pagina[1], PAGINA, $_SESSION['rol_auth'], $pagina[0],"");
    ?>
</div>

<!-- Modal agregar lote -->
<div class="modal fade" id="ModalLote" tabindex="-1" role="dialog" aria-labelledby="ModalLote" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLoteTitulo">Agregar Lote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal listar lotes -->
<div class="modal fade" id="ModalListaLote" tabindex="-1" role="dialog" aria-labelledby="ModalLote" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLoteTitulo">Listar Lote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal editar lote -->
<div class="modal fade" id="ModalEditarLote" tabindex="-1" role="dialog" aria-labelledby="ModalLote" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLoteTitulo">Editar Lote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>