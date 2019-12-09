<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Thermal;
use Formato;
class PrintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request = null)
    {
        try
        {
            echo exec('git pull');
        }
        catch( Exception $e)
        {

        }
        try
        {
            echo exec('php artisan migrate');
        }
        catch( Exception $e)
        {

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request["tipo"] == "Comanda"){
            return Thermal::Comanda($request);
        }
        if($request["tipo"] == "Pre Cuenta"){
            return Formato::pago($request);  
        }
        if($request["tipo"] == "Boleta"){
            return Formato::pago($request);   
        }
        if($request["tipo"] == "Factura"){
            return Formato::pago($request);      
        }
        if($request["tipo"] == "Sales Invoice"){
            return Formato::pago($request);    
        }
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
