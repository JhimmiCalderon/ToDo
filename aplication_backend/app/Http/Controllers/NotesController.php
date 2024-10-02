<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
     {
         // Asegura que el usuario esté autenticado para todas las acciones excepto 'index' y 'show'
         $this->middleware('auth:api')->except(['index', 'show']);
     }
     public function index(Request $request)
     {
         // Obtener el usuario autenticado
         $user = auth()->user();
     
         // Obtener el criterio de ordenación de la solicitud (por defecto será 'created_at')
         $sortBy = $request->query('sort_by', 'created_at'); // Puede ser 'created_at' o 'due_date'
         $sortOrder = $request->query('sort_order', 'asc'); // Orden por defecto será 'asc'
     
         // Validar los parámetros de ordenación
         if (!in_array($sortBy, ['created_at', 'due_date'])) {
             return response()->json([
                 'error' => 'Valor de sort_by no válido. Debe ser "created_at" o "due_date".'
             ], 400);
         }
     
         if (!in_array($sortOrder, ['asc', 'desc'])) {
             return response()->json([
                 'error' => 'Valor de sort_order no válido. Debe ser "asc" o "desc".'
             ], 400);
         }
     
         // Obtener solo las notas del usuario autenticado, ordenadas por el campo seleccionado
         $notes = Note::where('user_id', $user->id)
             ->orderBy($sortBy, $sortOrder)
             ->get();
     
         return response()->json([
             'data' => $notes,
             'message' => "¡Notas recuperadas con éxito!",
         ]);
     }
     
     

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        //Creamos una variable recoge todos los datos que nos van a enviar
        $inputs = $request->input();
        $inputs['user_id'] = $user->id; // Asignar el usuario autenticado a la nota
        $note = Note::create($inputs);
        return response()->json([
            'data'=>$note,
            'message'=>"¡Nota creada con éxito!",
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $note = Note::find($id);
        if(isset($note)){
            return response()->json([
                'data'=>$note,
                'menssage'=>"Nota encontrada.",
            ]);
        }else{
            return response()->json([
                'error'=>true,
                'message'=>"No existe.",
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = Note::find($id);
        if( isset($note)){
            $note->title = $request-> title;
            $note->description = $request-> description;
            $note->due_date = $request-> due_date;
            $note->tag = $request-> tag;
            $note->image = $request-> image;
            $note->user_id = $request-> user_id;
            if($note->save()){
                return response()->json([
                    'data'=>$note,
                    'message'=>"Nota actualizada con éxito.",
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>"La nota no se actualizó con éxito.",
                ]);
            };
        }else{
            return response()->json([
                'error'=>true,
                'message'=>"La nota no existe.",
            ]);
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = Note::find($id);
        if(isset($note)){
           $res = Note::destroy($id);
           if($res){
            return response()->json([
                'data'=>[],
                'menssage'=>"Nota eliminada con éxito.",
            ]);
        }else{
            return response()->json([
                'error'=>true,
                'message'=>"No existe.",
            ]);
        }
    } }
}
