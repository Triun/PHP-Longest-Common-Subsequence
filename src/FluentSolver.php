<?php

namespace Triun\LongestCommonSubsequence;

/**
 * Class FluentSolver
 *
 * @package Triun\LongestCommonSubsequence
 */
class FluentSolver extends Solver
{
    /**
     * @var \Closure
     */
    private $comparator;

    /**
     * @var \Closure
     */
    private $aggregator;

    /**
     * @var \Closure
     */
    private $restHandler;

    /**
     * Construct a new LCS solver.
     *
     * @param \Closure|null $comparator  The comparator to use when comparing sequence members.
     * @param \Closure|null $aggregator  The aggregator to use when sequences items has been solved.
     * @param \Closure|null $restHandler The Handler for the rest of the sequence items not aggregated.
     */
    public function __construct($comparator = null, $aggregator = null, $restHandler = null)
    {
        $this->comparator = $comparator ?: [$this, 'defaultComparator'];
        $this->aggregator = $aggregator ?: [$this, 'defaultAggregator'];
        $this->restHandler = $restHandler ?: [$this, 'defaultRestHandler'];
    }

    /**
     * @param $left
     * @param $right
     *
     * @return bool
     */
    protected function compare($left, $right)
    {
        return call_user_func($this->comparator, $left, $right);
    }

    /**
     * @param $left
     * @param $right
     *
     * @return bool
     */
    protected function defaultComparator($left, $right)
    {
        return parent::compare($left, $right);
    }

    /**
     * @param array $result
     * @param int   $type
     * @param       $left
     * @param       $right
     * @param int   $indexLeft
     * @param int   $indexRight
     */
    protected function aggregate(array &$result, int $type, $left, $right, int $indexLeft, int $indexRight)
    {
        call_user_func_array($this->aggregator, [&$result, $type, $left, $right, $indexLeft, $indexRight]);
    }

    /**
     * @param array $result
     * @param int   $type
     * @param       $left
     * @param       $right
     * @param int   $indexLeft
     * @param int   $indexRight
     */
    protected function defaultAggregator(array &$result, int $type, $left, $right, int $indexLeft, int $indexRight)
    {
        parent::aggregate($result, $type, $left, $right, $indexLeft,  $indexRight);
    }

    /**
     * @param array $result
     * @param array $sequenceLeft
     * @param int   $indexLeft
     * @param int   $totalLeft
     * @param array $sequenceRight
     * @param int   $indexRight
     * @param int   $totalRight
     */
    protected function restHandler(
        array &$result,
        array $sequenceLeft,
        int $indexLeft,
        int $totalLeft,
        array $sequenceRight,
        int $indexRight,
        int $totalRight
    ) {
        call_user_func_array(
            $this->restHandler,
            [&$result, $sequenceLeft, $indexLeft, $totalLeft, $sequenceRight, $indexRight, $totalRight]
        );
    }

    /**
     * @param array $result
     * @param array $sequenceLeft
     * @param int   $indexLeft
     * @param int   $totalLeft
     * @param array $sequenceRight
     * @param int   $indexRight
     * @param int   $totalRight
     */
    protected function defaultRestHandler(
        array &$result,
        array $sequenceLeft,
        int $indexLeft,
        int $totalLeft,
        array $sequenceRight,
        int $indexRight,
        int $totalRight
    ) {
        parent::restHandler($result, $sequenceLeft, $indexLeft, $totalLeft, $sequenceRight, $indexRight, $totalRight);
    }
}
