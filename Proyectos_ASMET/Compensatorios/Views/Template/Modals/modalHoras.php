<!-- Modal de Horas-->
<div class="modal fade" id="modalFormHora" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Solicitud de horas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <!-- <div class="tile"> -->
            <div class="tile-body">
              <form id="formHora" name="formHora">
        
                <div id="usersDiv" name="usersDiv" class="form-row">
                  <div class="form-group col-md-6">
                    <h5 class="text-primary">Todos los campos son obligatorios.</h5>
                  </div>
                  <div  class="form-group col-md-6">
                    <label for="listaUsuarios">Usuarios</label>
                    <select class="form-control" data-live-search="true" name="listaUsuarios" id="listaUsuarios" required>
                      <option value="">Seleccione un usuario</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-row" style="display:none;">
                  <div class="form-group col-md-6">
                    <input type="hidden" id="idToma" name="idToma" value="">
                  </div>
                  <div class="form-group col-md-6">
                    <label class="control-label" for="txtEstado">Estado</label>
                    <input class="form-control" id="txtEstado" name="txtEstado" value="1">              
                  </div>
                </div>
                
                <div class="row">
                  
                  <div class="form-group col-md-12">
                    <div class="card headerRegister p-3 text-center shadow" id="txtDisponibles" name="txtDisponibles">
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <label class="control-label">Motivo de la solicitud</label>
                    <textarea class="form-control" id="txtMotivo" name="txtMotivo" type="text" placeholder="Escribe tu motivo" rows="1" cols="50" required autocomplete="off"></textarea>
                  </div>

                </div>  

                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="txtFecha">Fecha de solicitud</label>
                    <div
                        class="input-group date"
                        id="datetimepicker"
                        data-target-input="nearest"
                      >
                        <input
                          id="txtFecha"
                          name="txtFecha"
                          type="text"
                          class="form-control datetimepicker-input"
                          data-target="#datetimepicker"
                          data-toggle="datetimepicker" required  autocomplete="off"
                        />
                        <div
                          class="input-group-append"
                          data-target="#datetimepicker"
                          data-toggle="datetimepicker"
                        >
                          <div class="input-group-text"><i class="fa fa-calendar fa-lg"></i></div>
                        </div>
                      </div>
                  </div>

                  <div class="form-group col-md-6">
                    <label class="control-label">Horas a solicitar</label>
                    <input class="form-control" id="txtHoras" name="txtHoras" type="number" min="0.5" step="0.5" placeholder="Digita las horas que vas a solicitar" required="" autocomplete="off">
                  </div>
                </div>
                </div>
                <div class="modal-footer">
                  <button id="btnActionForm" class="btn btn-primary" type="submit">
                    <i class="fa fa-fw fa-lg fa-check-circle"></i>
                    <span id="btnText">Enviar solicitud</span>
                  </button>&nbsp;&nbsp;&nbsp;
                  <a class="btn btn-danger" href="#" data-dismiss="modal" >
                    <i class="fa fa-fw fa-lg fa-times-circle">
                    </i>Cancelar</a>
                </div>

              </form>
            </div>
          <!-- </div> -->
      </div>
    </div>
  </div>
</div>
<!-- Modal datos del user -->
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
            <!-- <tr>
              <td>Total de horas</td>
              <td id="celHorasTotales"></td>
            </tr> -->
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
            <tr>
              <td>Horas a solicitar</td>
              <td id="celHoras"></td>
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

