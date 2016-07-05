<?php
namespace Dataport\Model;

class Node
{
	public $id;
    public $name;
	public $floor;
	public $x_coord;
	public $y_coord;

	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->id 	    = (isset($data['id'])) ? $data['id'] : null;
        $this->name     = (isset($data['name'])) ? $data['name'] : null;       
    	$this->floor 	= (isset($data['floor'])) ? $data['floor'] : null;
    	$this->x_coord 	= (isset($data['x_coord'])) ? $data['x_coord'] : null;
    	$this->y_coord 	= (isset($data['y_coord'])) ? $data['y_coord'] : null;
    }
}