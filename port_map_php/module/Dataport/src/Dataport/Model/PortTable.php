<?php

namespace Dataport\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PortTable
{
	protected $tableGateway;
  	public function __construct(TableGateway $tableGateway)
  	{
    	$this->tableGateway = $tableGateway;
  	}

  	//search port by port id
  	public function getPortById($id)
  	{

  		$id = (int)$id;
      $error = null;
  		$portset = $this->tableGateway->select(array('id' => $id ));
      $portset->buffer();

      if ($portset->count() == 0 )
      {
        $error = "The network port requested does not exist!";
        $port = null;
      }
      else
      {
        $port = $portset->current();
        $error = null;
      }
      return array('error' => $error, 'port' => $port);
  	}

  	//search port by Room number, return port list. 
  	public function getPortByRoom($room)
  	{
      $error = null;
  		$portset = $this->tableGateway->select(array('room' => $room ));
      $portset->buffer();
  		if ($portset->count() == 0 ){
        $error = "No dataport for room: $room, please try again!";
        $portset = null;
      }

      return array('error' => $error, 'portset' => $portset);  		
  	}

  	//search port by floor and port number
  	public function getPortByPortandFloor($portnum, $floornum)
  	{
      $error = null;
      $port = null;
      $portnum = (int)$portnum;
      $floornum = (int)$floornum;
      $portset = $this->tableGateway->select(array('port' => $portnum, 'floor' => $floornum ));
      $portset->buffer();
      $port = $portset->current();

  		if (!$port) {
  			//throw new \Exception("Could not find DataPort for Floor $room , DataPort D-: $port");
        $error = "The network port requested does not exist!";
        $port = null;
  		}

      return array('error' => $error, 'port' => $port);
  	}
    //update a existing data port information.
  	public function updatePort(Port $port)
  	{
  		$data = array(
  			'floor'		=> $port->floor,
  			'port'  	=> $port->port,
  			'room'  	=> $port->room,
  			'section'  	=> $port->section,
  			'x_coord'  	=> $port->x_coord,
  			'y_coord'  	=> $port->y_coord,
  		);
  		$id = (int)$port->id;
  		//if($this->getPortById($id)['error'] != null){
  			$this->tableGateway->update($data, array('id' => $id));
  		//}else {
  		//	throw new \Exception ("Port id does not exist");
  		//}
  	}

}