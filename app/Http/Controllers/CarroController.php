<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carro;
use GuzzleHttp\Client;


class CarroController extends Controller
{

    private $client;
    private $token;

    public function __construct()
    {
        $this->middleware('auth');

        $this->client = new Client(['base_uri' => 'https://isapi.intelisis-solutions.com']);
        
        $this->token = $this->getTokenApi();

    }

    public function getTokenApi(){

        $response = $this->client->request('POST', '/auth/', [
            'form_params' => [
                'username' => 'TEST001',
                'password' => 'intelisis'
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        return $result['token'];


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        

        $response = $this->client->request('POST', '/api/VehicleInv/', [
            'headers' => [
                'Authorization' => 'Token ' . $this->token
            ],
            'form_params' => [
                'dealerid' => '31',
                'condicion' => 'Todos'
            ]
        ]);

        $carros = json_decode($response->getBody(), true);

        //echo json_encode($carros['data']);
        
        //return;
    

        //$carros = Carro::all();
        return view('carro.index')->with('carros', $carros['data']);

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('carro.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carros = new Carro();

        $carros->linea = $request->get('linea');
        $carros->catalogo = $request->get('catalogo');
        $carros->modelo = $request->get('modelo');
        $carros->serie = $request->get('serie');
        $carros->color = $request->get('color');
        $carros->ubicacion = $request->get('ubicacion');
        $carros->diasPiso = $request->get('diasPiso');
        $carros->costo = $request->get('costo');
        $carros->estatus = $request->get('estatus');
        $carros->comentarios = $request->get('comentarios');

        $carros->save();
        
        return redirect('carros');
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
        $carro = Carro::find($id);
        return view('carro.edit')->with('carro', $carro);
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
        $carro =Carro::find($id);

        $carro->linea = $request->get('linea');
        $carro->catalogo = $request->get('catalogo');
        $carro->modelo = $request->get('modelo');
        $carro->serie = $request->get('serie');
        $carro->color = $request->get('color');
        $carro->ubicacion = $request->get('ubicacion');
        $carro->diasPiso = $request->get('diasPiso');
        $carro->costo = $request->get('costo');
        $carro->estatus = $request->get('estatus');
        $carro->comentarios = $request->get('comentarios');

        $carro->save();
        
        return redirect('/carros');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro =Carro::find($id);
        $carro->delete();

        return redirect('/carros');
    }
}
