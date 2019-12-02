@extends('base')
@section('titulo')
Configurar Ruc de la Empresa
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
<div class="form-column col-sm-6">
    <form>
        <div class="frappe-control input-max-width">
            <div class="form-group">
                <div class="clearfix">
                    <label class="control-label" style="padding-right: 0px;">Ruc</label>
                </div>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <input type="text" id="Ruc" autocomplete="off" class="input-with-feedback form-control" maxlength="140" placeholder="">
                    </div>
                </div>
            </div>
        </div>
        <div class="frappe-control input-max-width">
            <div class="form-group">
                <div class="clearfix">
                    <label class="control-label" style="padding-right: 0px;">Dominio web</label>
                </div>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <input type="text" id="Domain" autocomplete="off" class="input-with-feedback form-control" maxlength="140" placeholder="Ejm. https://frappe.cf/">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="form-column col-sm-6">
    <form>
        <div class="frappe-control input-max-width">
            <div class="form-group">
                <div class="clearfix">
                    <label class="control-label" style="padding-right: 0px;">Razon Social</label>
                </div>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <input type="text" id="RazonSocial" autocomplete="off" class="input-with-feedback form-control" maxlength="140" placeholder="">
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
            "ruc":$("#Ruc").val(),
            "razon_social":$("#RazonSocial").val(),
            "domain":$("#Domain").val()
        }
   
        $.post('/empresa', doc, function(result){
                location.href="/";
        });
    }
</script>
@endsection