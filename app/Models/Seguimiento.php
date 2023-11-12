<?php

namespace App\Models;

class Seguimiento
{
    public $paisOrigen;
    public $paisDestino;
    public $estadoActual;
    public $diasEnTransito;
    public $carrierCode;
    public $carrierName;
    public $eventos = [];
    private $contenido;

    public function __construct(String $resJson)
    {
        $this->contenido = json_decode($resJson);
        $this->paisDestino = $this->getPaisDestino();
        $this->paisOrigen = $this->getPaisOrigen($this->contenido);
        $this->estadoActual = $this->getEstadoActual();
        $this->diasEnTransito = $this->getDiasEnTransito();
        $eventos = $this->getEventos();
        $this->carrierCode = $this->getCarrier();
        $this->carrierName = $this->getCarrierName();
        $this->eventos = $this->getEventosConMenosDatos($eventos);
    }

    private  function getPaisOrigen($contenido)
    {
        return $this->contenido->data->accepted[0]->track_info->shipping_info->recipient_address->country;
    }

    private  function getPaisDestino(){
        return $this->contenido->data->accepted[0]->track_info->shipping_info->shipper_address->country;
    }

    private  function getEstadoActual()
    {
        return $this->contenido->data->accepted[0]->track_info->latest_status->status;
    }

    private  function getDiasEnTransito()
    {
        return $this->contenido->data->accepted[0]->track_info->time_metrics->days_of_transit;
    }

    private  function getEventos()
    {
        return $this->contenido->data->accepted[0]->track_info->tracking->providers[0]->events;
    }
    
    private  function getCarrier()
    {
        return $this->contenido->data->accepted[0]->carrier;
    }

    private  function getCarrierName()
    {
        return $this->contenido->data->accepted[0]->track_info->tracking->providers[0]->provider->name;
    }

    private  function getEventosConMenosDatos($eventos)
    {
        return array_map(function ($evento) {
            return [
                'description' => $evento->description,
                'location' => $evento->location,
                'time_utc' => $evento->time_utc,
            ];
        }, $eventos);
    }

    static function getTransportistas($resJson)
    {
        $carriers = json_decode($resJson);
        return array_map(function ($carrier) {
            return [
                'name' => $carrier->_name,
                'key' => $carrier->key,
                'url' => $carrier->_url,
            ];
        }, $carriers);
    }
}
