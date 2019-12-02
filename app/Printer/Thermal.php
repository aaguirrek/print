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

class Thermal
{
    public static function Comanda($request)
    {
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $printer1 = Printers::Where('printer', 'comanda')->first();
        $connector = null;
        if( $printer1 )
        {   
        }
        else
        {
            return abort(500);
        }
        if($printer1->printer == "USB")
        {
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }
        else
        {
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $printer = new Printer($connector);
        $printer -> text($request["time"] . " - " . $request["sunat"]["razon_social"] ."\n");
        $printer -> setJustification(1);
        $printer->setFont(1);
        $printer -> text($request["mesa"]."\n\n");
        $printer -> setJustification(0);
        foreach ($request["message"]["items"] as $key => $value) 
        {
            $printer -> text( $value["qty"] . " :: " . $value["item_name"] . "\n\n");
            if(array_key_exists("extras", $value )  )
            {
                if(count( $value["extras"] ) > 0 )
                {
                    $printer -> setJustification(1);
                    $printer -> text( ".: Toppings :.\n");
                    $printer -> setJustification(0);
                    foreach ($value["extras"] as $clave => $valor) 
                    {
                        $printer -> text( "    " . $valor . " \n");
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
        return $request->all();
    }

    public static function PreCuenta($request)
    {
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $printer1 = Printers::Where('printer', 'caja')->first();
        $connector = null;
        if( $printer1 )
        {    
        }
        else
        {
            return abort(500);
        }
        if($printer1->printer == "USB")
        {
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }
        else
        {
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $printer = new Printer($connector);
        $printer->setFont(1);
        $printer -> setJustification(1);
        $printer -> text(".:PRE CUENTA:.\n\n");
        $printer -> text($request["sunat"]["razon_social"]."\n");
        $printer -> text($request["sunat"]["ruc"]."\n\n");
        $printer -> setJustification(0);
        $printer -> text("Items:\n");
        foreach ($request["message"]["items"] as $key => $value) 
        {
            $line = sprintf('%3.0f %-40.40s %5.2f %13.2f',$value["qty"] , eliminar_acentos(  $value["item_name"] ), $value["rate"], ( (int)$value["qty"] * (float)$value["rate"] ) );
            $printer->text($line);
            $printer -> text("\n");
        }
        $printer -> setJustification(0);
        $printer -> text( "TOTAL:"  . "\n");
        $printer -> setJustification(2);
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total',':', $request["message"]["total"]) );
        $printer -> text( "\n");
        $printer -> text("\n \n");
        $printer -> setJustification(1);
        $printer -> text( "Build on Frappe\n\n");
        $printer -> text("\n \n");
        $printer -> cut();
        $printer -> text("\n \n \n");
        $printer -> close();
        return $request->all();
    }
    public static function SalesInvoice($request)
    {
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $printer1 = Printers::Where('printer', 'caja')->first();
        $connector = null;
        if( $printer1 )
        {
            
        }
        else
        {
            return abort(500);
        }


        if($printer1->printer == "USB")
        {
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }else{
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $igv = ( (float)$request["sunat"]["igv"] * (float)$request["message"]["total"] )  /( 100 + (float)$request["sunat"]["igv"] );
        $igv = round( $igv ,2);
        $igv = (float)$igv;
        $printer = new Printer($connector);
        $printer->setFont(1);
        $printer -> setJustification(1);
        $printer -> text(".:COMPROBANTE ELECTRONICO:.\n\n");
        $printer -> text($request["sunat"]["razon_social"]."\n");
        $printer -> text($request["sunat"]["ruc"]."\n");
        $printer -> text($request["sunat"]["direccion"]."\n\n");
        $printer -> text("Fecha: ".$request["message"]["due_date"]."\n\n");
        $printer -> setJustification(0);
        $printer -> text("Adquiriente:\n");
        $printer -> text($request["message"]["customer_name"]."\n\n");
        $printer -> text("Items:\n");
        foreach ($request["message"]["items"] as $key => $value) 
        {
            $line = sprintf('%3.0f %-40.40s %5.2f %13.2f',$value["qty"] , eliminar_acentos(  $value["item_name"] ), $value["rate"], ( (int)$value["qty"] * (float)$value["rate"] ) );
            $printer->text($line);
            $printer -> text("\n");
        }
        $printer -> setJustification(0);
        $printer -> text( "TOTAL:"  . "\n");
        $printer -> setJustification(2);
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Gravada',':',  ( (float)$request["message"]["total"] - $igv ) ) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','IGV('.$request["sunat"]["igv"].'%)',':',round( $igv ,2 ) ) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total',':', round((float)$request["message"]["total"], 2) ) );
        $printer -> text( "\n");
        $printer -> text( "\n\n");
        $printer -> setJustification(1);
        $printer -> text( "Build on Frappe\n\n");
        $printer -> text("\n \n");
        $printer -> cut();
        $printer -> text("\n \n");
        $printer -> close();
        return $request->all();
    }
    public static function Factura($request)
    {
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $qrCode = \QrCode::format('png')->size(300)->generate($request["message"]["qr"]);
        Storage::disk('local')->put('qrcode.png', $qrCode);
        $printer1 = Printers::Where('printer', 'caja')->first();
        $connector = null;
        if( $printer1 )
        {
            
        }
        else
        {
            return abort(500);
        }
        if($printer1->printer == "USB")
        {
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }
        else
        {
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $printer = new Printer($connector);
        $printer->setFont(1);
        $printer -> setJustification(1);
        $printer -> text(".:FACTURA ELECTRONICA:.\n");
        $printer -> text($request["message"]["serie_documento"]."-".$request["message"]["numero_documento"]."\n\n");
        $printer -> text($request["sunat"]["razon_social"]."\n");
        $printer -> text($request["sunat"]["ruc"]."\n");
        $printer -> text($request["sunat"]["direccion"]."\n\n");
        $printer -> text("Fecha: ".$request["message"]["fecha_de_emision"]."\n\n");
        $printer -> setJustification(0);
        $printer -> text("Adquiriente:\n");
        $printer -> text("RUC: ".$request["message"]["cliente_numero_de_documento"]."\n");
        $printer -> text($request["message"]["cliente_denominacion"]."\n\n");
        $printer -> text("Items:\n");
        foreach ($request["message"]["items"] as $key => $value) 
        {
            $line = sprintf('%3.0f %-40.40s %5.2f %13.2f',$value["cantidad"] , eliminar_acentos(  $value["codigo_interno"] ), $value["precio_unitario"], $value["total"]);
            $printer->text($line);
            $printer -> text("\n");
        }
        $printer -> setJustification(0);
        $printer -> text( "TOTAL:"  . "\n");
        $printer -> setJustification(2);
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Inafecta',':', $request["message"]["total_inafecta"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Exonerada',':', $request["message"]["total_exonerada"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Gravada',':', $request["message"]["total_gravada"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','IGV('.$request["sunat"]["igv"].'%)',':', $request["message"]["total_igv"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total',':', $request["message"]["total"]) );
        $printer -> text( "\n");
        $printer -> text( "\n\n");
        $printer -> setJustification(1);
        $printer -> text( "Representación impresa de la Factura de Venta\n");
        $printer -> text( $empresa->domain."cpe?t=F&c=".$request["message"]["name"]."\n\n");
        $printer -> text( "Autorizado mediante Resolución de Intendencia No.034-005-0005315\n\n");
        $printer -> text( "Resumen:\n\n");
        $printer -> text( $request["message"]["hash"]."\n\n");
        $img = EscposImage::load(storage_path('app')."\qrcode.png", false);
        $printer -> graphics($img);
        $printer -> text("\n \n");
        $printer -> text( "Build on Frappe\n\n");
        $printer -> text("\n \n");
        $printer -> text("\n \n");
        $printer -> cut();
        $printer -> text("\n \n \n");
        $printer -> close();
        return $request->all();
    }
    public static function Boleta($request)
    {
        $empresa = Empresa::all();
        if( count($empresa) > 0 ){
            $empresa = $empresa[0];   
        }
        $qrCode = \QrCode::format('png')->size(300)->generate($request["message"]["qr"]);
        Storage::disk('local')->put('qrcode.png', $qrCode);
        $printer1 = Printers::Where('printer', 'caja')->first();
        $connector = null;
        if( $printer1 )
        {    
        }
        else
        {
            return abort(500);
        }
        if($printer1->printer == "USB")
        {
            $connector = new WindowsPrintConnector( $printer1->ruta );
        }
        else
        {
            $connector = new NetworkPrintConnector( $printer1->ruta , 9100 );
        }
        $printer = new Printer($connector);
        $printer->setFont(1);
        $printer -> setJustification(1);
        $printer -> text(".:BOLETA ELECTRONICA:.\n");
        $printer -> text($request["message"]["serie_documento"]."-".$request["message"]["numero_documento"]."\n\n");
        $printer -> text($request["sunat"]["razon_social"]."\n");
        $printer -> text($request["sunat"]["ruc"]."\n");
        $printer -> text($request["sunat"]["direccion"]."\n\n");
        $printer -> text("Fecha: ".$request["message"]["fecha_de_emision"]."\n\n");
        $printer -> setJustification(0);
        if(array_key_exists( "cliente_numero_de_documento", $request["message"]) )
        {
            $printer -> text("Adquiriente:\n");
            $printer -> text("DNI: ".$request["message"]["cliente_numero_de_documento"]."\n");
            $printer -> text($request["message"]["cliente_denominacion"]."\n\n");
        }
        $printer -> text("Items:\n");
        foreach ($request["message"]["items"] as $key => $value) 
        {
            $line = sprintf('%3.0f %-40.40s %5.2f %13.2f',$value["cantidad"] , eliminar_acentos(  $value["codigo_interno"] ), $value["precio_unitario"], $value["total"]);
            $printer->text($line);
            $printer -> text("\n");
        }
        $printer -> setJustification(0);
        $printer -> text( "TOTAL:"  . "\n");
        $printer -> setJustification(2);
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Inafecta',':', $request["message"]["total_inafecta"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Exonerada',':', $request["message"]["total_exonerada"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total Gravada',':', $request["message"]["total_gravada"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','IGV('.$request["sunat"]["igv"].'%)',':', $request["message"]["total_igv"]) );
        $printer -> text( "\n");
        $printer -> text( sprintf('%-5.40s %-1.05s %13.40s','Total',':', $request["message"]["total"]) );
        $printer -> text( "\n");
        $printer -> text( "\n\n");
        $printer -> setJustification(1);
        $printer -> text( "Representación impresa de la Boleta de Venta\n");
        $printer -> text( $empresa->domain."cpe?t=B&c=".$request["message"]["name"]."\n\n");
        $printer -> text( "Autorizado mediante Resolución de Intendencia No.034-005-0005315\n\n");
        $printer -> text( "Resumen:\n\n");
        $printer -> text( $request["message"]["hash"]."\n\n");
        $img = EscposImage::load(storage_path('app')."\qrcode.png", false);
        $printer -> graphics($img);
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