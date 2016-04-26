<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ObjectNode extends AbstractParentNode
{
    /**
     * @param string       $name
     * @param AbstractNode $node
     *
     * @return $this
     */
    public function add($name, AbstractNode $node)
    {
        if (isset($this->children[$name])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with name %s!', $name));
        }

        $node->setParent($this);

        $this->children[$name] = $node;

        return $this;
    }

    /**
     * @return array
     */
    public function getAddDefault()
    {
        return new \stdClass();
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialized = new \stdClass();
        foreach ($this->children as $name => $child) {
            if (null !== $serializedChild = $child->serialize()) {
                $serialized->$name = $serializedChild;
            } elseif ($child->allowAddDefault()) {
                $serialized->$name = $child->getAddDefault();
            }
        }

        if ([] === (array) $serialized) {
            return;
        }

        return $serialized;
    }
}
