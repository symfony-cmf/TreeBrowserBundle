<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\TreeBrowserBundle\Route;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Load routes for all existing tree controllers.
 */
class TreeControllerRoutesLoader extends FileLoader
{
    /** @var array */
    protected $treeControllers;

    /**
     * @param array $trees
     */
    public function __construct(array $treeControllers)
    {
        parent::__construct(new FileLocator());

        $this->treeControllers = $treeControllers;
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        foreach ($this->treeControllers as $controller) {
            foreach ($this->getRoutesDefinitions() as $name => $definition) {
                $defaults = array(
                    '_controller' => $controller['id'].':'.$definition['action'],
                );
                $path = '_cmf_tree/'.$controller['alias'].'/'.$definition['path'];

                $route = new Route($path, $defaults, array(), array('expose' => true), '', array(), array($definition['method']));
                $collection->add('_cmf_tree_'.$controller['alias'].'_'.$name, $route);
            }
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return bool true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        if ($type == 'cmf_tree') {
            return true;
        }

        return false;
    }

    protected function getRoutesDefinitions()
    {
        return array(
            'children' => array(
                'path'   => 'children',
                'method' => 'GET',
                'action' => 'childrenAction',
            ),
            'move' => array(
                'path'   => 'move',
                'method' => 'POST',
                'action' => 'moveAction',
            ),
            'reorder' => array(
                'path'   => 'reorder',
                'method' => 'POST',
                'action' => 'reorderAction',
            ),
        );
    }
}
