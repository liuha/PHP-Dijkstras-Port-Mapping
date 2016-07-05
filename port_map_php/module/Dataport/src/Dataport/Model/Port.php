<?php
namespace Dataport\Model;

class Port
{
	public $id;
	public $floor;
	public $port;
	public $room;
	public $section;
	public $x_coord;
	public $y_coord;

	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->id 		= (isset($data['id'])) ? $data['id'] : null;
    	$this->floor 	= (isset($data['floor'])) ? $data['floor'] : null;
    	$this->port 	= (isset($data['port'])) ? $data['port'] : null;
    	$this->room 	= (isset($data['room'])) ? $data['room'] : null;
    	$this->section 	= (isset($data['section'])) ? $data['section'] : null;
    	$this->x_coord 	= (isset($data['x_coord'])) ? $data['x_coord'] : null;
    	$this->y_coord 	= (isset($data['y_coord'])) ? $data['y_coord'] : null;
    }
}