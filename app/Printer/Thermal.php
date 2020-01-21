<?php

namespace App\Printer;

use Empresa;
use Printer;
use WindowsPrintConnector;
use EscposImage;
use FilePrintConnector;
use NetworkPrintConnector;
use Printers;
use Formato;
use QRCode;
use Storage;

class Thermal{
    public static function Comanda($request){
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
        $printer -> setJustification(1);
        $printer -> text($request["time"] . " \n");
        $printer->setTextSize(2,2);
        $printer -> text("".$request["mesa"]."\n\n");
        $printer->setTextSize(1,1);
        $printer -> setJustification(0);
        foreach ($request["message"]["items"] as $key => $value){
            $printer -> text( $value["qty"] . " :: " . eliminar_acentos($value["item_name"]) . "\n");
            if(array_key_exists("extras", $value )  ){
                if(count( $value["extras"] ) > 0 ){
                    $printer -> setJustification(1);
                    $printer -> text( ".: Toppings :.\n");
                    $printer -> setJustification(0);
                    foreach ($value["extras"] as $clave => $valor){
                        $printer -> text( "    " . $valor . " \n");
                    }
                    if(key_exists("comentario",$value ) ){
                        $printer -> text( "    " . $value["comentario"] . " \n");
                    }
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

        comanda_bebidas();
        return $request->all();
    }

    public static function PreCuenta($request)
    {
        return Formato::detectFacturaComprobante($request->all());
    }
    public static function SalesInvoice($request)
    {
        return Formato::detectFacturaComprobante($request);
    }
    public static function Factura($request)
    {
        return Formato::detectFacturaComprobante($request);
    }
    public static function Boleta($request)
    {
        return Formato::detectFacturaComprobante($request);
    }

    
}

function comanda_bebidas($cadena)
{
    $empresa = Empresa::all();
    if( count($empresa) > 0 ){
        $empresa = $empresa[0];   
    }
    $printer1 = Printers::Where('ubicacion', 'Bebidas')->first();
    if( $printer1 ){}else{
        return;
    }
    if($printer1->printer == "USB"){
        $connector = new WindowsPrintConnector( $printer1->ruta );
    }else{
        $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
    }
    $printer = new Printer($connector);
    $printer->setFont(2);
    $printer -> setJustification(1);
    $printer -> text($request["time"] . " \n");
    $printer->setTextSize(2,2);
    $printer -> text("".$request["mesa"]."\n\n");
    $printer->setTextSize(1,1);
    $printer -> setJustification(0);
    foreach ($request["message"]["items"] as $key => $value){
        if($value["item_group"] == "Bebidas")
        {
            $printer -> text( $value["qty"] . " :: " . eliminar_acentos($value["item_name"]) . "\n");
            if(array_key_exists("extras", $value )  ){
                if(count( $value["extras"] ) > 0 ){
                    $printer -> setJustification(1);
                    $printer -> text( ".: Toppings :.\n");
                    $printer -> setJustification(0);
                    foreach ($value["extras"] as $clave => $valor){
                        $printer -> text( "    " . $valor . " \n");
                    }
                    if(key_exists("comentario",$value ) ){
                        $printer -> text( "    " . $value["comentario"] . " \n");
                    }
                    $printer -> text("\n\n");
                }
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