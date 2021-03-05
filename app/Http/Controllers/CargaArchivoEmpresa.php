<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuentas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;

class CargaArchivoEmpresa extends Controller
{

    public function store(Request $request){
        Log::info("Info");
        $path = public_path().'\uploads';
            $doc = $request->file('file');
            //Log::info($path);

            $fileName = $doc->getClientOriginalName();
          //  Log::info($fileName);


                //dd("subido y guardado");

                $carpeta = "uploads";
/*                  $file = fopen(public_path($carpeta."/".$fileName), 'r') or exit("Unable to open file!");
 */
                 //SE ABRE EL ARCHIVO EN MODO LECTURA
                $file = fopen(public_path($carpeta."/".$fileName), 'r') or exit("Unable to open file!");


                 if( $file == false ) {
                    Log::info("Error abriendo el archivo");
                }
                  else
                  {   
                    //Si no esta vacio el directorio muevo el archivo a carpeta Public
                    $directorio = $doc->move($path, $fileName);

                    Cuentas::truncate();

                    $data = array();

                    $arrayTxa = array();
                    $arrayGts = array();
                    $arrayEmp = array();

                    $acountUptd = new Cuentas;

                    $i = 1;
                    while(!feof($file)) {
                        
                        $data  = explode("|", fgets($file));
                        //SI QUEREMOS VER TODO EL CONTENIDO EN BRUTO:
                        //Log::info($data[0]."-".$data[1]."-".$data[2]);


                         $arrayTxa[$i]  = $data[0];
                         $arrayGts[$i]  = $data[1];
                         $arrayEmp[$i]  = $data[2];

                        
                        $i++; 
                        
                    }

                   // Log::info($i);

                    $respuesta = array();

                     for($k = 1; $k<=sizeof($arrayTxa); $k++){
                     // Log::info($arrayTxa[$k]);
                 
                        $respuesta[] = [
                            'cuenta_txa' => $arrayTxa[$k],
                            'cuenta_gts' => $arrayGts[$k],
                            'empresa'    => $arrayEmp[$k]
                        ];

                        //Log::debug("Longitud de array".$k);


                    }  

                    
                     foreach(array_chunk($respuesta,5000, true) as $t) {

                        DB::table('cuentas')->insert($t);

                     } 
                    //Cuentas::insert($respuesta);


                        }


                return view('admin.add.empresa.index');
           
    
}

public function listCompany(Request $request){
 Log::info("Lista de cuentas");
 
    if($request->ajax()){ 
    
        $data = Cuentas::query()
        ->select([
            'cuentas.id AS id',
            'cuentas.cuenta_txa AS cuenta_txa',
            'cuentas.cuenta_gts AS cuenta_gts',
            'cuentas.empresa AS empresa',

        ])
        ->groupBy('cuentas.id', 'cuentas.cuenta_txa','cuentas.cuenta_gts', 'cuentas.empresa')
        ->orderByRaw('id');

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    
                  /*   $button = '<button type="button" name = "add" class="btn btn btn-sm addAcount" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-node-plus-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11 13a5 5 0 1 0-4.975-5.5H4A1.5 1.5 0 0 0 2.5 6h-1A1.5 1.5 0 0 0 0 7.5v1A1.5 1.5 0 0 0 1.5 10h1A1.5 1.5 0 0 0 4 8.5h2.025A5 5 0 0 0 11 13zm.5-7.5a.5.5 0 0 0-1 0v2h-2a.5.5 0 0 0 0 1h2v2a.5.5 0 0 0 1 0v-2h2a.5.5 0 0 0 0-1h-2v-2z"/>
                  </svg></a></button>';
                    $button .='<button type="button" name = "list" class="btn btn listAcount" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-checklist" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path fill-rule="evenodd" d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                  </svg></a></button>';
                    $button .='<button type="button" name = "list" class="btn btn editInfo" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg></a></button>';*/ 


                    return $button; 
                })
                ->rawColumns(['action'])
                ->make(true);
    
    
    }


}


}