<!-- Modal -->
<div class="modal fade" id="modalFormHora" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Solicitu de horas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="tile">
            <div class="tile-body">
              <form id="formHora" name="formHora">
                <input type="hidden" id="idHora" name="idHora" value="">
                
                <div class="form-group" style="display:none;">
                <label class="control-label" for="txtEstado">Estado</label>
                <input class="form-control" id="txtEstado" name="txtEstado" value="1">              
                </div>

                <div class="form-group">
                  <label class="control-label">Motivo de la solicitud</label>
                  <input class="form-control" id="txtMotivo" name="txtMotivo" type="text" placeholder="Escribe tu motivo" required="" autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="control-label">Fecha de solicitud</label>
                  <input class="form-control" id="txtFecha" name="txtFecha" type="date" required="" autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="control-label">Horas a solicitar</label>
                  <input class="form-control" id="txtHoras" name="txtHoras" type="number" min="1" placeholder="Digita las horas que vas a solicitar" required="" autocomplete="off">
                </div>
              </div>
                <div class="tile-footer">
                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Enviar solicitud</span></button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#" data-dismiss="modal" ><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
            </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalViewHora" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header headerView ">
        <h5 class="modal-title" id="titleModal">Datos del Funcionario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Nombres</td>
              <td id="celNombres"></td>
            </tr>
            <tr>
              <td>Apellidos</td>
              <td id="celApellidos"></td>
            </tr>
            <tr>
              <td>Total de horas</td>
              <td id="celHorasTotales"></td>
            </tr>
            <tr>
              <td>Correo</td>
              <td id="celCorreo"></td>
            </tr>
            <tr>
              <td>Motivo de la solicitud</td>
              <td id="celMotivo"></td>
            </tr>
            <tr>
              <td>Fecha de la solicitud</td>
              <td id="celFecha"></td>
            </tr>
            <!-- <tr>
              <td>Usuario <br> Contraseña</td>
              <td id="celUsuario"></td>
            </tr> -->
            <tr>
              <td>Horas a solicitar</td>
              <td id="celHoras"></td>
            </tr>
            <!-- <tr>
              <td>Rol</td>
              <td id="celTipoUsuario"></td>
            </tr> -->
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

