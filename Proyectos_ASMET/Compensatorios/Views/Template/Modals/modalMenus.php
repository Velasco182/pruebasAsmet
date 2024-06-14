<!-- Modal -->
<div class="modal fade" id="modalFormMenu" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="tile">
            <div class="tile-body">
              <form id="formMenu" name="formMenu">
                <input type="hidden" id="idMenu" name="idMenu" value="" autocomplete="off">
                <div class="form-group">
                  <label class="control-label" for="txtNombre">Titulo</label>
                  <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder="Nombre del menu" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtCodigo">Código</label>
                  <input class="form-control" id="txtCodigo" name="txtCodigo" type="text" placeholder="Código del menu" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="exampleSelect1">Estado</label>
                    <select class="form-control" id="listStatus" name="listStatus" required autocomplete="off">
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtIcono">Icono</label>
                  <input class="form-control" id="txtIcono" name="txtIcono" type="text" placeholder="Icono del menu" required autocomplete="off">
                </div>
                <div class="tile-footer">
                  <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>
                  &nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#" data-dismiss="modal" ><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

