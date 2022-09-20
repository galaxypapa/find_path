<?php

namespace App\Service;

use SplStack;

class DeviceManager implements DeviceManagerInterface
{
    private array $dist = [];
    private array $pred = [];

    /**
     * Store the latency information as a graph
     *
     * @param array $latencyInfo
     * @return array
     */
    public function storeAsGraph(array $latencyInfo): array
    {
        $arr = [];
        foreach ($latencyInfo as $info) {
            $from = strtoupper($info[0]);
            $to = strtoupper($info[1]);
            $latency = $info[2];
            $arr[$from] = isset($arr[$from]) ? array_merge($arr[$from], [$to => $latency]) : [$to => $latency];
            $arr[$to] = isset($arr[$to]) ? array_merge($arr[$to], [$from => $latency]) : [$from => $latency];
        }
        return $arr;
    }


    /**
     * Find the shortest path between given two node. It is an implementation base on
     * Dijkstra algorithm.
     *
     * @param string $from
     * @param string $to
     * @param array $graph
     * @return array
     */
    function shortestPath(string $from, string $to, array $graph): array
    {
        foreach ($graph as $v => $_) {
            $this->dist[$v] = INF;
            $this->pred[$v] = null;
        }
        $this->dist[$from] = 0;
        $this->updateAdjacencyVertex($from, $graph);
        $stack = new SplStack();
        $latency = 0;
        while (isset($this->pred[$to]) && $this->pred[$to]) {
            $stack->push($to);
            $latency += $graph[$to][$this->pred[$to]];
            $to = $this->pred[$to];
        }
        if ($stack->isEmpty()) {
            return ["latency" => 0, "path" => 'Path not found'];
        } else {
            $stack->push($from);
            return ["latency" => $latency, "path" => $stack];
        }
    }

    /**
     * Update distance array & predecessor of the Adjacency Vertex
     *
     * @param $source
     * @param $graph
     */
    private function updateAdjacencyVertex($source, $graph)
    {
        if (!empty($graph[$source])) {
            foreach ($graph[$source] as $v => $cost) {
                $alt = $this->dist[$source] + $cost;
                if ($alt < $this->dist[$v]) {
                    $this->dist[$v] = $alt;
                    $this->pred[$v] = $source;
                    $this->updateAdjacencyVertex($v, $graph);
                }
            }
        }
    }

}