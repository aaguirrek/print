<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Printers;
use Formato;
class PrinterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('select-printer');
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
    public function test(Request $request)
    {
        return Formato::pago($request);
    }
    public function store(Request $request)
    {
        $comanda = Printers::firstOrNew(['ubicacion' => 'Comanda']);
        $comanda ->printer = $request["ccomanda"];
        $comanda ->ruta = $request["comanda"];
        $comanda ->save();


        $caja = Printers::firstOrNew(['ubicacion' => 'Caja']);
        $caja ->printer = $request["ccaja"];
        $caja ->ruta = $request["caja"];
        $caja ->save();

        $comanda = Printers::firstOrNew(['ubicacion' => 'Bebidas']);
        $comanda ->printer = $request["cBebidas"];
        $comanda ->ruta = $request["Bebidas"];
        $comanda ->save();

        return ['comanda' => $comanda, 'caja' => $caja ];
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
