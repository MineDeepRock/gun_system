<?php


namespace gun_system\model\performance;


class DamageGraph
{
    /**
     * @var float[]
     */
    private $graph;

    public function __construct(array $graph) {
        $this->graph = $graph;
    }

    /**
     * @return float[]
     */
    public function getGraph(): array {
        return $this->graph;
    }
}