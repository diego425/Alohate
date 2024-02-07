<?php

namespace App\Http\Controllers;

use App\Models\Renta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
// use App\Mail\pagosMail;
use Maatwebsite\Excel\Facades\Excel;
use App\Providers\simple_html_dom;

class MercadoPagoController extends Controller
{
    public function obtenerPago($id){
        $token = MercadoPagoController::traerToken();
        $regreso = array(
            "orden" => "",
            "pagos" => array(),
            "error" => "",
            "phone" => ""
        );

        if (!empty($id)) {
            if (!empty($token[0]->value)) {
                $token = $token[0]->value;
                $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$id.'',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer '.$token.''
                        ),
                    ));
                    $json = curl_exec($curl);
                curl_close($curl);
                
                $json_payment = json_decode($json);
                
                if (!empty($json_payment->error)) {
                    $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/search?sort=date_approved&criteria=desc&range=date_created&begin_date=NOW-3MONTHS&end_date=NOW&order.id='.$id.'',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer '.$token.''
                            ),
                        ));
                        $jsonSearch = curl_exec($curl);
                    curl_close($curl);                
                    $json_search = json_decode($jsonSearch);
    
                    if (!empty($json_search->paging->total)) {
                        $sucess = 0;
                        foreach ($json_search->results as $key => $item) {
                            if (!empty($item->status)) {
                                if ($item->status == "approved") {
                                    $sucess++;
        
                                    $dato = array(
                                        "respuestaML" => "",
                                        "total_paid_amount" => 0,
                                        "status" => "",
                                        "status_detail" => "",
                                        "error" => "",
                                        "id" => "",
                                        "date_approved" => ""
                                    );
                    
                                    $dato["respuestaML"] = $item;
                    
                                    if (!empty($item->status)) {
                                        $dato["status"] = $item->status;
                                    }else{
                                        $dato["error"] = "no tiene el campo status";
                                    }
                                    if (!empty($item->status_detail)) {
                                        $dato["status_detail"] = $item->status_detail;
                                    }else{
                                        $dato["error"] = "no tiene el campo status_detail";
                                    }
                                    if (!empty($item->transaction_details->total_paid_amount)) {
                                        $dato["total_paid_amount"] = $item->transaction_details->total_paid_amount;
                                    }else{
                                        $dato["error"] = "no tiene el campo total_paid_amount";
                                    }
                                    if (!empty($item->id)) {
                                        $dato["id"] = $item->id;
                                        if (MercadoPagoController::verificarPago($item->id) === 'no') {
                                            $regreso["error"] = "Pago ya relacionado.";
                                        }
                                    }else{
                                        $dato["error"] = "no tiene el campo id";
                                    }
                                    if (!empty($item->date_approved)) {
                                        $dato["date_approved"] = $item->date_approved;
                                    }else{
                                    }
                    
                                    array_push($regreso["pagos"],$dato);
                                }
                            }
                        }
    
                        if ($sucess == 0) {
                            $regreso["error"] = "No se encontró ningún pago approved, verifique que sea el número de orden o el id del pago correcto.";
                        }
                    }else{
                        $regreso["error"] = "No se encontró ningún pago, verifique que sea el número de orden o el id del pago correcto.";
                    }
                }else{
                    $dato = array(
                        "respuestaML" => "",
                        "total_paid_amount" => 0,
                        "status" => "",
                        "status_detail" => "",
                        "error" => "",
                        "id" => "",
                        "date_approved" => ""
                    );
    
                    $dato["respuestaML"] = $json_payment;
    
                    if (!empty($json_payment->status)) {
                        $dato["status"] = $json_payment->status;

                        if ($json_payment->status == "approved") {
                        }else{
                            $regreso["error"] = "El status del pago no esta aprobado.";
                        }
                    }else{
                        $dato["error"] = "no tiene el campo status";
                    }
                    if (!empty($json_payment->status_detail)) {
                        $dato["status_detail"] = $json_payment->status_detail;
                    }else{
                        $dato["error"] = "no tiene el campo status_detail";
                    }
                    if (!empty($json_payment->transaction_details->total_paid_amount)) {
                        $dato["total_paid_amount"] = $json_payment->transaction_details->total_paid_amount;
                    }else{
                        $dato["error"] = "no tiene el campo total_paid_amount";
                    }
                    if (!empty($json_payment->id)) {
                        $dato["id"] = $json_payment->id;
                        if (MercadoPagoController::verificarPago($json_payment->id) === 'no') {
                            $regreso["error"] = "Pago ya relacionado.";
                        }else{
                            if ($json_payment->status == "approved") {
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://api.mercadopago.com/merchant_orders/'.$json_payment->order->id.'',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'GET',
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json',
                                        'Authorization: Bearer '.$token.''
                                    ),
                                ));
                                $responseOrders = curl_exec($curl);
                                $responseOrders = json_decode($responseOrders,true);
                                curl_close($curl);

                                if (!empty($responseOrders["shipments"][0]["id"])) {
                                    $regreso["phone"] = $responseOrders["shipments"][0]["receiver_address"]["phone"];
                                    $tokenML = DB::select("SELECT * FROM `token`");
                                    $tokenML = $tokenML[0]->Valor;
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => 'https://api.mercadolibre.com/shipments/'.$responseOrders["shipments"][0]["id"].'',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                        CURLOPT_HTTPHEADER => array(
                                            'Authorization: Bearer '.$tokenML.''
                                        ),
                                    ));
                                    $responseGuia = curl_exec($curl);
                                    curl_close($curl);
                                    $regreso["orden"] = json_decode($responseGuia,true);
                                }
                            }
                        }
                    }else{
                        $dato["error"] = "no tiene el campo id";
                    }
                    if (!empty($json_payment->date_approved)) {
                        $dato["date_approved"] = $json_payment->date_approved;
                    }else{
                        // $dato["error"] = "no tiene el campo id";
                    }
    
                    array_push($regreso["pagos"],$dato);
                }
            }else{
                $regreso["error"] = "Token vacio.";
            }
        }else{
            $regreso["error"] = "Número vacio.";
        }

        return $regreso;
    }

    public static function traerToken() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://af86y478.sonarmx.store/plugins/ajax/Prestashop/traerMercadoPago.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public static function verificarPago($id) {
        $regreso = 'ok';
        $pagos = DB::select("SELECT * FROM `venta_pago`
        WHERE Referencia LIKE '%".$id."%'
        ORDER BY `venta_pago`.`id_venta_pago`  DESC;", []);
        // $pagos = DB::select("SELECT * FROM `venta_pago`
        // WHERE Referencia LIKE '%68719653232323%'
        // ORDER BY `venta_pago`.`id_venta_pago`  DESC;", []);

        if (count($pagos) == 0) {
        }else{
            $regreso = 'no';
        }

        return $regreso;
    }

    public function webhook(Request $request) {
        if (!empty($request["type"])) {
            switch($request["type"]) {
                case "payment":
                    $id = $request["data"]["id"];
                    $insert = DB::table('call_backs_mercado_pago')->insert([
                        'type' => $request["type"],
                        'id' => $id,
                        'dateInsert' => date("Y-m-d h:i:s"),
                        'data' => json_encode($request->all())
                    ]);

                    if ($insert) {
                        $token = MercadoPagoController::traerToken();
                        $token = $token[0]->value;
                        $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$id.'',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json',
                                    'Authorization: Bearer '.$token.''
                                ),
                            ));
                            $payment = curl_exec($curl);
                        curl_close($curl);
                        $json_payment = json_decode($payment);

                        if (!empty($json_payment->external_reference)) {
                            $affected = DB::table('cobro_renta')
                            ->where('Id_cobro_renta', $json_payment->external_reference)
                            ->update(['id_pago_mp' => $id]);

                            if ($json_payment->status == "approved") {
                                //Mandar notificacion por correo
                                $objDemo = new \stdClass();
                                $objDemo->sender = 'Sonarmx Store';
                                $objDemo->id = $id;
                                $objDemo->Id_Cotizacion = $json_payment->external_reference;
                                $objDemo->Comprador = $json_payment->payer->email;
    
                                $emails = ['sonarmxsistemas9@gmail.com','ventas@sonarmx.com'];
                                // Mail::to($emails)->send(new pagosMail($objDemo));
                            }
                        }

                        echo "ok";
                    }else{
                        echo "error";
                    }
                    break;
                case "plan":
                    $id = $request["data"]["id"];
                    echo "ok";
                    break;
                case "subscription":
                    $id = $request["data"]["id"];
                    echo "ok";
                    break;
                case "invoice":
                    $id = $request["data"]["id"];
                    echo "ok";
                    break;
                case "point_integration_wh":
                    // $_POST contiene la informaciòn relacionada a la notificaciòn.
                    break;
            }
        }/* else{
            //Mandar notificacion por correo
            $objDemo = new \stdClass();
            $objDemo->sender = 'Sonarmx Store';
            $objDemo->id = "1316219462";
            $objDemo->Id_Cotizacion = "5846";
            $objDemo->Comprador = "luisferchoq46@gmail";

            Mail::to("luisferchoq46@gmail.com")->send(new pagosMail($objDemo));
        } */
    }

    //Checkout Pro
    public function generarLinkPago(Request $request){
        $iva = 1.16;
        if (!empty($request["type"])) {
            switch($request["type"]) {
                case "ConEnvio":
                    break;
                case "SinEnvio":
                    $idRenta = $request["IdRenta"];

                    $detalleRenta = DB::select("SELECT cobro_renta.*
                        FROM `cobro_renta`
                    WHERE Id_cobro_renta = ?", [$idRenta]);

                    // return redirect()->back()->with('error', 'No se puede pagar por este medio, intente mas tarde');
                    if (empty($detalleRenta[0]->preference_mp)) {
                        //"notification_url" => "",
                        $jsonEnvio = array(
                            "expiration_date_to" => date('Y-m-d',strtotime(date("Y-m-d").'2 day'))."T06:00:00.000-00:00",
                            "items" => array(),
                            "external_reference" => "renta-".$idRenta,
                            "back_urls" => array(
                                "success" => url()->previous()
                            )
                        );
                        
                        $total_amount = 0;
                        $picture_url = null;
                        foreach ($detalleRenta as $key => $item) {
                            $total_amount += $item->Monto_total;
                            
                            $itemArr = array(
                                "id" => $item->Id_cobro_renta,
                                "title" => $request["title"],
                                "description" => null,
                                "category_id" => "Estancia",
                                "quantity" => 1,
                                "picture_url" => $picture_url,
                                "currency_id" => "MXN",
                                "unit_price" => $total_amount,
                            );
                            array_push($jsonEnvio["items"],$itemArr);
                        }

    
                        $jsonEnvio = json_encode($jsonEnvio);
                        
                        $token = "APP_USR-3075988642504072-112417-ff97b9cfc9f031cc6c24f468c9c7696d-349822028";
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $jsonEnvio,
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer '.$token.''
                            ),
                        ));
                        $responseLink = curl_exec($curl);
                        curl_close($curl);

                        $insertcall = DB::insert("INSERT INTO `call_backs_mercado_pago` (`text`) VALUES (?)",[$responseLink]);
    
                        $responseLink = json_decode($responseLink);
                        if (!empty($responseLink->id)) {
                            $affected = DB::table('cobro_renta')
                                ->where('Id_cobro_renta', $idRenta)
                            ->update(['preference_mp' => $responseLink->id]);
                            
                            if (!empty($responseLink->sandbox_init_point)) {
                                return redirect()->to($responseLink->sandbox_init_point);
                            } else {
                                return redirect()->back()->with('message', 'Vuelva a dar click en Mercado pago por favor.');
                            }                            
                        }else{
                            return redirect()->back()->with('error', 'No se puede pagar por este medio, intente mas tarde');
                        }

                        print_r($jsonEnvio);
                    }else{
                        return redirect()->back()->with('error', 'No se puede pagar por este medio, intente mas tarde');
                    }

                    break;
                case "actualizar":
                    /* $idRenta = $request["IdRenta"];

                    $detalleRenta = DB::select("SELECT cobro_renta.*
                        FROM `cobro_renta`
                    WHERE Id_cobro_renta = ?", [$idRenta]);

                    if (!empty($detalleRenta[0]->preference_mp)) {
                        $jsonEnvio = array(
                            "expiration_date_to" => date('Y-m-d',strtotime(date("Y-m-d").'1 day'))."T06:00:00.000-00:00",
                            "items" => array(),
                            "notification_url" => "https://af86y478.sonarmx.store/mercadopago/webhook/sdS56d5G5f@9e5ss33eg567gn7562"
                        );
                        
                        $total_amount = 0;
                        $picture_url = null;
                        foreach ($detalleRenta as $key => $item) {
                            $title = $item->Titulo;
                            
                            $unit_price = (float)bcdiv((($item->Precio_momento*$iva)*$item->Cantidad),1,2);
                            $total_amount += $unit_price;
                        }

                        $itemArr = array(
                            "id" => $item->Id_Articulo_Inventario,
                            "title" => "Cotización ".$idRenta,
                            "description" => null,
                            "category_id" => "musical",
                            "quantity" => 1,
                            "picture_url" => $picture_url,
                            "currency_id" => "MXN",
                            "unit_price" => $total_amount,
                        );
                        array_push($jsonEnvio["items"],$itemArr);
    
                        $jsonEnvio = json_encode($jsonEnvio);
    
                        $token = MercadoPagoController::traerToken();
                        $token = $token[0]->value;
                        $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences/'.$detalleRenta[0]->preference_mp.'',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'PUT',
                                CURLOPT_POSTFIELDS => $jsonEnvio,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json',
                                    'Authorization: Bearer '.$token.''
                                ),
                            ));
                            $responseLink = curl_exec($curl);
                            $responseLink = json_decode($responseLink);
                        curl_close($curl);
    
                        if (!empty($responseLink->id)) {
                            $affected = DB::table('cobro_renta')
                                ->where('Id_cobro_renta', $idRenta)
                            ->update(['preference_mp' => $responseLink->id]);
    
                            return json_encode($responseLink);
                        }else{
                            print_r(json_encode($responseLink));
                        }
                    }else{
                        echo 'Ya se creo el link para esta cotización';
                    } */
                break;
            }
        }
    }

    public static function publicacionesRelacionadas($Id_Articulo) {
        $regreso = array(
            "id" => "",
            "img" => ""
        );
        $sku = '';
        if (substr($Id_Articulo,0,3) == 'PAK') {
            $traerArt = DB::select('SELECT * FROM `combinacion` WHERE Articulo_Combinacion = ?', [$Id_Articulo]);
            $traerArt = json_decode(json_encode($traerArt));
            if (count((array)$traerArt) > 0) {
                $sku = $traerArt[0]->Articulo_Componente;
            }
        } else {
            $sku = $Id_Articulo;
        }

        $catalogo = DB::table('catalogo_inventario')
            ->select("pm.*","c.*")
            ->selectRaw('(select (select count(*) from escaneos_de_competencia where escaneos_de_competencia.Id_MercadoLibre = catalogo_inventario.Id_MercadoLibre)) as totalCompetidores')
            ->join(DB::raw("(SELECT * FROM inventario WHERE Id_Bodega = 1) as i"), function($join){
                    $join->on('i.Id_Articulo', '=', 'catalogo_inventario.Id_Articulo');
            })
            ->join('articulo as a', 'a.Id_Articulo', '=', 'catalogo_inventario.Id_Articulo')
            ->join('publicaciones_mercado as pm', 'pm.Id_MercadoLibre', '=', 'catalogo_inventario.Id_MercadoLibre')
            ->leftJoin(DB::raw("(SELECT * FROM combinacion) as c"),function($join){
                    $join->on("c.Articulo_Combinacion","=","catalogo_inventario.Id_Articulo");
            })
            ->where('c.Articulo_Componente', 'LIKE',"%".$sku."%")->orWhere('i.Id_Articulo', '=', $sku)
            ->orderBy('catalogo_inventario.Id_MercadoLibre','ASC')
        ->get();
        $catalogo = json_decode(json_encode($catalogo));

        if (!empty($catalogo[0]->Id_MercadoLibre)) {
            $regreso["id"] = $catalogo[0]->Id_MercadoLibre;
            $regreso["img"] = $catalogo[0]->imagen1;
        }

        return $regreso;
    }

    public static function acomodarDatosVentaCotizacion($Id_Venta) {
        if (!empty($Id_Venta)) {
            $traerCotizacion = DB::select('SELECT cobro_renta.*,venta.*,pedido.No_Orden FROM `cotizacion`
            LEFT JOIN venta ON venta.Id_Venta = cotizacion.Id_Venta
            LEFT JOIN pedido ON pedido.Id_Venta = venta.Id_Venta
            WHERE cotizacion.Id_Venta = ? ORDER BY `Id_Cotizacion` DESC;', [$Id_Venta]);
    
            if (!empty($traerCotizacion[0]->id_pago_mp)) {
                $token = MercadoPagoController::traerToken();
                $token = $token[0]->value;
                $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$traerCotizacion[0]->id_pago_mp.'',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer '.$token.''
                        ),
                    ));
                    $payment = curl_exec($curl);
                curl_close($curl);
                $json_payment = json_decode($payment);
    
                if (!empty($json_payment->status)) {
                    if ($json_payment->status == "approved") {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.mercadopago.com/merchant_orders/'.$json_payment->order->id.'',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer '.$token.''
                            ),
                        ));
                        $responseOrders = curl_exec($curl);
                        $responseOrders = json_decode($responseOrders);
                        curl_close($curl);
    
                        if (!empty($responseOrders->shipments[0]->id)) {
                            $affected1 = DB::table('call_backs_mercado_pago')
                            ->where('id', $traerCotizacion[0]->id_pago_mp)
                            ->update(
                                [
                                    'shipment_id' => $responseOrders->shipments[0]->id,
                                    'order_id' => $json_payment->order->id,
                                    'comision' => $json_payment->fee_details[0]->amount,
                                    'costo_envio' => $responseOrders->shipments[0]->shipping_option->list_cost
                                ]
                            );
                            
                            if (!empty($traerCotizacion[0]->No_Orden) && $responseOrders->shipments[0]->shipping_mode == "me2") {
                                $affected2 = DB::table('pedido')
                                ->where('No_Orden', $traerCotizacion[0]->No_Orden)
                                ->update(
                                    [
                                        'No_de_guia' => $responseOrders->shipments[0]->id,
                                        'Pedido_Sonar' => "MercadoEnvios"
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    function cambiarStatusPedido($Id_Venta) {
        if (!empty($Id_Venta)) {
            $traerDatos = DB::select('SELECT cobro_renta.*,venta.*,pedido.No_Orden FROM `cotizacion`
            LEFT JOIN venta ON venta.Id_Venta = cotizacion.Id_Venta
            LEFT JOIN pedido ON pedido.Id_Venta = venta.Id_Venta
            WHERE venta.Id_Venta = ?;', [$Id_Venta]);

            foreach ($traerDatos as $key => $item) {
                $update = DB::table('pedido')
                ->where('Id_Venta', $Id_Venta)
                ->update(['Estado_Pedido' => "SonarC"]);
            }
        }
    }

    public function process_payment(Request $request,$idCobro) {
    }
}