<!-- views/producto/index.php -->
<div class="row mb-4">
    <div class="col">
        <h2>Listado de tareas por realizar</h2>
    </div>
    <div class="col text-end">
        <a href="<?= BASE_URL ?>/reporte/pdf" class="btn btn-secondary">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
        <a href="<?= BASE_URL ?>/reporte/excel" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Exportar Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fas fa-plus"></i> Nueva Tarea
        </button>
    </div>
</div>

<!-- Filtros -->
<div class="shadow-none p-3 mb-5 bg-body-tertiary rounded">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control"
                        id="searchProduct"
                        placeholder="Buscar tarea por titulo..."
                        onkeyup="searchProducts(event)">
                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Titulo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha de vencimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <!-- Los productos se cargarán aquí dinámicamente -->
        </tbody>
    </table>
</div>

<!-- Paginación -->


<!-- Modal para Crear/Editar Producto -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Titulo</label>
                        <input type="text" class="form-control" id="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" aria-label="Default select example">
                                   <option selected value="pendiente">pendiente</option>
                                   <option value="completada">completada</option>
                                </select>
                            </div>
                    </div>
                    <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" aria-label="Default select example">
                                   <option selected value="baja">baja</option>
                                   <option value="media">media</option>
                                   <option value="alta">alta</option>
                                </select>
                            </div>
                            <div class="mb-3">
                        <label for="fecha_vencimiento" class="form-label">fecha de vencimiento</label>
                        <input type="text" class="form-control" id="fecha_vencimiento" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen" accept="image/*">
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="almacenar()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar esta tarea?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="id_tarea">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vista Previa -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="previewImage" src="" alt="Imagen de la Tarea" class="img-fluid rounded">
                </div>
                <dl class="row">
                    <dt class="col-sm-3">Titulo:</dt>
                    <dd class="col-sm-9" id="previewTitulo"></dd>

                    <dt class="col-sm-3">Descripción:</dt>
                    <dd class="col-sm-9" id="previewDescription"></dd>

                    <dt class="col-sm-3">Estado:</dt>
                    <dd class="col-sm-9" id="previewEstado"></dd>

                    <dt class="col-sm-3">Prioridad:</dt>
                    <dd class="col-sm-9" id="previewPrioridad"></dd>

                    <dt class="col-sm-3">Creado:</dt>
                    <dd class="col-sm-9" id="previewCreated"></dd>

                    <dt class="col-sm-3">Actualizado:</dt>
                    <dd class="col-sm-9" id="previewUpdated"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    obtenerTarea();
});
</script>