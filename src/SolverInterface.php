<?php

namespace Triun\LongestCommonSubsequence;

/**
 * Interface SolverInterface
 *
 * @package Triun\LongestCommonSubsequence
 */
interface SolverInterface
{
    const EQUAL = 0;
    const LEFT = 1;
    const RIGHT = 2;
    const CHANGED = 3;

    /**
     * @param array $sequenceLeft
     * @param array $sequenceRight
     *
     * @return array|mixed
     */
    public function solve(array $sequenceLeft, array $sequenceRight);
}
