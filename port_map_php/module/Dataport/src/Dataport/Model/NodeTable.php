<?php

namespace Dataport\Model;

use Zend\Db\TableGateway\TableGateway;

class NodeTable
{
	protected $tableGateway;
  	public function __construct(TableGateway $tableGateway)
  	{
    	$this->tableGateway = $tableGateway;
  	}

    //search node by name and floor number
  	public function getNode($name, $floor)
  	{
  	
  		$nodeset = $this->tableGateway->select(array('name' => $name, 'floor' => $floor ));
      $nodeset->buffer();
      $node = $nodeset->current();
      return $node;
  	}

    //search node by floor number
    public function getNodebyFloor($floor)
    {
       $nodeset = $this->tableGateway->select(array('floor' => $floor));
       $nodeset->buffer();
       return $nodeset; 
    }

    //search node by node id
    public function getNodebyId($id)
    {
      $id = (int)$id;
      $nodeset = $this->tableGateway->select(array('id' => $id));
      $nodeset->buffer();
      $node = $nodeset->current();
      return $node;
    }

    //update node coordinates
    public function updateNode(Node $node)
    {
      $data = array(
          'name' => $node->name,
          'floor' => $node->floor,
          'x_coord'   => $node->x_coord,
          'y_coord'   => $node->y_coord,
        );
      $id = (int)$node->id;
      $this->tableGateway->update($data, array('id' => $id));

    }

    public function addNode($data)
    {
        $this->tableGateway->insert($data);
    }
}