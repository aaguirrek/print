<?php

namespace App\Printer;

use Empresa;
use Printer;
use WindowsPrintConnector;
use EscposImage;
use FilePrintConnector;
use NetworkPrintConnector;
use Printers;
use QRCode;
use Storage;

class Formato
{
    public static function test($r){
        return detectFacturaComprobante($r);
    }
    public static function Comanda($request){
        $el = detectFacturaComprobante($request);
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $printer1 = Printers::Where('ubicacion', 'comanda')->first();
        if( $printer1 ){}else{
            return abort(500);
        }
        if($printer1->printer == "USB"){
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }else{
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $printer = new Printer($connector);
        $printer->setFont(2);
        $printer -> text( $el->posting_time . " \n ");
        $printer -> setJustification(1);
        $printer -> text("MESA: ".$request["mesa"]."\n\n");
        $printer -> setJustification(0);
        foreach( $el->itema as $key => $value ){
            $printer -> text( $value->qty . " :: " . $value->item_name . "\n");
            if(array_key_exists("extras", $value )  ){
                if(count( $value["extras"] ) > 0 ){
                    $printer -> setJustification(1);
                    $printer -> text( ".: Toppings :.\n");
                    $printer -> setJustification(0);
                    foreach ($value["extras"] as $clave => $valor){
                        $printer -> text( "    " . $valor . " \n");
                    }
                    $printer -> text( "    " . $value->comentario . " \n");
                    $printer -> text("\n\n");
                }
            }

        }
        $printer -> setJustification(1);
        $printer -> text("\n");
        $printer -> text( "Build on Frappe\n\n");
        $printer -> setJustification(1);
        $printer -> cut();
        $printer -> text("\n \n \n");
        $printer -> close();
        return $request->all();
    }
    public static function pago($request){
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $el = detectFacturaComprobante($request);
        if($el->qr != "no"){
            $qrCode = \QrCode::format('png')->size(300)->generate($request["message"]["qr"]);
            Storage::disk('local')->put('qrcode.png', $qrCode);
        }
        $printer1 = Printers::Where('ubicacion', 'caja')->first();
        $connector = null;
        if( $printer1 ){}else{
            return abort(500);
        }
        if($printer1->printer == "USB"){
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }else{
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $printer = new Printer($connector);
        $printer->setFont(0);
        $printer -> setJustification(1);
        $printer -> text(".:".$el->title.":.\n");
        $printer -> text( $el->mesa );
        $printer -> text( $el->id ."\n\n");
        $printer -> text( $el->razon_social ."\n" );
        $printer -> text( $el->ruc ."\n" );
        $printer -> text( $el->direccion ."\n\n" );
        $printer -> text( $el->posting_date );
        $printer -> setJustification( 0 );
        if (  $el->customer != ""){
            $printer -> text("ADQUIRIENTE:\n");
            $printer -> text( $el->tax_id  );
            $printer -> text( $el->customer ."\n\n");
        }
        $line = sprintf('%3.3s %-30.30s %6.6s %6.6s', "Can" ,  "Items", "PU",  "PT" );
        $printer->text($line);
        $printer -> text("\n");
        $line = sprintf('%3.3s %-30.30s %6.6s %6.6s', "_______" ,  "_______________________________________", "________",  "_______" );
        $printer->text($line);
        $printer -> text("\n");
        foreach ($el->items as $key => $value){
            $arrstr = str_split($value->item_name, 30);
            foreach ($arrstr as $k => $val) {
                if($k==0){
                    $line = sprintf('%3.3s %-30.30s %6.5s %6.5s',  $value->qty ,  $val,  $value->rate,  $value->amount );
                    $printer->text($line);
                    $printer -> text("\n");

                }else{
                    $line = sprintf('%3.3s %-30.30s %6.5s %6.5s',  "" ,  $val,  "",  "" );
                    $printer->text($line);
                    $printer -> text("\n");
                }
            }
        }
        $printer -> setJustification(0);
        $printer -> text( "\n\n");
        $printer -> setJustification(2);
        $printer -> text( sprintf('%-15.15s %-1.1s %7.7s','Total Inafecta',':', $el->total_inafecto ) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-15.15s %-1.1s %7.7s','Total Exonerada',':', $el->total_exonerado ) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-15.15s %-1.1s %7.7s','Total Gravada',':', $el->total_gravado ) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-15.15s %-1.1s %7.7s','IGV('.$el->porcentaje_igv.'%)',':', $el->igv ) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-15.15s %-1.1s %7.7s','Total',':', $el->total."\n" ) );
        $printer -> setJustification(1);
        if($el->qr != "no"){
            $printer -> setJustification(1);
            $printer -> text( "\n");
            $printer -> text( "\n\n");
            $printer -> text( "Representación impresa de la ".$el->rec." de Venta\n");
            $printer -> text( $empresa->domain."cpe?t=".$el->code."&c=".$el->name."\n\n");
            $printer -> text( "Autorizado mediante Resolución de Intendencia No.034-005-0005315\n\n");
            $printer -> text( "Resumen:\n\n");
            $printer -> text( $el->hash ."\n\n");
            $img = EscposImage::load(storage_path('app')."\qrcode.png", false);
            $printer -> graphics($img);
        }
        $printer -> text("\n \n");
        $printer -> text( "Build on Frappe\n\n");
        $printer -> text("\n \n");
        $printer -> text("\n \n");
        $printer -> cut();
        $printer -> text("\n \n \n");
        $printer -> close();
        return $request->all();
    }
    
}

function detectFacturaComprobante($res){
    $el = new class{};

    $total = (float)$res["message"]["total"];
    $PercentIGV = (float)$res["sunat"]["igv"];
    $el->porcentaje_igv = (string)$res["sunat"]["igv"];
    $el->ruc = $res["sunat"]["ruc"];
    $el->direccion = $res["sunat"]["direccion"];
    $el->razon_social = $res["sunat"]["razon_social"];
    $el->mesa = "";
    
        $el->mesa = $res["mesa"]."\n";

    $items = [];
    $igv = (float)0;
    $total_inafecto=(float)0;
    $total_gratuito=(float)0;
    $total_gravado=(float)0;
    $total_exonerado=(float)0;
    if(array_key_exists("name", $res["message"])){
        $com=$res["message"]["name"];
    }else{
        $com = "Pre Cuenta";
    }
    $tax_id="";
    $customer="";
    if(array_key_exists("posting_date", $res["message"] )){
        $el->posting_date ="Fecha y Hora: ". $res["message"]["posting_date"]." ".$res["message"]["posting_time"] ."\n\n";
    }else{
        if(array_key_exists("fecha_de_emision", $res["message"] )){
            $el->posting_date = "Fecha: ". $res["message"]["fecha_de_emision"] ."\n\n";
        }else{
            $el->posting_date = "Fecha y Hora: ". $res["time"] ."\n\n";
        }
    }
    if(key_exists("serie_documento", $res["message"] )){
        $com= $res["message"]["serie_documento"]."-".$res["message"]["numero_documento"];
    }
    if(key_exists("total_igv", $res["message"] )){
        $igv = (float)$res["message"]["total_igv"];
    }else{
        $igv = ( $total * $PercentIGV ) / ( 100 + $PercentIGV );
    }
    $igv = round( $igv , 2 );
    if(key_exists("total_gravada", $res["message"]) ){   
        $total_inafecto= (float)$res["message"]["total_inafecta"] ;
        $total_gratuito=(float)"0.00" ;
        $total_exonerado=(float)$res["message"]["total_exonerada"] ;
        $total_gravado = (float)$res["message"]["total_gravada"];
    }else{
        $total_gravado = $total - $igv;
    }
    $total_inafecto=round($total_inafecto,2);
    $total_gratuito=round($total_gratuito,2);
    $total_exonerado= round($total_exonerado,2);
    $total_gravado = round($total_gravado,2);

    foreach ( $res["message"]["items"] as $key => $value) {


        $items[$key] = new class{};
        if(key_exists("cantidad", $value )){
            $items[$key]->qty =  (int)$value["cantidad"];
        }else{
            $items[$key]->qty =  (int)$value["qty"];
        }
        if(key_exists("comentario",$value)){
            $items[$key]->comentario =  eliminar_acentos(  $value["comentario"] );
        }
        if(key_exists("codigo_interno", $value )){
            $items[$key]->item_name =  eliminar_acentos(  $value["codigo_interno"] );
        }else{
            $items[$key]->item_name =  eliminar_acentos(  $value["item_name"] );
        }
        if(key_exists("precio_unitario", $value )){
            $items[$key]->rate =  (float)$value["precio_unitario"];
        }else{
            $items[$key]->rate =  (float)$value["rate"];
        }
        if(key_exists("total", $value )){
            $items[$key]->amount =  (float)$value["total"];
        }else{
            $items[$key]->amount =  $items[$key]->rate *  $items[$key]->qty;
        }
        $items[$key]->qty = (string)$items[$key]->qty;
        $items[$key]->rate =number_format($items[$key]->rate,2);
        $items[$key]->amount =number_format($items[$key]->amount,2);
     


    }

    
    if(key_exists("cliente_numero_de_documento", $res["message"] ))
    {
        $tax_id = $res["message"]["cliente_numero_de_documento"]."\n";
    }
    else
    {
        if(key_exists("tax_id", $res["message"] )){
            $tax_id = $res["message"]["tax_id"]."\n";
        }
    }
    if(key_exists("customer", $res["message"] ))
    {
        $customer = $res["message"]["customer"];
    }else{
        if(key_exists("cliente_denominacion", $res["message"] )){
        $customer = $res["message"]["cliente_denominacion"];
        }
    }
    if( key_exists("qr", $res["message"]) )
    {
        $el->qr = $res["message"]["qr"];
    }else{
        $el->qr = "no";
    }
    if( key_exists("hash", $res["message"] ) )
    {
        $el->hash = $res["message"]["hash"];
    }else{
        $el->hash = "no";
    }
    if($tax_id == null ){
        $tax_id = "";
    }
    if($res->tipo == "Comanda"){
        $el->code = "C";
        $el->title = "COMANDA";    
        $el->rec = "Comanda";
        
    }
    if($res->tipo == "Pre Cuenta"){
        $el->code = "P";
        $el->title = "PRE CUENTA";    
        $el->rec = "Pre Cuenta";
        
    }
    if($res->tipo == "Factura"){
        $el->code = "F";    
        $el->title = "FACTURA ELECTRONICA";
        $tax_id = "DNI: ". $res["message"]["cliente_numero_de_documento"]."\n";
        $el->rec = "Factura";
    }
    if($res->tipo == "Boleta"){
        $el->code = "B";    
        $el->title = "BOLETA ELECTRONICA";
        $tax_id = "NRO DOC: ". $res["message"]["cliente_numero_de_documento"]."\n";
        $el->rec = "Boleta";
    }
    if($res->tipo == "Sales Invoice"){
        $el->code = "V";    
        $el->title = "COMPROBANTE DE VENTA";
        $tax_id = "";
        $el->rec = "Comprobante";
    }
    $el->tipo = $res->tipo;
    $el->items = $items;
    $el->igv = number_format($igv,2);
    $el->tax_id = $tax_id;
    $el->customer = $customer;
    
    if(key_exists("name", $res["message"])){
        $el->name = $res["message"]["name"];
    }else{
        $el->name = "Pre Cuenta";
    }
    $el->id = $com;
    
    $el->total_inafecto = number_format($total_inafecto,2);
    $el->total_gratuito = number_format($total_gratuito,2);
    $el->total_exonerado = number_format($total_exonerado,2);
    $el->total_gravado = number_format($total_gravado,2);
    $el->total = $total;
    return $el;
}
function eliminar_acentos($cadena)
{
            
    //Reemplazamos la A y a
    $cadena = str_replace(
    array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
    array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
    $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
    array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
    array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
    $cadena );

    //Reemplazamos la I y i
    $cadena = str_replace(
    array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
    array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
    $cadena );

    //Reemplazamos la O y o
    $cadena = str_replace(
    array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
    array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
    $cadena );

    //Reemplazamos la U y u
    $cadena = str_replace(
    array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
    array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
    $cadena );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
    array('Ñ', 'ñ', 'Ç', 'ç'),
    array('N', 'n', 'C', 'c'),
    $cadena
    );
    
    return $cadena;
}