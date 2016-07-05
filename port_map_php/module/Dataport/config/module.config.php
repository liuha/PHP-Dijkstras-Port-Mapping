<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Dataport\Controller\Dataport' => 'Dataport\Controller\DataportController',
            'Dataport\Controller\Node' => 'Dataport\Controller\NodeController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'dataport' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/dataport[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Dataport\Controller\Dataport',
                        'action'     => 'index',
                    ),
                ),
            ),
            'node' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/node[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Dataport\Controller\Node',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'dataport' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/default-layout.phtml',
            'layout/nodelayout'           => __DIR__ . '/../view/layout/node-layout.phtml',
        ),
    ),
);