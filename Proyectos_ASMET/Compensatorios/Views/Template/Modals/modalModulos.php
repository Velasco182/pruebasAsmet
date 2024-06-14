<!-- Modal -->
<div class="modal fade" id="modalFormModulo" tabindex="-1" role="dialog" aria-hidden="true">
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
              <form id="formModulo" name="formModulo">
                <input type="hidden" id="idModulo" name="idModulo" value="" autocomplete="off">
                <div class="form-group">
                    <label for="exampleSelect1">Menu</label>
                    <select class="form-control" id="listaMenus" name="listaMenus" required autocomplete="off">
                      <option value="">Seleccione</option>
                    </select>
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtNombre">Titulo</label>
                  <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder="Nombre del modulo" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtDescripcion">Descripción</label>
                  <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="2" placeholder="Descripción del modulo" required autocomplete="off"></textarea>
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtCodigo">Código</label>
                  <input class="form-control" id="txtCodigo" name="txtCodigo" type="text" placeholder="Código del modulo" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtIcono">Icono</label>
                  <input class="form-control" id="txtIcono" name="txtIcono" type="text" placeholder="Icono del modulo" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="control-label" for="txtNombre">Acceso</label>
                  <input class="form-control" id="txtAcceso" name="txtAcceso" type="text" placeholder="Acceso al modulo" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="exampleSelect1">¿Listar?</label>
                    <select class="form-control" id="listaMostrar" name="listaMostrar" required autocomplete="off">
                      <option value="1">Si</option>
                      <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleSelect1">Estado</label>
                    <select class="form-control" id="listStatus" name="listStatus" required autocomplete="off">
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
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
<!-- Modal -->
<div class="modal fade" id="modalViewModulo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header headerView ">
        <h5 class="modal-title" id="titleModal">Datos del Modulo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Menu</td>
              <td id="celMenu"></td>
            </tr>
            <tr>
              <td>Titulo</td>
              <td id="celTitulo"></td>
            </tr>
            <tr>
              <td>Descripción</td>
              <td id="celDesc"></td>
            </tr>
            <tr>
              <td>Código</td>
              <td id="celCodigo"></td>
            </tr>
            <tr>
              <td>Icono</td>
              <td id="celIcono"></td>
            </tr>
            <tr>
              <td>Acceso</td>
              <td id="celAcceso"></td>
            </tr>
            <tr>
              <td>¿Listar?</td>
              <td id="celListar"></td>
            </tr>
            <tr>
              <td>Estado</td>
              <td id="celEstado"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

