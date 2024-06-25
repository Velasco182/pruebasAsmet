<!-- Modal Tipo compensatorio -->
<div class="modal fade" id="modalFormTipocompensatorios" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Tipo de Compensatorio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formTipoCompensatorio" name="formTipoCompensatorio" class="form-horizontal">
          <input type="hidden" id="idTipoCompensatorio" name="idTipoCompensatorio" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>

          <div class="form-row" style="display:none;">
          
            <div class="form-group col-md-6">
              <label for="txtTipoCompensatorio">Tipo Compensatorio</label>
              <input type="text" class="form-control" id="txtTipoCompensatorio" name="txtTipoCompensatorio" required value="0" autocomplete="off">
            </div>
            
          </div>
          <div class="form-row">
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txtNombreTipoCompensatorio">Nombre</label>
              <input type="text" class="form-control" id="txtNombreTipoCompensatorio" name="txtNombreTipoCompensatorio" required
                autocomplete="off">
            </div>
            <div class="form-group col-md-6">
              <label for="txtDescripcionTipoCompensatorio">Descripción</label>
              <textarea type="text" class="form-control" id="txtDescripcionTipoCompensatorio" name="txtDescripcionTipoCompensatorio" required
                autocomplete="off"></textarea>
            </div>
            <div class="form-group col-md-6">
              <label for="txtEstadoTipoCompensatorio">Estado</label>
              <select class="form-control selectpicker" id="txtEstadoTipoCompensatorio" name="txtEstadoTipoCompensatorio" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
          <div class="tile-footer">
            <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal ver Tipo compensatorio -->
<div class="modal fade" id="modalViewTipoCompensatorio" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header headerView ">
        <h5 class="modal-title" id="titleModal">Datos Adicionales Del Tipo de Compensatorio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
          <tr>
              <td>Nombre</td>
              <td id="InfoNombre"></td>
            </tr>
            <tr>
              <td>Descripcion</td>
              <td id="InfoDescripcion"></td>
            </tr>
              <td>Estado</td>
              <td id="InfoEstado"></td>
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