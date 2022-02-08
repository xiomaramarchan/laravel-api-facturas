<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ItemFactura;
use Illuminate\Support\Facades\Auth;
use App\Models\Factura;
use App\Models\Item;

class FacturaController extends Controller
{
    //
    //
    //Crear la Factura
    public function crearFactura(Request $request)
    {   
        date_default_timezone_set('America/Caracas');
         
        
       
        $rules = [
          'comprador_nombre' => 'required|string|max:100', 
          'comprador_nit' => 'required|numeric',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(['created' => false, 'error'=>$validator->errors()],422);
        }
        
        $permitted_chars = '0123456789'; 
        $numeroFactura = substr(str_shuffle($permitted_chars),0, 8);
        
        

        $factura = new Factura();
        $factura->numero = $numeroFactura;
        $factura->user_id = Auth::user()->id;        
        $factura->comprador_nombre = $request->input('comprador_nombre');
        $factura->comprador_nit = $request->input('comprador_nit');
        $factura->fecha = date("d/m/Y");
        $factura->hora = date("H:i");
        $factura->save();      

        //Buscar el id de la ultima factura generada para registrar el detalle en la tabla item_facturas
        $facturaGenerada = Factura::latest('id')->first();

              
        //obtengo los item que provienen de un json el frontend
        $items = json_decode($request->input('items'), true );
        
        foreach ($items as $item) {
            
            $valuesAux = [];
            $valuesAux['factura_id'] = $facturaGenerada->id;
            $valuesAux['item_id'] = $item['id'];          
            $valuesAux['cantidad'] = $item['cantidad'];
            $valuesAux['precio_venta'] = $item['precio_venta'];
            $valuesAux['valor_total'] = ($item['precio_venta'] * $item['cantidad']);
            $valuesAux['created_at'] = date('Y-m-d H:m:s');
            $valuesAux['updated_at'] = date('Y-m-d H:m:s');
            $values[] = $valuesAux;
           
        }

         
        ItemFactura::insert($values);

        return response()->json(['message'=>'Factura generada'],200);
    }

    //Mostrar todas las facturas generadas

    public function mostrarFacturasGeneradas()
    {   
         
        $facturas=Factura::join("users", "users.id", "=", "facturas.user_id")
       ->select("facturas.*",'users.name','users.nit')
       ->get();
    
        for($i = 0; $i < sizeof($facturas); $i++)
        {   
            //Obtengo los items asociados a cada factura a través del metodo items del modelo Factura
            $facturas[$i]->items;
         
                    
            $array[] = $facturas[$i];
         
        }
        if(sizeof($facturas) == 0)
        {
            return response()->json(['message'=>"No existen registro de Facturas"],404);
        }else{
            return response()->json(['facturas'=>$array],200);
        }

    }

    public function mostrarFacturaId($id)
    {
       
        $factura=Factura::join("users", "users.id", "=", "facturas.user_id")
                        ->select("facturas.*",'users.name','users.nit')                        
                        ->find($id);
        
        if(!$factura){
            return response()->json(['error'=>'Esta factura no se encuentra registrada'],404);
        }else{      
            
            $itemsFactura = Factura::join('item_facturas', 'facturas.id', '=', 'item_facturas.factura_id')
            ->join('items', 'item_facturas.item_id', '=', 'items.id')
            ->select("items.*",'item_facturas.precio_venta','item_facturas.cantidad','item_facturas.valor_total')
            ->where('facturas.id','=',$id)
            ->get();

            

            //$factura->items;
            
            return response()->json(['factura'=> $factura,'itemsFactura'=> $itemsFactura],200);
        }
       
    }

    public function editarFactura(Request $request, $id)
    {   
        date_default_timezone_set('America/Caracas');

        $factura = Factura::find($id);
        $rules = [
          //'number' => 'required|integer|unique:facturas,numero,'.$factura->id, 
          //'user_id' => 'required',         
          'comprador_nombre' => 'required|string|max:255',
          'comprador_nit' => 'required', 
          'fecha' => 'required',  
          'hora' => 'required',        
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(['created' => false, 'error'=>$validator->errors()],422);
        }

        Factura::where('id',$id)-> update([
            //'numero' => $request->input('number'),           
            'comprador_nombre' => $request->input('comprador_nombre'),
            'comprador_nit' => $request->input('comprador_nit'),
            'fecha' => $request->input('fecha'),
            'hora' => $request->input('hora'),

        ]);

        return response()->json(['message'=>'Factura editada con éxito'],200); 

    }

}
