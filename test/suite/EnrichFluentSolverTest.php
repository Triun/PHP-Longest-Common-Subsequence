<?php

namespace Triun\LongestCommonSubsequence;

use PHPUnit\Framework\TestCase;

class EnrichFluentSolverTest extends TestCase
{
    /**
     * @var \Triun\LongestCommonSubsequence\FluentSolver
     */
    protected static $solver;

    public static function setUpBeforeClass()
    {
        self::$solver = new FluentSolver(
            null,
            [static::class, 'fluentAggregator'],
            [static::class, 'fluentRestHandler']
        );
    }

    /**
     * We want to get all items with the type of comparision result found.
     *
     * @param array $result
     * @param int   $type
     * @param       $left
     * @param       $right
     */
    public static function fluentAggregator(array &$result, int $type, $left, $right)
    {
        $last = FluentSolver::RIGHT === $type && count($result) > 0 ?
            $result[count($result) - 1] : null;

        if (null !== $last && FluentSolver::LEFT === $last[2] && $right === $last[1]) {
            $result[count($result) - 1][2] = FluentSolver::CHANGED;
        } else {
            $result[] = [$left, $right, $type];
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
    public static function fluentRestHandler(
        array &$result,
        array $sequenceLeft,
        int $indexLeft,
        int $totalLeft,
        array $sequenceRight,
        int $indexRight,
        int $totalRight
    ) {
        // Append the rest of the left values
        $lastRight = min($indexRight, count($sequenceRight) - 1);
        while ($indexLeft < $totalLeft) {
            static::fluentAggregator(
                $result,
                FluentSolver::LEFT,
                $sequenceLeft[$indexLeft],
                $sequenceRight[$lastRight]
            );
            // $result[] = [
            //     $sequenceLeft[$indexLeft],
            //     $sequenceRight[$lastRight],
            //     FluentSolver::LEFT
            // ];
            $indexLeft++;
        }

        // Append the rest of the right values
        $lastLeft = min($indexLeft, count($sequenceLeft) - 1);
        while ($indexRight < $totalRight) {
            static::fluentAggregator(
                $result,
                FluentSolver::RIGHT,
                $sequenceLeft[$lastLeft],
                $sequenceRight[$indexRight]
            );
            // $result[] = [
            //     $sequenceLeft[$lastLeft],
            //     $sequenceRight[$indexRight],
            //     FluentSolver::RIGHT
            // ];
            $indexRight++;
        }
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
                [
                    ['A', 'A', FluentSolver::EQUAL],
                    ['B', 'B', FluentSolver::EQUAL],
                    ['C', 'C', FluentSolver::EQUAL],
                ],
            ],
            'All elements equal words and sentences'    => [
                ['Tortor', 'Amet', 'Commodo', 'Ligula', 'Donec ullamcorper nulla non metus auctor fringilla.'],
                ['Tortor', 'Amet', 'Commodo', 'Ligula', 'Donec ullamcorper nulla non metus auctor fringilla.'],
                [
                    ['Tortor', 'Tortor', FluentSolver::EQUAL],
                    ['Amet', 'Amet', FluentSolver::EQUAL],
                    ['Commodo', 'Commodo', FluentSolver::EQUAL],
                    ['Ligula', 'Ligula', FluentSolver::EQUAL],
                    [
                        'Donec ullamcorper nulla non metus auctor fringilla.',
                        'Donec ullamcorper nulla non metus auctor fringilla.',
                        FluentSolver::EQUAL,
                    ],
                ],
            ],
            'Second sequence is a subsequence of first' => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['B', 'C', 'D', 'E'],
                [
                    ['A', 'B', FluentSolver::LEFT],
                    ['B', 'B', FluentSolver::EQUAL],
                    ['C', 'C', FluentSolver::EQUAL],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['E', 'E', FluentSolver::EQUAL],
                    ['F', 'E', FluentSolver::LEFT],
                ],
            ],
            'Basic common subsequence'                  => [
                ['A', 'B', 'D', 'E'],
                ['A', 'C', 'D', 'F'],
                [
                    ['A', 'A', FluentSolver::EQUAL],
                    ['B', 'C', FluentSolver::CHANGED],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['E', 'F', FluentSolver::CHANGED],
                ],
            ],
            'Common subsequence of larger data set'     => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['J', 'A', 'D', 'F', 'A', 'F', 'K', 'D', 'F', 'B', 'C', 'D', 'E', 'H', 'J', 'D', 'F', 'G'],
                [
                    ['A', 'J', FluentSolver::RIGHT],
                    ['A', 'A', FluentSolver::EQUAL],
                    ['B', 'D', FluentSolver::RIGHT],
                    ['B', 'F', FluentSolver::RIGHT],
                    ['B', 'A', FluentSolver::RIGHT],
                    ['B', 'F', FluentSolver::RIGHT],
                    ['B', 'K', FluentSolver::RIGHT],
                    ['B', 'D', FluentSolver::RIGHT],
                    ['B', 'F', FluentSolver::RIGHT],
                    ['B', 'B', FluentSolver::EQUAL],
                    ['C', 'C', FluentSolver::EQUAL],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['E', 'E', FluentSolver::EQUAL],
                    ['F', 'H', FluentSolver::RIGHT],
                    ['F', 'J', FluentSolver::RIGHT],
                    ['F', 'D', FluentSolver::RIGHT],
                    ['F', 'F', FluentSolver::EQUAL],
                    ['F', 'G', FluentSolver::RIGHT],
                ],
            ],
            'Single element subsequence at start'       => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['A'],
                [
                    ['A', 'A', FluentSolver::EQUAL],
                    ['B', 'A', FluentSolver::LEFT],
                    ['C', 'A', FluentSolver::LEFT],
                    ['D', 'A', FluentSolver::LEFT],
                    ['E', 'A', FluentSolver::LEFT],
                    ['F', 'A', FluentSolver::LEFT],
                ],
            ],
            'Single element subsequence at middle'      => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['D'],
                [
                    ['A', 'D', FluentSolver::LEFT],
                    ['B', 'D', FluentSolver::LEFT],
                    ['C', 'D', FluentSolver::LEFT],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['E', 'D', FluentSolver::LEFT],
                    ['F', 'D', FluentSolver::LEFT],
                ],
            ],
            'Single element subsequence at end'         => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['F'],
                [
                    ['A', 'F', FluentSolver::LEFT],
                    ['B', 'F', FluentSolver::LEFT],
                    ['C', 'F', FluentSolver::LEFT],
                    ['D', 'F', FluentSolver::LEFT],
                    ['E', 'F', FluentSolver::LEFT],
                    ['F', 'F', FluentSolver::EQUAL],
                ],
            ],
            'Elements after end of first sequence'      => [
                ['J', 'A', 'F', 'A', 'F', 'K', 'D', 'B', 'C', 'E', 'H', 'J', 'D', 'F'],
                ['J', 'D', 'F', 'A', 'K', 'D', 'F', 'C', 'D', 'E', 'J', 'D', 'F', 'G'],
                [
                    ['J', 'J', FluentSolver::EQUAL],
                    ['A', 'D', FluentSolver::CHANGED],
                    ['F', 'F', FluentSolver::EQUAL],
                    ['A', 'A', FluentSolver::EQUAL],
                    ['F', 'K', FluentSolver::LEFT],
                    ['K', 'K', FluentSolver::EQUAL],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['B', 'F', FluentSolver::CHANGED],
                    ['C', 'C', FluentSolver::EQUAL],
                    ['E', 'D', FluentSolver::RIGHT],
                    ['E', 'E', FluentSolver::EQUAL],
                    ['H', 'J', FluentSolver::LEFT],
                    ['J', 'J', FluentSolver::EQUAL],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['F', 'F', FluentSolver::EQUAL],
                    ['F', 'G', FluentSolver::RIGHT],
                ],
            ],
            'No common elements'                        => [
                ['A', 'B', 'C'],
                ['D', 'E', 'F'],
                [
                    ['A', 'D', FluentSolver::LEFT],
                    ['B', 'D', FluentSolver::LEFT],
                    ['C', 'D', FluentSolver::CHANGED],
                    ['C', 'E', FluentSolver::RIGHT],
                    ['C', 'F', FluentSolver::RIGHT],
                ],
            ],
            'No case common elements'                   => [
                ['A', 'B', 'C'],
                ['a', 'b', 'c'],
                [
                    ['A', 'a', FluentSolver::LEFT],
                    ['B', 'a', FluentSolver::LEFT],
                    ['C', 'a', FluentSolver::CHANGED],
                    ['C', 'b', FluentSolver::RIGHT],
                    ['C', 'c', FluentSolver::RIGHT],
                ],
            ],
            'Reverse sequence'                          => [
                ['A', 'B', 'C', 'D', 'E'],
                ['E', 'D', 'C', 'B', 'A'],
                [
                    ['A', 'E', FluentSolver::LEFT],
                    ['B', 'E', FluentSolver::LEFT],
                    ['C', 'E', FluentSolver::LEFT],
                    ['D', 'E', FluentSolver::LEFT],
                    ['E', 'E', FluentSolver::EQUAL],
                    ['E', 'D', FluentSolver::RIGHT],
                    ['E', 'C', FluentSolver::RIGHT],
                    ['E', 'B', FluentSolver::RIGHT],
                    ['E', 'A', FluentSolver::RIGHT],
                ],
            ],
            'Order change'                              => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['A', 'B', 'D', 'C', 'E', 'F'],
                [
                    ['A', 'A', FluentSolver::EQUAL],
                    ['B', 'B', FluentSolver::EQUAL],
                    ['C', 'D', FluentSolver::LEFT],
                    ['D', 'D', FluentSolver::EQUAL],
                    ['E', 'C', FluentSolver::RIGHT],
                    ['E', 'E', FluentSolver::EQUAL],
                    ['F', 'F', FluentSolver::EQUAL],
                ],
            ],
            'Mixed types'                               => [
                ['A', 19, '56', 78, true, false, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                ['A', 19, 56, '78', true, 0, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                [
                    ['A', 'A', FluentSolver::EQUAL],
                    [19, 19, FluentSolver::EQUAL],
                    ['56', 56, FluentSolver::LEFT],
                    [78, 56, FluentSolver::CHANGED],
                    [true, '78', FluentSolver::RIGHT],
                    [true, true, FluentSolver::EQUAL],
                    [false, 0, FluentSolver::CHANGED],
                    ['', '', FluentSolver::EQUAL],
                    [null, null, FluentSolver::EQUAL],
                    [
                        'Aenean lacinia bibendum nulla sed consectetur.',
                        'Aenean lacinia bibendum nulla sed consectetur.',
                        FluentSolver::EQUAL,
                    ],
                ],
            ],
            'Arrays equal'                              => [
                [[], [3, 4, 5], ['a', 'A', 'c']],
                [[], [3, 4, 5], ['a', 'A', 'c']],
                [
                    [[], [], FluentSolver::EQUAL],
                    [[3, 4, 5], [3, 4, 5], FluentSolver::EQUAL],
                    [['a', 'A', 'c'], ['a', 'A', 'c'], FluentSolver::EQUAL],
                ],
            ],
            'Arrays with differences'                   => [
                [[], [3, 4, 5], [1, 2, 3], ['a', 'b', 'c'], ['A', 'a', 'c']],
                [[], [3, 4, 5], [2, 1, 3], ['a', 'b', 'c'], ['a', 'A', 'c']],
                [
                    [[], [], FluentSolver::EQUAL],
                    [[3, 4, 5], [3, 4, 5], FluentSolver::EQUAL],
                    [[1, 2, 3], [2, 1, 3], FluentSolver::CHANGED],
                    [['a', 'b', 'c'], ['a', 'b', 'c'], FluentSolver::EQUAL],
                    [['A', 'a', 'c'], ['a', 'A', 'c'], FluentSolver::CHANGED],
                ],
            ],
            'README example'                            => [
                ['B', 'A', 'N', 'A', 'N', 'A'],
                ['A', 'T', 'A', 'N', 'A'],
                [
                    ['B', 'A', FluentSolver::LEFT],
                    ['A', 'A', FluentSolver::EQUAL],
                    ['N', 'T', FluentSolver::CHANGED],
                    ['A', 'A', FluentSolver::EQUAL],
                    ['N', 'N', FluentSolver::EQUAL],
                    ['A', 'A', FluentSolver::EQUAL],
                ],
            ],
            'British English vs American English'       => [
                ['aerofoil', 'biscuit', 'braces', 'car park', 'cinema', 'surtitle'],
                ['airfoil', 'cookie', 'suspenders', 'parking lot', 'movie theater', 'supertitle'],
                [
                    ['aerofoil', 'airfoil', FluentSolver::LEFT],
                    ['biscuit', 'airfoil', FluentSolver::LEFT],
                    ['braces', 'airfoil', FluentSolver::LEFT],
                    ['car park', 'airfoil', FluentSolver::LEFT],
                    ['cinema', 'airfoil', FluentSolver::LEFT],

                    ['surtitle', 'airfoil', FluentSolver::CHANGED],

                    ['surtitle', 'cookie', FluentSolver::RIGHT],
                    ['surtitle', 'suspenders', FluentSolver::RIGHT],
                    ['surtitle', 'parking lot', FluentSolver::RIGHT],
                    ['surtitle', 'movie theater', FluentSolver::RIGHT],
                    ['surtitle', 'supertitle', FluentSolver::RIGHT],
                ],
            ],
        ];
    }
}
