@extends('base')
@section('titulo')
Configurar las impresoras
@endsection
@section('buttons')
<!-- <button class="btn btn btn-sm ">
    <i class="visible-xs octicon octicon-check"></i>
    <span class="hidden-xs">Limpiar</span>
</button> -->
<button class="btn btn-primary btn-sm primary-action" onclick="guardar()">
    <i class="visible-xs octicon octicon-check"></i>
    <span class="hidden-xs">Guardar</span>
</button>
@endsection
@section('content')
<div class="form-column col-sm-4">
    <form>
        <div class="control-input-wrapper">
            <div class="frappe-control input-max-width" data-fieldtype="Select" data-fieldname="mesarestaurant">				
                <div class="form-group">					
                    <div class="clearfix">						
                        <label class="control-label" style="padding-right: 0px;">Tipo de Conexión</label>
                    </div>
                    <div class="control-input-wrapper">
                        <div class="control-input flex align-center">
                            <select id="conexion-comanda" type="text" autocomplete="off" class="input-with-feedback form-control" maxlength="140">
                                <option value="RED">Red</option>
                                <option value="USB">USB</option>
                            </select>
                            <i class="fas fa-angle-down text-muted" style="position: absolute; right: 10px;"></i>
                        </div>
                        <div class="control-value like-disabled-input" style="display: none;">
                        </div>
                        <p class="help-box small text-muted hidden-xs"></p>					
                    </div>				
                </div>			
            </div>
        </div>
        <div class="frappe-control input-max-width">
            <div class="form-group">
                <div class="clearfix">
                    <label class="control-label" style="padding-right: 0px;">Comanda</label>
                </div>
                <div class="control-input-wrapper">
                    <div class="control-input"><!-- CBX-POS808 -->
                        <input type="text" id="comanda" autocomplete="off" class="input-with-feedback form-control" maxlength="140" placeholder="">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="form-column col-sm-4">
    <form>
        <div class="control-input-wrapper">
            <div class="frappe-control input-max-width" data-fieldtype="Select" data-fieldname="mesarestaurant">				
                <div class="form-group">					
                    <div class="clearfix">						
                        <label class="control-label" style="padding-right: 0px;">Tipo de Conexión</label>
                    </div>
                    <div class="control-input-wrapper">
                        <div class="control-input flex align-center">
                            <select id="conexion-caja" type="text" autocomplete="off" class="input-with-feedback form-control" maxlength="140">
                                <option value="RED">Red</option>
                                <option value="USB">USB</option>
                            </select>
                            <i class="fas fa-angle-down text-muted" style="position: absolute; right: 10px;"></i>
                        </div>
                        <div class="control-value like-disabled-input" style="display: none;">
                        </div>
                        <p class="help-box small text-muted hidden-xs"></p>					
                    </div>				
                </div>			
            </div>
        </div>
        <div class="frappe-control input-max-width">
            <div class="form-group">
                <div class="clearfix">
                    <label class="control-label" style="padding-right: 0px;">Caja</label>
                </div>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <input type="text" id="caja" autocomplete="off" class="input-with-feedback form-control" maxlength="140" placeholder="">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="form-column col-sm-4">
    <form>
        <div class="control-input-wrapper">
            <div class="frappe-control input-max-width" data-fieldtype="Select" data-fieldname="mesarestaurant">				
                <div class="form-group">					
                    <div class="clearfix">						
                        <label class="control-label" style="padding-right: 0px;">Tipo de Conexión</label>
                    </div>
                    <div class="control-input-wrapper">
                        <div class="control-input flex align-center">
                            <select id="conexion-bebidas" type="text" autocomplete="off" class="input-with-feedback form-control" maxlength="140">
                                <option value="RED">Red</option>
                                <option value="USB">USB</option>
                            </select>
                            <i class="fas fa-angle-down text-muted" style="position: absolute; right: 10px;"></i>
                        </div>
                        <div class="control-value like-disabled-input" style="display: none;">
                        </div>
                        <p class="help-box small text-muted hidden-xs"></p>					
                    </div>				
                </div>			
            </div>
        </div>
        <div class="frappe-control input-max-width">
            <div class="form-group">
                <div class="clearfix">
                    <label class="control-label" style="padding-right: 0px;">Bebidas</label>
                </div>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <input type="text" id="bebidas" autocomplete="off" class="input-with-feedback form-control" maxlength="140" placeholder="">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
@section('scripts')
<script>
    function guardar(){
        var doc={
            "comanda":$("#comanda").val(),
            "caja":$("#caja").val(),
            "Bebidas":$("#bebidas").val(),
            "ccomanda":$("#conexion-comanda").val(),
            "ccaja":$("#conexion-caja").val(),
            "cBebidas":$("#conexion-bebidas").val()
        }
   
        $.post('/printer-save', doc, function(result){
                location.href="/";
        });
    }
</script>
@endsection