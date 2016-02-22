<setion class="content-header"><h1>
            Servicios
            <small>Panel de Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class=""><a href="/services">Servicios</a> </li>
            <li class="active">Editar</li>
          </ol>


        </section>

        <section class="content">
        <div class="row">
        <div class="col-md-12">

          <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Servicios</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="serviceCreateForm" role="form" novalidate>
                  <div class="box-body">
                  <div class="callout callout-danger" ng-show="errors">
                                                  <ul>
                                              <li ng-repeat="row in errors track by $index"><strong >@{{row}}</strong></li>
                                              </ul>
                                            </div>
                    

                  <div class="row">
                    <div class="col-md-5"> 
                                  <div class="row">
                                  <div class="col-md-10" >
                                    <label for="fechaServicio"></label>
                                      <input type="text" ng-model="customerSelected" placeholder="Buscar Cliente" typeahead="atributo as atributo.busqueda for atributo in getCustomer($viewValue)" 
                                            typeahead-loading="loadingLocations" typeahead-no-results="noResults" class="form-control" typeahead-on-select="selecionarCliente()"/>
                                    </div>
                                    <div>
                                      <a style="margin-top:20px;" class="btn btn-default ng-binding" data-toggle="modal" data-target="#miventana2"><span class="glyphicon glyphicon-plus"></span></a>
                                    </div>
                                    </div> 
                    </div>

                    <div class="col-md-4">                          
                    <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.fechaServicio.$error.required &amp;&amp; serviceCreateForm.$submitted || serviceCreateForm.fechaServicio.$dirty &amp;&amp; serviceCreateForm.fechaServicio.$invalid]">
                                <label for="fechaServicio">Fecha: </label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                </div>
                                  <input type="date" class="form-control ng-pristine ng-valid ng-touched" name="fechaServicio" ng-model="service.fechaServicio">
                            </div>
               
                      </div>
                      </div>
                      <div class="col-md-3"> 
                        <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.numeroServicio.$error.required && serviceCreateForm.$submitted || serviceCreateForm.numeroServicio.$dirty && serviceCreateForm.numeroServicio.$invalid]">
                          <label for="numeroServicio" >N° Orden</label>
                          <input ng-disabled="true" type="text" class="form-control" name="numeroServicio" placeholder="N° Orden" ng-model="service.numeroServicio" required>
                          <label ng-show="serviceCreateForm.$submitted || serviceCreateForm.numeroServicio.$dirty && serviceCreateForm.numeroServicio.$invalid">
                            <span ng-show="serviceCreateForm.numeroServicio.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                          </label>
                        </div> 
                      </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" >
                            <label for="cliente">Cliente</label>
                            <input type="text" class="form-control" name="cliente" placeholder="Nombre y apellidos" ng-model="service.cliente" >
                          </div>
                    </div>
                    
                  </div>

                  <div class="row">
                    <div class="col-md-8">
                        <div class="form-group" >
                          <label for="empresa">Empresa / Razón Social</label>
                          <input type="text" class="form-control" name="empresa" placeholder="Empresa / Razón Social" ng-model="service.empresa" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="ruc">RUC</label>
                          <input type="text" class="form-control" name="ruc" placeholder="ruc" ng-model="service.ruc" >
                        </div>
                    </div>
                  </div> 


                  <div class="row">
                    <div class="col-md-8">
                        <div class="form-group" >
                          <label for="direcion">Direccion</label>
                          <input type="text" class="form-control" name="direcion" placeholder="Direccion" ng-model="service.direcion" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.telefono.$error.required && serviceCreateForm.$submitted || serviceCreateForm.telefono.$dirty && serviceCreateForm.telefono.$invalid]">
                          <label for="telefono">Telefono Referencia</label>
                          <input type="text" class="form-control" name="telefono" placeholder="Telf Referencia" ng-model="service.telefono" required>
                          <label ng-show="serviceCreateForm.$submitted || serviceCreateForm.telefono.$dirty && serviceCreateForm.telefono.$invalid">
                            <span ng-show="serviceCreateForm.telefono.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                          </label>
                        </div>
                    </div>
                  </div> 


                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                            <label>Tipo</label>
                                            <select name="genero" class="form-control ng-pristine ng-valid ng-touched" ng-model="service.tipo">
                                             <option value="">-- elige tipo --</option>
                                             <option value="0">Servicio</option>
                                             <option value="1">Garantia</option>

                                            </select>
                                      </div>
                                  </div>

                                  <div class="col-md-6" ng-if="service.tipo==1">  
                                    <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.ordenTrabajo.$error.required && serviceCreateForm.$submitted || serviceCreateForm.ordenTrabajo.$dirty && serviceCreateForm.ordenTrabajo.$invalid]">
                                      <label for="ordenTrabajo">Orden de trabajo</label>
                                      <input type="text" class="form-control" name="ordenTrabajo" placeholder="Orden de trabajo " ng-model="service.ordenTrabajo" required>
                                      <label ng-show="serviceCreateForm.$submitted || serviceCreateForm.ordenTrabajo.$dirty && serviceCreateForm.ordenTrabajo.$invalid">
                                        <span ng-show="serviceCreateForm.ordenTrabajo.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                      </label>
                                    </div>
                                  </div>
                              </div>

                              <div class="box box-primary">
                                <div class="box-header with-border">
                                <h3 class="box-title">Datos Del Equipo</h3>
                              </div><!-- /.box-header -->

                              <div class="row">
                                <div class="col-md-12">  
                                    <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.descripcion.$error.required && serviceCreateForm.$submitted || serviceCreateForm.descripcion.$dirty && serviceCreateForm.descripcion.$invalid]">
                                      <label for="descripcion">Descripcion Impresora</label>
                                      <input type="text" class="form-control" name="descripcion" placeholder="Descripcion Impresora " ng-model="service.descripcion" required>
                                      <label ng-show="serviceCreateForm.$submitted || serviceCreateForm.descripcion.$dirty && serviceCreateForm.descripcion.$invalid">
                                        <span ng-show="serviceCreateForm.descripcion.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                      </label>
                                    </div>
                                  </div>
                              </div>


                              <div class="row">
                                <div class="col-md-6">  
                                    <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.modelo.$error.required && serviceCreateForm.$submitted || serviceCreateForm.modelo.$dirty && serviceCreateForm.modelo.$invalid]">
                                      <label for="modelo">Modelo</label>
                                      <input type="text" class="form-control" name="modelo" placeholder="Modelo Impresora " ng-model="service.modelo" required>
                                      <label ng-show="serviceCreateForm.$submitted || serviceCreateForm.modelo.$dirty && serviceCreateForm.modelo.$invalid">
                                        <span ng-show="serviceCreateForm.modelo.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md-6">  
                                    <div class="form-group" ng-class="{true: 'has-error'}[ serviceCreateForm.serie.$error.required && serviceCreateForm.$submitted || serviceCreateForm.serie.$dirty && serviceCreateForm.serie.$invalid]">
                                      <label for="serie">Serie</label>
                                      <input type="text" class="form-control" name="serie" placeholder="Modelo Impresora " ng-model="service.serie" required>
                                      <label ng-show="serviceCreateForm.$submitted || serviceCreateForm.serie.$dirty && serviceCreateForm.serie.$invalid">
                                        <span ng-show="serviceCreateForm.serie.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                      </label>
                                    </div>
                                  </div>
                              </div>

                    <div class="form-group" >
                      <label for="notas">Accesorios</label>
                      <textarea type="notas" class="form-control" name="notas" placeholder="Accesorios"
                      ng-model="service.accesorios" rows="4" cols="50"></textarea>
                     </div>
                     <div class="form-group">
                                <label for="diagnostico">Diagnostico</label>
                                <textarea type="diagnostico" class="form-control" name="diagnostico" placeholder="Diagnostico"
                                          ng-model="service.diagnostico" rows="4" cols="50"></textarea>
                              </div>

                </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" ng-click="createService()">Crear</button>
                    <a href="/services" class="btn btn-danger">Cancelar</a>
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->





              <!-- =========================================Ventana Agregar Año=================================-->
         <div class="container"  style="margin-top: 60px;">
           <div  class="modal fade" id="miventana2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="ngenabled">
             <div  class="modal-dialog">
                  <div style="border-radius: 5px" class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="ngenabled"> &times; </button>
                          <h4 class="modal-title">Opciones Año</h4>
                        </div>
                        <form name="customerCreateForm" role="form" novalidate> 
                        <!--=================cueropo========================-->
                        <div class="modal-body">
                   <div class="row" >
                    <div class="col-md-6">
                      <div class="form-group" ng-class="{true: 'has-error'}[ customerCreateForm.nombres.$error.required && customerCreateForm.$submitted || customerCreateForm.nombres.$dirty && customerCreateForm.nombres.$invalid]">
                      <label for="nombres">Nombres</label>
                      <input type="text" class="form-control" name="nombres" placeholder="Nombres" ng-model="customer.nombres" required>
                      <label ng-show="customerCreateForm.$submitted || customerCreateForm.nombres.$dirty && customerCreateForm.nombres.$invalid">
                        <span ng-show="customerCreateForm.nombres.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                      </label>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group" ng-class="{true: 'has-error'}[ customerCreateForm.apellidos.$error.required && customerCreateForm.$submitted || customerCreateForm.apellidos.$dirty && customerCreateForm.apellidos.$invalid]">
                      <label for="apellidos">Apellidos</label>
                      <input type="text" class="form-control" name="apellidos" placeholder="Apellidos"
                      ng-model="customer.apellidos" required>
                      <label ng-show="customerCreateForm.$submitted || customerCreateForm.apellidos.$dirty && customerCreateForm.apellidos.$invalid">

                        <span ng-show="customerCreateForm.apellidos.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                       </label>
                    </div>
                    </div>
                    </div>

                    <div class="row" >
                    <div class="col-md-6">
                    <div class="form-group" >
                      <label for="apellidos">Empresa / Razón Social</label>
                      <input type="text" class="form-control" name="empresa" placeholder="empresa"
                      ng-model="customer.empresa">
                     </div>
                     </div>
                     <div class="col-md-6">
                    <div class="form-group" >
                      <label for="direccFiscal">Dirección Fiscal</label>
                      <input type="text" class="form-control" name="direccFiscal" placeholder="dirección fiscal"
                      ng-model="customer.direccFiscal">
                     </div>
                     </div>
                     </div>

                    <div class="row" >
                    <div class="col-md-6"> 
                    <div class="form-group" >
                      <label for="ruc">RUC</label>
                      <input type="text" class="form-control" name="ruc" placeholder="ruc"
                      ng-model="customer.ruc">
                     </div>
                     </div>
                     <div class="col-md-3"> 
                        <div class="form-group" >
                                      <label for="codigo">Código de Cliente</label>
                                      <input type="text" class="form-control" name="codigo" placeholder="codigo de cliente"
                                             ng-model="customer.codigo" ng-disabled="customer.autogenerado" ng-required="!customer.autogenerado">
                                      <span style="color:#dd4b39;" ng-show="customerCreateForm.codigo.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                  </div>
                      </div>

                        <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="apellidos">Autogenerado</label><br>
                                      <input type="checkbox" ng-model="customer.autogenerado"> Cód. gen.
                                  </div>
                              </div>
                    </div>
                    <div class="row" >
                    <div class="col-md-5"> 
                    <div class="form-group" ng-class="{true: 'has-error'}[ customerCreateForm.fechaNac.$error.required && customerCreateForm.$submitted || customerCreateForm.fechaNac.$dirty && customerCreateForm.fechaNac.$invalid]">
                    <label for="fechaNac">Fecha de Nac.</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                      <input type="date" class="form-control" name="fechaNac" ng-model="customer.fechaNac">
                      <label ng-show="customerCreateForm.$submitted || customerCreateForm.fechaNac.$dirty && customerCreateForm.fechaNac.$invalid">
                                              <span ng-show="customerCreateForm.fechaNac.$invalid"><i class="fa fa-times-circle-o"></i>Fecha Inválida.</span>
                                            </label>
                      </div>
                     </div>
                     </div>
                     <div class="col-md-3"> 
                      <div class="form-group">
                                            <label>Género</label>
                                            <select name="genero" class="form-control" ng-model="customer.genero">
                                             <option value="">-- elige género --</option>
                                             <option value="M">Masculino</option>
                                             <option value="F">Femenino</option>

                                            </select>
                      </div>
                      </div>
                      <div class="col-md-4">
            <div class="form-group" >
                <label for="dni">DNI</label>
                <input type="text" class="form-control" name="dni" placeholder="8 dígitos"
                       ng-model="customer.dni">
            </div>
        </div>
                     </div>


                     <div class="">
                          <hr>
                          <button type="button" class="btn btn-default" ng-click="toggle()">Mostrar Formulario de Contacto</button>
                          <hr>
                      </div>

                <div ng-show="show" >
                     <div class="row" >
                     

                    <div class="col-md-4">  
                    <div class="form-group" >
                      <label for="fijo">Teléfono fijo</label>
                      <input type="text" class="form-control" name="fijo" placeholder="###"
                      ng-model="customer.fijo">
                     </div>
                     </div>

                     <div class="col-md-4"> 
                    <div class="form-group" >
                      <label for="movil">Teléfono movil</label>
                      <input type="text" class="form-control" name="movil" placeholder="###"
                      ng-model="customer.movil">
                     </div>
                     </div>
                     </div>

                    <div class="row" >
                     <div class="col-md-4">  
                    <div class="form-group" >
                      <label for="email">Email</label>
                      <input type="email" class="form-control" name="email" placeholder="###"
                      ng-model="customer.email">
                     </div>
                     </div>
                     <div class="col-md-4">  
                    <div class="form-group" >
                      <label for="website">Página Web</label>
                      <input type="text" class="form-control" name="website" placeholder="###"
                      ng-model="customer.website">
                     </div>
                     </div>
                     <div class="col-md-4">  
                    <div class="form-group" >
                      <label for="direccContac">Dirección de Contacto</label>
                      <input type="text" class="form-control" name="direccContac" placeholder="###"
                      ng-model="customer.direccContac">
                     </div>
                     </div>
                     </div>

                    <div class="row" >
                     <div class="col-md-3"> 
                    <div class="form-group" >
                      <label for="distrito">Distrito</label>
                      <input type="text" class="form-control" name="distrito" placeholder="Chiclayo"
                      ng-model="customer.distrito">
                     </div>
                     </div>
                     <div class="col-md-3"> 
                    <div class="form-group" >
                      <label for="provincia">Provincia</label>
                      <input type="text" class="form-control" name="provincia" placeholder="Chiclayo"
                      ng-model="customer.provincia">
                     </div>
                     </div>
                     <div class="col-md-3"> 
                    <div class="form-group" >
                      <label for="departamento">Departamento</label>
                      <input type="text" class="form-control" name="departamento" placeholder="Lambayeque"
                      ng-model="customer.departamento">
                     </div>
                     </div>
                     <div class="col-md-3"> 
                    <div class="form-group" >
                      <label for="pais">País</label>
                      <input type="text" class="form-control" name="pais" placeholder="Perú"
                      ng-model="customer.pais">
                     </div>
                     </div>
                     </div>
                    <div class="form-group" >
                      <label for="notas">Notas</label>
                      <input type="notas" class="form-control" name="notas" placeholder="..."
                      ng-model="customer.notas"></input>
                     </div>
                      </div>
                  </div>
                        <!--================================================-->
                        <div class="modal-footer" >
                          <button type="submit" class="btn btn-primary" ng-click="createCustomer()">Crear</button>
                          <a  class="btn btn-danger" data-dismiss="modal" aria-hidden="ngenabled">Salir</a>
                      </div>
                    </form>
                  </div>
              </div>
            </div>
          </div>