<?php

namespace Triun\LongestCommonSubsequence;

/**
 * Class Solver
 *
 * @package Triun\LongestCommonSubsequence
 */
class Solver implements SolverInterface
{
    /**
     * @param $left
     * @param $right
     *
     * @return bool
     */
    protected function compare($left, $right)
    {
        return $left === $right;
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
        if (static::EQUAL === $type) {
            $result[] = $left;
        }
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
        //
    }

    /**
     * @param array $sequenceLeft
     * @param array $sequenceRight
     *
     * @return array|mixed
     */
    public function solve(array $sequenceLeft, array $sequenceRight)
    {
        if (func_num_args() > 2) {
            $arguments = func_get_args();
            array_splice($arguments, 0, 2, [$this->solve($sequenceLeft, $sequenceRight)]);

            return call_user_func_array([$this, 'solve'], $arguments);
        }

        $m = count($sequenceLeft);
        $n = count($sequenceRight);
        // $a[$i][$j] = length of LCS of $sequenceLeft[$i..$m] and $sequenceRight[$j..$n]
        $a = [];
        // compute length of LCS and all subproblems via dynamic programming
        for ($i = $m - 1; $i >= 0; $i--) {
            for ($j = $n - 1; $j >= 0; $j--) {
                if ($this->compare($sequenceLeft[$i], $sequenceRight[$j])) {
                    $a[$i][$j] = (isset($a[$i + 1][$j + 1]) ? $a[$i + 1][$j + 1] : 0) + 1;
                } else {
                    $a[$i][$j] = max(
                        (isset($a[$i + 1][$j]) ? $a[$i + 1][$j] : 0),
                        (isset($a[$i][$j + 1]) ? $a[$i][$j + 1] : 0)
                    );
                }
            }
        }

        // recover LCS itself
        $i = 0;
        $j = 0;
        $result = [];
        while ($i < $m && $j < $n) {
            if ($this->compare($sequenceLeft[$i], $sequenceRight[$j])) {
                $this->aggregate($result, static::EQUAL, $sequenceLeft[$i], $sequenceRight[$j], $i, $j);
                $i++;
                $j++;
            } elseif ((isset($a[$i + 1][$j]) ? $a[$i + 1][$j] : 0) >= (isset($a[$i][$j + 1]) ? $a[$i][$j + 1] : 0)) {
                $this->aggregate($result, static::LEFT, $sequenceLeft[$i], $sequenceRight[$j], $i, $j);
                $i++;
            } else {
                $this->aggregate($result, static::RIGHT, $sequenceLeft[$i], $sequenceRight[$j], $i, $j);
                $j++;
            }
        }

        $this->restHandler($result, $sequenceLeft, $i, $m, $sequenceRight, $j, $n);

        return $result;
    }
}
