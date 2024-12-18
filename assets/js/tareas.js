async function obtenerTarea() {
    try {
        const respuesta = await fetch('tareas/obtener-tarea');
        const resultado = await respuesta.json();
        
        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        const productos = resultado.data;
        //console.log(tareas);

        const tbody = document.getElementById('productsTableBody');
        tbody.innerHTML = '';
        
        productos.forEach(product => {
            //console.log(product);
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${product.id_tarea}</td>
                <td>
                    ${product.imagen 
                        ? `<img src="assets/uploads/${product.imagen}" 
                            alt="${product.titulo}" 
                            class="img-thumbnail" 
                            style="max-width: 50px; max-height: 50px;">`
                        : '<span class="text-muted">Sin imagen</span>'}
                </td>
                <td>${product.titulo}</td>
                <td>${product.descripcion || '<span class="text-muted">Sin descripción</span>'}</td>
                <td>${product.estado}</td>
                <td>${product.prioridad}</td>
                <td>${product.fecha_vencimiento}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="mostarDataEditar(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${product.id_tarea})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        showAlert('error', 'Error al cargar las tareas: d ' + error.message);
    }
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.appendChild(alertDiv);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}



////GUARDAR TAREAS////////


async function guardarTarea(){
    try {
        const formData = new FormData();
        const titulo = document.getElementById('titulo').value;
        const descripcion = document.getElementById('descripcion').value;
        const estado = document.getElementById('estado').value;
        const prioridad = document.getElementById('prioridad').value;
        const fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
        const imagenFile = document.getElementById('imagen').files[0];

        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('estado', estado);
        formData.append('prioridad', prioridad);
        formData.append('fecha_vencimiento', fecha_vencimiento);

        if (imagenFile) {
            formData.append('imagen', imagenFile);
        }

        // const url = editingProductId ? 'products/update' : 'products/create';
        // if (editingProductId) {
        //     formData.append('id', editingProductId);
        // }

        const response = await fetch('tareas/guardar-tarea', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'error') {
            throw new Error(result.message);
        }

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showAlert('success', result.message);

        // Recargar la lista de productos
        obtenerTarea();

        // Resetear el formulario
        resetForm();        
    } catch (error) {
        showAlert('error', error.message);
    }
}


////ACTUALIZAR TAREAS////////



async function actualizarTarea(){
    try {
        const formData = new FormData();
        const id = document.getElementById('productId').value;
        const titulo = document.getElementById('titulo').value;
        const descripcion = document.getElementById('descripcion').value;
        const estado = document.getElementById('estado').value;
        const prioridad = document.getElementById('prioridad').value;
        const fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
        const imagenFile = document.getElementById('imagen').files[0];

        formData.append('id', id);
        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('estado', estado);
        formData.append('prioridad', prioridad);
        formData.append('fecha_vencimiento', fecha_vencimiento);

        if (imagenFile) {
            formData.append('imagen', imagenFile);
        }

        // const url = editingProductId ? 'products/update' : 'products/create';
        // if (editingProductId) {
        //     formData.append('id', editingProductId);
        // }

        const response = await fetch('tareas/actualizar-tarea', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'error') {
            throw new Error(result.message);
        }

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showAlert('success', result.message);

        // Recargar la lista de productos
        obtenerTarea();

        // Resetear el formulario
        resetForm();        
    } catch (error) {
        showAlert('error', error.message);
    }
}


////ELIMINAR TAREAS////////



async function eliminarTarea(id) {
    console.log('entro aqui para eliminar', id);
    
    try {
        if (!confirm('¿Está seguro de que desea eliminar?')) {
            return;
        }

        const respuesta = await fetch('tareas/eliminar-tarea', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                id_tarea:id,
            })
        });

        const resultado = await respuesta.json();

        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        showAlert('success', resultado.message);
        //actualizar la lista o tabla
        obtenerTarea();

    } catch (error) {
        showAlert('error', error.message);
    }
}






function mostarDataEditar(tarea){
     console.log(tarea);

    document.getElementById('productId').value = tarea.id_tarea;
    document.getElementById('titulo').value = tarea.titulo;
    document.getElementById('descripcion').value = tarea.descripcion;
    document.getElementById('estado').value = tarea.estado;
    document.getElementById('prioridad').value = tarea.prioridad;
    document.getElementById('imagen').value = '';
    document.getElementById('fecha_vencimiento').value = tarea.fecha_vencimiento;

    
    // Actualizar título del modal
    document.getElementById('modalTitle').textContent = 'Editar Tarea';
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();

}





function almacenar(){
    if(document.getElementById('productId').value){
        //actualizar producto
        actualizarTarea();
    }else{
        //crear producto
        guardarTareas();
    }
}




function resetForm() {
    document.getElementById('productId').value = '';
    document.getElementById('productForm').reset();
    document.getElementById('modalTitle').textContent = 'Nueva Tarea';
}