<div class="modal fade" tabindex="-1" role="dialog" id="confirmDeleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("¿Estás seguro?") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __("¡Esta acción no se puede deshacer!") }}</p>
                <p id="message"></p>
                <form id="deleteForm" action="" method="post">
                    @method('DELETE')
                    @csrf()
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> {{ __("Cerrar") }}
                </button>
                <button type="submit" form="deleteForm" class="btn btn-danger">
                    <i class="fa fa-trash"></i> {{ __("Eliminar") }}
                </button>
            </div>
        </div>
    </div>
</div>
