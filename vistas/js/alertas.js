const formularios_ajax = document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
    e.preventDefault();
    
    let data = new FormData(this);
    let method = this.getAttribute("method");
    let action = this.getAttribute("action");
    let tipo = this.getAttribute("data-form");

    let encabezados = new Headers();
    let config = {
        method: method,
        headers: encabezados,
        mode: 'cors',
        cache: 'no-cache',
        body: data
    }

    let texto_alerta;
    if (tipo === "save") {
        texto_alerta = "Los datos serán guardados en el sistema.";
    }else if (tipo === "delete") {
        texto_alerta = "Los datos serán eliminados en el sistema.";
    }else if (tipo === "update") {
        texto_alerta = "Los datos serán actualizados en el sistema.";
    }else if (tipo === "search") {
        texto_alerta = "Se eliminara el termino de búsqueda.";
    }else if (tipo === "loans") {
        texto_alerta = "Desea remover los datos seleccionados.";
    }else {
        texto_alerta = "Quieres realizar la operación solicitada?.";
    }

    if (tipo !== "default") {
        Swal.fire({
            type: 'question',
            title: 'Estas seguro?',
            text: texto_alerta,
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                fetch(action, config)
                .then(respuesta => respuesta.json())
                .then(respuesta => {
                    return alertas_ajax(respuesta)
                });
            }
        });
    } else {
        fetch(action, config)
        .then(respuesta => respuesta.json())
        .then(respuesta => {
            return alertas_ajax(respuesta)
        });
    }
}

formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit", enviar_formulario_ajax);
});

function alertas_ajax(alerta){
    if (alerta.Alerta === "simple") {
        Swal.fire({
            type: alerta.Tipo,
            title: alerta.Titulo,
            text: alerta.Texto,
            confirmButtonText: 'Aceptar'
        });
    }else if(alerta.Alerta === "recargar") {
        Swal.fire({
            type: alerta.Tipo,
            title: alerta.Titulo,
            text: alerta.Texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                location.reload();
            }
        });
    }else if (alerta.Alerta === "limpiar") {
        Swal.fire({
            type: alerta.Tipo,
            title: alerta.Titulo,
            text: alerta.Texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                document.querySelector(".FormularioAjax").reset();
            }
        });
    }else if (alerta.Alerta === "redireccionar") {
        window.location.href = alerta.URL;
    }
}