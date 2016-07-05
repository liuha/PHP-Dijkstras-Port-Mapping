<?php

namespace Dataport\Model;

use Zend\Db\TableGateway\TableGateway;

class EdgeTable
{
	protected $tableGateway;
  	public function __construct(TableGateway $tableGateway)
  	{
    	$this->tableGateway = $tableGateway;
  	}

  	//get all edges to given node. 
  	public function getEdgebyToNode($ToNode_id)
  	{
      $edgeset = null;
  		$edgeset = $this->tableGateway->select(array('ToNode_id' => $ToNode_id));
      $edgeset->buffer();
  		return $edgeset;
  	}
    //get all edges start from the given node. 
  	public function getEdgebyFromNode($FromNode_id)
  	{
      $edgeset = null;
  		$edgeset = $this->tableGateway->select(array('FromNode_id' => $FromNode_id));
      $edgeset->buffer();
  		return $edgeset;
  	}
    //get the edge from gvien node to given node.
    public function getEdge($FromNode_id, $ToNode_id)
    {
      $edgeset = $this->tableGateway->select(array('FromNode_id' => $FromNode_id, 'ToNode_id' => $ToNode_id));
      $edgeset->buffer();
      $edge = $edgeset->current();
      return $edge;
    }
    //add edges. edge is undirected, therefore, need to add A=>B AND B=>A at a same time. 
    public function addEdge($FromNode_id, $ToNode_id)
    {
      $edge1 = array(
                'FromNode_id'    => $FromNode_id,
                'ToNode_id'   => $ToNode_id,
            );
      $this->tableGateway->insert($edge1);
      $edge2 = array(
                'FromNode_id'    => $ToNode_id,
                'ToNode_id'   => $FromNode_id,
            );
      $this->tableGateway->insert($edge2);
    }

    //delete edges A <=> B;
    public function deleteEdge($FromNode_id, $ToNode_id)
    {
      $edge1 = array(
                'FromNode_id'    => $FromNode_id,
                'ToNode_id'   => $ToNode_id,
            );
      $this->tableGateway->delete($edge1);
      $edge2 = array(
                'FromNode_id'    => $ToNode_id,
                'ToNode_id'   => $FromNode_id,
            );
      $this->tableGateway->delete($edge2);
    }
}