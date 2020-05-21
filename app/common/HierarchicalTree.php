<?php

namespace app\common;

use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;

/**
 * Serializer which creates a hierarchical, depth-first sorted representation of the tree nodes.
 *
 * @author  Carsten Bluem <carsten@bluem.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD 3-Clause License
 */
class HierarchicalTree implements TreeJsonSerializerInterface
{
    /**
     * @var string
     */
    private $childNodesArrayKey;

    /**
     * @param string $childNodesArrayKey
     */
    public function __construct($childNodesArrayKey = 'children')
    {
        $this->childNodesArrayKey = $childNodesArrayKey;
    }

    /**
     * {@inheritdoc}
     *
     * @return array Multi-dimensional array of node data arrays, where a node's children are
     *               included in array key "children" of a node
     */
    public function serialize(Tree $tree): array
    {
        $data = [];
        foreach ($tree->getRootNodes() as $node) {
            $data[] = $this->serializeNode($node);
        }

        return $data;
    }

    private function serializeNode(Tree\Node $node): array
    {
        $nodeData = $node->toArray();
        if ($node->hasChildren()) {
            $nodeData[$this->childNodesArrayKey] = [];
            foreach ($node->getChildren() as $child) {
                $nodeData[$this->childNodesArrayKey][] = $this->serializeNode($child);
            }
        }

        return $nodeData;
    }
}

