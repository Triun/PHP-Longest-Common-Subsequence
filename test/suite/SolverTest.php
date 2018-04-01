<?php

namespace Triun\LongestCommonSubsequence;

use PHPUnit\Framework\TestCase;

class SolverTest extends TestCase
{
    /**
     * @var \Triun\LongestCommonSubsequence\Solver
     */
    protected static $solver;

    public static function setUpBeforeClass()
    {
        self::$solver = new Solver();
    }

    /**
     * @dataProvider lcsProvider
     *
     * @param array $sequenceLeft
     * @param array $sequenceRight
     * @param array $expected
     */
    public function testSolve($sequenceLeft, $sequenceRight, $expected)
    {
        $this->assertEquals($expected, self::$solver->solve($sequenceLeft, $sequenceRight));
        $this->assertEquals($expected, self::$solver->solve($sequenceRight, $sequenceLeft));
    }

    /**
     * @dataProvider lcsProvider
     *
     * @param array $sequenceLeft
     * @param array $sequenceRight
     * @param array $expected
     */
    public function testSolveWithExpectedOrder($sequenceLeft, $sequenceRight, $expected)
    {
        $this->assertEquals($expected, self::$solver->solve($sequenceLeft, $sequenceRight));
    }

    public function lcsProvider()
    {
        return [
            'Empty values'                              => [
                [],
                [],
                [],
            ],
            'All elements equal'                        => [
                ['A', 'B', 'C'],
                ['A', 'B', 'C'],
                ['A', 'B', 'C'],
            ],
            'All elements equal words and sentences'    => [
                ['Tortor', 'Amet', 'Commodo', 'Ligula', 'Donec ullamcorper nulla non metus auctor fringilla.'],
                ['Tortor', 'Amet', 'Commodo', 'Ligula', 'Donec ullamcorper nulla non metus auctor fringilla.'],
                ['Tortor', 'Amet', 'Commodo', 'Ligula', 'Donec ullamcorper nulla non metus auctor fringilla.'],
            ],
            'British English vs American English'       => [
                ['aerofoil', 'biscuit', 'braces', 'car park', 'cinema', 'surtitle'],
                ['airfoil', 'cookie', 'suspenders', 'parking lot', 'movie theater', 'supertitle'],
                [],
            ],
            'Second sequence is a subsequence of first' => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['B', 'C', 'D', 'E'],
                ['B', 'C', 'D', 'E'],
            ],
            'Basic common subsequence'                  => [
                ['A', 'B', 'D', 'E'],
                ['A', 'C', 'D', 'F'],
                ['A', 'D'],
            ],
            'Common subsequence of larger data set'     => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['J', 'A', 'D', 'F', 'A', 'F', 'K', 'D', 'F', 'B', 'C', 'D', 'E', 'H', 'J', 'D', 'F', 'G'],
                ['A', 'B', 'C', 'D', 'E', 'F'],
            ],
            'Single element subsequence at start'       => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['A'],
                ['A'],
            ],
            'Single element subsequence at middle'      => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['D'],
                ['D'],
            ],
            'Single element subsequence at end'         => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['F'],
                ['F'],
            ],
            'Elements after end of first sequence'      => [
                ['J', 'A', 'F', 'A', 'F', 'K', 'D', 'B', 'C', 'E', 'H', 'J', 'D', 'F'],
                ['J', 'D', 'F', 'A', 'K', 'D', 'F', 'C', 'D', 'E', 'J', 'D', 'F', 'G'],
                ['J', 'F', 'A', 'K', 'D', 'C', 'E', 'J', 'D', 'F'],
            ],
            'No common elements'                        => [
                ['A', 'B', 'C'],
                ['D', 'E', 'F'],
                [],
            ],
            'No case common elements'                   => [
                ['A', 'B', 'C'],
                ['a', 'b', 'c'],
                [],
            ],
            'Mixed types'                               => [
                ['A', 19, '56', 78, true, false, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                ['A', 19, 56, '78', true, 0, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                ['A', 19, true, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
            ],
            'Arrays equal'                              => [
                [[], [3, 4, 5], ['a', 'A', 'c']],
                [[], [3, 4, 5], ['a', 'A', 'c']],
                [[], [3, 4, 5], ['a', 'A', 'c']],
            ],
            'Arrays with differences'                   => [
                [[], [3, 4, 5], [1, 2, 3], ['a', 'b', 'c'], ['A', 'a', 'c']],
                [[], [3, 4, 5], [2, 1, 3], ['a', 'b', 'c'], ['a', 'A', 'c']],
                [[], [3, 4, 5], ['a', 'b', 'c']],
            ],
            'README example'                            => [
                ['B', 'A', 'N', 'A', 'N', 'A'],
                ['A', 'T', 'A', 'N', 'A'],
                ['A', 'A', 'N', 'A'],
            ],
        ];
    }

    public function lcsOrderMattersProvider()
    {
        return [
            'Reverse sequence ASC -> DESC'    => [
                ['A', 'B', 'C', 'D', 'E'],
                ['E', 'D', 'C', 'B', 'A'],
                ['E'],
            ],
            'Reverse sequence DESC -> ASC'    => [
                ['E', 'D', 'C', 'B', 'A'],
                ['A', 'B', 'C', 'D', 'E'],
                ['A'],
            ],
            'Order change Natural -> Changed' => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['A', 'B', 'D', 'C', 'E', 'F'],
                ['A', 'B', 'D', 'E', 'F'],
            ],
            'Order change Changed -> Natural' => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['A', 'B', 'D', 'C', 'E', 'F'],
                ['A', 'B', 'C', 'E', 'F'],
            ],
        ];
    }

    public function testSolveWithThreeSequences()
    {
        $sequenceA = ['A', 'B', 'D', 'E', 'G', 'H'];
        $sequenceB = ['A', 'D', 'G', 'J'];
        $sequenceC = ['B', 'C', 'D', 'E', 'F', 'G'];
        $expected = ['D', 'G'];

        $this->assertSame($expected, static::$solver->solve($sequenceA, $sequenceB, $sequenceC));
        $this->assertSame($expected, static::$solver->solve($sequenceA, $sequenceC, $sequenceB));
        $this->assertSame($expected, static::$solver->solve($sequenceB, $sequenceA, $sequenceC));
        $this->assertSame($expected, static::$solver->solve($sequenceB, $sequenceC, $sequenceA));
        $this->assertSame($expected, static::$solver->solve($sequenceC, $sequenceA, $sequenceB));
        $this->assertSame($expected, static::$solver->solve($sequenceC, $sequenceB, $sequenceA));
    }
}
