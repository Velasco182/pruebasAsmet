<!-- Modal -->
<div class="modal fade" id="modalFormCompensatorio" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Funcionario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="formCompensatorio" name="formCompensatorio" class="form-horizontal">
              <input type="hidden" id="idCompensatorio" name="idCompensatorio" value="">
              <!-- <input type="" id="idFuncionario" name="idFuncionario" value=""> -->
              <p class="text-primary">Todos los campos son obligatorios.</p>

              <div class="form-row" style="display:none;">
              
                <div class="form-group col-md-6">
                  <label for="txtCompensatorio">Compensatorio</label>
                  <input type="text" class="form-control" id="txtCompensatorio" name="txtCompensatorio" required value="0" autocomplete="off">
                </div>
                
              </div>
              <div class="form-row"> 
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="txtFechaInicio">Fecha y hora de inicio</label>
                  <div
                      class="input-group date"
                      id="datetimepickerInicio"
                      data-target-input="nearest"
                    >
                      <input
                        id="txtFechaInicio"
                        name="txtFechaInicio"
                        type="text"
                        class="form-control datetimepicker-input"
                        data-target="#datetimepickerInicio"
                        data-toggle="datetimepicker" required  autocomplete="off"
                      />
                      <div
                        class="input-group-append"
                        data-target="#datetimepickerInicio"
                        data-toggle="datetimepicker"
                      >
                        <div class="input-group-text"><i class="fa fa-calendar fa-lg"></i></div>
                      </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="txtFechaFin">Fecha y hora final</label>
                  <div
                      class="input-group date"
                      id="datetimepickerFinal"
                      data-target-input="nearest"
                    >
                      <input
                        id="txtFechaFin"
                        name="txtFechaFin"
                        type="text"
                        class="form-control datetimepicker-input"
                        data-target="#datetimepickerFinal"
                        data-toggle="datetimepicker" required autocomplete="off"
                      />
                      <div
                        class="input-group-append"
                        data-target="#datetimepickerFinal"
                        data-toggle="datetimepicker"
                      >
                        <div class="input-group-text"><i class="fa fa-calendar fa-lg"></i></div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="txtActividad">Tipo de Compensatorio</label>
                  <!-- <input class="form-control" id="txtActividad" name="txtActividad" rows="4" cols="50" required autocomplete="off"></input> -->
                  <div class="form-group">
                    <select class="form-control" data-live-search="true" id="txtActividad" name="txtActividad" required>
                    </select>
                  </div>
                </div>
                <div class="form-group col-md-6">
                   <label for="txtTrabajoRequerido">¿Quien requiere el trabajo?</label>
                    <input type="text" class="form-control" id="txtTrabajoRequerido" name="txtTrabajoRequerido" required
                    autocomplete="off">
                </div>
                <div class="form-group col-md-6">
                  <label for="txtDescripcionActividad">Descripcion Actividad</label>
                  <textarea class="form-control" id="txtDescripcionActividad" name="txtDescripcionActividad" rows="4" cols="50" required autocomplete="off"></textarea>
                </div>

                <div class="form-group col-md-6">
                  <label for="listaUsuarios">Usuarios</label>
                  
                  <select class="form-control" data-live-search="true" name="listaUsuarios" id="listaUsuarios">
                  
                  </select>
                </div>
                
              <div class="form-group col-md-6" style="display:none;">
                <label for="txtEstado">Estado</label>
                <input id="txtEstado" name="txtEstado" value="1">              
              </div>
                <!-- <div class="form-group col-md-6">
                  <label for="txtFechaEntrega">Fecha Final de Entrega</label>
                  <input type="date" class="form-control" id="txtFechaEntrega" name="txtFechaEntrega" required
                    autocomplete="off">
                </div> -->
                <!-- <div class="form-group col-md-6">
                  <label for="">Label sin definicion</label>
                  <input type="text" class="form-control" id="" name="" 
                    autocomplete="off">
                </div> -->
                <!-- <div class="form-group col-md-6">
                  <label for="txtJustificacion">Justificacion</label>
                    <textarea class="form-control" id="txtJustificacion" name="txtJustificacion" rows="4" cols="50"></textarea>
                </div> -->
             </div>
              <div class="tile-footer">
                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Enviar solicitud</span></button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
              </div>
            </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalViewFuncionario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header headerView ">
        <h5 class="modal-title" id="titleModal">Datos Adicionales Del Compensatorio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
          <tr>
              <td>Nombres</td>
              <td id="InfoNombres"></td>
            </tr>
            <tr>
              <td>Apellidos</td>
              <td id="InfoApellidos"></td>
            </tr>
            <tr>
              <td>Correo</td>
              <td id="InfoCorreo"></td>
            </tr>
            <tr>
              <td>Tipo de Compensatorio</td>
              <td id="InfoTipoCompensatorio"></td>
            </tr>
            <tr>
              <td>Horas realizadas</td>
              <td id="InfoHorasRealizadas"></td>
            </tr>
            <tr>
              <td>Estado</td>
              <td id="InfoEstado"></td>
            </tr>
            <tr>
              <td>Descargar soporte</td>
              <td id="DescargarSoporte"></td>
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
<!-- Modal Evidencias --->
<div class="modal fade" id="modalFormEvidencias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header headerView">
                    <h5 class="modal-title" id="exampleModalLabel">Cargar Evidencias del Compensatorio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCargarEvidencias" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Seleccione un archivo:</label>
                            <input type="file" class="form-control-file" id="archivoEvidencia" name="archivoEvidencia" multiple>
                        </div>
                      </div>
                      
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnSubirEvidencia">Subir Evidencia</button>
                      </div>
                    </form>
            </div>
        </div>
    </div> 
</div>