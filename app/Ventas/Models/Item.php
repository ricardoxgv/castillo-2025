<?php

namespace App\Ventas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'tipo', 'costo', 'user_id', 'estado', 'cuenta_como_persona', 'categoria',
        ];

    public static function crearNuevo($request)
        {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:entrada,servicio,cochera',
            'costo' => 'required|numeric|min:0',
            'categoria' => 'required|in:general,exonerado,invitado',
        ]);

        $item = self::create([
            'nombre' => $validated['nombre'],
            'tipo'   => $validated['tipo'],
            'costo'  => $validated['costo'],
            'categoria' => $validated['categoria'],
            'user_id' => Auth::id(),
            'estado' => true,
            'cuenta_como_persona' => $validated['tipo'] === 'entrada',
        ]);

            
           $item->registrarLog('Creación', 'Ítem creado por el usuario ' . Auth::user()->name);

            return $item;
        }

    public static function obtenerTodos()
        {
            return self::orderBy('nombre')->get();
        }

    public function alternarEstado()
        {
            $this->estado = !$this->estado;
            $this->save();

            $this->registrarLog($this->estado ? 'activado' : 'desactivado');
        }

    public function registrarLog(string $accion, ?string $detalles = null): void
        {
            $usuario = Auth::user();
            

            ItemLog::create([
                'item_id' => $this->id,
                'user_id' => Auth::id(),
                'accion' => $accion,
                'detalles' => $detalles ?? "{$accion} realizada por {$usuario->name}",
            ]);
        }
    public static function taquillaSeparada()
        {
            return [
                'entrada' => self::where('tipo', 'entrada')->where('estado', true)->get(),
                'servicios' => self::where('tipo', 'servicio')->where('estado', true)->get(),
            ];
        }

    public static function cochera()
        {
            return self::where('tipo', 'cochera')->where('estado', true)->get();
        }


}
