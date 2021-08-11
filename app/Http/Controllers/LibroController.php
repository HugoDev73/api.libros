<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

use Carbon\Carbon;

class LibroController extends Controller
{

    public function index()
    {
        $datosLibro = Libro::all();
        return response()->json($datosLibro);
    }

    public function guardar(Request $request)
    {

        $datosLibro = new Libro;

        if ($request->hasFile('imagen')) {
            $nombreOriginal = $request->file('imagen')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp . "_" . $nombreOriginal;
            $carpetaDestino = './upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);

            $datosLibro->titulo = $request->titulo;
            $datosLibro->imagen = ltrim($carpetaDestino, '.') . $nuevoNombre;
            $datosLibro->save();
        }
        return response()->json($nuevoNombre);
    }

    public function ver($id)
    {
        $datosLibro = new Libro;
        $datosEncontrados = $datosLibro->find($id);
        return response()->json($datosEncontrados);
    }

    public function eliminar($id)
    {
        $datosLibro = Libro::find($id);//otra manera de crear una instanciar, llamado metodo directo
        if ($datosLibro) {
            $rutaArchivo = base_path('public') . $datosLibro->imagen;
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            $datosLibro->delete();
        }
        return response()->json("El registro ha sido eliminado");
    }

    public function actualizar(Request $request, $id){
        $datosLibro = Libro::find($id);

        if ($datosLibro) {
            $rutaArchivo = base_path('public') . $datosLibro->imagen;
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            $datosLibro->delete();
        }

        if ($request->hasFile('imagen')) {
            $nombreOriginal = $request->file('imagen')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp . "_" . $nombreOriginal;
            $carpetaDestino = './upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);

            $datosLibro->imagen = ltrim($carpetaDestino, '.') . $nuevoNombre;
            $datosLibro->save();
        }


        if($request->input('titulo')){
            $datosLibro->titulo = $request->input('titulo');
        }
        $datosLibro->save();
        return response()->json("Datos actualizados");
    }
}
