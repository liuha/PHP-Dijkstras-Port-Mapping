<?php
namespace Dataport;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;

use Dataport\Model\Port;
use Dataport\Model\PortTable;
use Dataport\Model\Node;
use Dataport\Model\NodeTable;
use Dataport\Model\Edge;
use Dataport\Model\EdgeTable;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        
        $sharedEventManager = $eventManager->getSharedManager(); // The shared event manager
        
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) {
            $controller = $e->getTarget(); // The controller which is dispatched
            $controllerName = $controller->getEvent()->getRouteMatch()->getParam('controller');
             
            if (in_array($controllerName, array('Dataport\Controller\Node'))) {
                $controller->layout('layout/node-layout');
            }
        });
        

    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(),
            'factories' => array(
                // DB
                'PortTable' =>  function($sm) {
                    $tableGateway = $sm->get('PortTableGateway');
                    $table = new PortTable($tableGateway);
                    return $table;
                },
                'PortTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Port());
                    return new TableGateway('networkport', $dbAdapter, null, $resultSetPrototype);
                },
                'NodeTable' =>  function($sm) {
                    $tableGateway = $sm->get('NodeTableGateway');
                    $table = new NodeTable($tableGateway);
                    return $table;
                },
                'NodeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Node());
                    return new TableGateway('node', $dbAdapter, null, $resultSetPrototype);
                },
                'EdgeTable' =>  function($sm) {
                    $tableGateway = $sm->get('EdgeTableGateway');
                    $table = new EdgeTable($tableGateway);
                    return $table;
                },
                'EdgeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Edge());
                    return new TableGateway('edge', $dbAdapter, null, $resultSetPrototype);
                },

                //FORMS

                //FILTERS

            ),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
    }
}
