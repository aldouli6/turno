<?php

class MostrarWizard{

	public static function seleccionFraccionamiento(){
		echo ' <div class="row row-same-height">
			  <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
			    <div class="padding-30 m-t-50">
			      <i class="fa fa-globe fa-2x hint-text"></i>
			      <h2>Ubicación de fraccionamiento</h2>
			      <p>Delimita la ubicación de tu fraccionamiento</p>
			      <p class="small hint-text">Para poder dibujar en el mapa tu fraccionamiento y ubicarlo de una manera mas sencilla visualmente</p>
			    </div>
			  </div>
			  <div class="col-md-7">
			    <button type="button" class="btn btn-default" id="buttonBorrar" style="margin-top: 4px; width: 25px !important; height: 25px; margin-left: -5px; padding: 0;"><i class="fa fa-eraser" aria-hidden="true" style="position: absolute;margin-left: -7px;margin-top: -7px;color: #909090;"></i></button>
			    <input id="searchLocation" class="controls form-control" type="text" placeholder="Buscar" style="width: 250px; display:none">
			    <div clas="padding-30 m-t-50">
			      <p class="hint-text" style="text-transform: uppercase;">Ubica el fraccionamiento "fraccionamiento_nombre"</p>
			      <div id="map" style="height:50vh"></div>
			    </div>
			  </div>
			</div>';
	}

	public static function registroCalles(){
		echo ' <div class="row row-same-height">
			  <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
			    <div class="padding-30 m-t-50">
			      <i class="fa fa-road fa-2x hint-text"></i>
			      <h2>Registrando calles</h2>
			      <p>Registra las calles que se encuentran dentro de tu fraccionamiento</p>
			      <p class="small hint-text">Para que tus colonos puedan registrarse en la aplicacion movil con sus respectivos domicilios</p>
			    </div>
			  </div>
			  <div class="col-md-7">
			  	<div class="row" style="padding: 15px;background: #ffffff ;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;"">
				    <div class="col-sm-8" style="margin-left: calc(18%);">
				      <div class="input-group">
				        <input type="text" class="form-control" name="nombreCalle" id="nombreCalle">
				        <span class="input-group-btn">
				          <button class="btn btn-primary btn-cons btn-animated from-top fa fa-plus" type="button" id="añadirCalle" name="añadirCalle">
				            <span>Añadir</span>
				          </button>
				        </span>
				      </div>
				    </div>
				    <br>
				    <br>
				    <br>
				    <div class=" table table-responsive" style="width:90%; margin-left: calc(7%)">
	                                   <table id="callesDataTable" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
	                                     <thead>
	                                       <tr>
	                                         <th>Id</th>
	                                         <th>Calle</th>
	                                         <th></th>
	                                       </tr>
	                                     </thead>
	                                     <tfoot>
	                                       <tr>
	                                         <th>Id</th>
	                                         <th>Calle</th>
	                                         <th></th>
	                                       </tr>
	                                     </tfoot>
	                                     <tbody id="contenidoListaCallesTable">
					   
	                                     </tbody>
	                                   </table>
	                                 </div>
				</div>
			  </div>
			</div>';
	}

}

?>