<?php

namespace Triun\LongestCommonSubsequence;

use ReflectionClass;
use PHPUnit\Framework\TestCase;

class FluentSolverTest extends TestCase
{
    /**
     * @var \Triun\LongestCommonSubsequence\FluentSolver
     */
    protected static $customSolver;

    /**
     * @var \Triun\LongestCommonSubsequence\FluentSolver
     */
    protected static $defaultSolver;

    public static function setUpBeforeClass()
    {
        self::$customSolver = new FluentSolver(
            [static::class, 'fluentComparator'],
            [static::class, 'fluentAggregator'],
            [static::class, 'fluentRestHandler']
        );
        self::$defaultSolver = new FluentSolver();
    }

    /**
     * @param $left
     * @param $right
     *
     * @return bool
     */
    public static function fluentComparator($left, $right)
    {
        // Not necessarily equal in var type.
        return $left == $right;
    }

    /**
     * @param array $lcs
     * @param int   $type
     * @param       $left
     * @param       $right
     */
    public static function fluentAggregator(array &$lcs, int $type, $left, $right)
    {
        if (FluentSolver::EQUAL === $type) {
            $lcs[] = $right;
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
        //
    }

    public function testConstructor()
    {
        $comparator = [static::class, 'fluentComparator'];
        $aggregator = [static::class, 'fluentAggregator'];
        $restHandler = [static::class, 'fluentRestHandler'];

        $solver = new FluentSolver($comparator, $aggregator, $restHandler);

        $class = new ReflectionClass(FluentSolver::class);

        $property = $class->getProperty('comparator');
        $property->setAccessible(true);

        $this->assertEquals($comparator, $property->getValue($solver));
        $this->assertNotEquals($aggregator, $property->getValue($solver));
        $this->assertNotEquals($restHandler, $property->getValue($solver));

        $property = $class->getProperty('aggregator');
        $property->setAccessible(true);

        $this->assertNotEquals($comparator, $property->getValue($solver));
        $this->assertEquals($aggregator, $property->getValue($solver));
        $this->assertNotEquals($restHandler, $property->getValue($solver));

        $property = $class->getProperty('restHandler');
        $property->setAccessible(true);

        $this->assertNotEquals($comparator, $property->getValue($solver));
        $this->assertNotEquals($aggregator, $property->getValue($solver));
        $this->assertEquals($restHandler, $property->getValue($solver));
    }

    /**
     * @dataProvider lcsProvider
     *
     * @param array $sequenceLeft
     * @param array $sequenceRight
     * @param array $expected
     */
    public function testDefaultLongestCommonSubsequenceCommons($sequenceLeft, $sequenceRight, $expected)
    {
        $this->assertEquals($expected, self::$defaultSolver->solve($sequenceLeft, $sequenceRight));
    }

    /**
     * @dataProvider lcsProvider
     *
     * @param array $sequenceLeft
     * @param array $sequenceRight
     * @param array $expected
     */
    public function testCustomLongestCommonSubsequenceCommons($sequenceLeft, $sequenceRight, $expected)
    {
        $this->assertEquals($expected, self::$customSolver->solve($sequenceLeft, $sequenceRight));
    }

    /**
     * @dataProvider defaultLongestCommonSubsequenceProvider
     *
     * @param array $sequenceLeft
     * @param array $sequenceRight
     * @param array $expected
     */
    public function testDefaultLongestCommonSubsequence($sequenceLeft, $sequenceRight, $expected)
    {
        $this->assertEquals($expected, self::$defaultSolver->solve($sequenceLeft, $sequenceRight));
    }

    /**
     * @dataProvider customLongestCommonSubsequenceProvider
     *
     * @param array $sequenceLeft
     * @param array $sequenceRight
     * @param array $expected
     */
    public function testCustomLongestCommonSubsequence($sequenceLeft, $sequenceRight, $expected)
    {
        $this->assertEquals($expected, self::$customSolver->solve($sequenceLeft, $sequenceRight));
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
            'Reverse sequence'                          => [
                ['A', 'B', 'C', 'D', 'E'],
                ['E', 'D', 'C', 'B', 'A'],
                ['E'],
            ],
            'Order change'                              => [
                ['A', 'B', 'C', 'D', 'E', 'F'],
                ['A', 'B', 'D', 'C', 'E', 'F'],
                ['A', 'B', 'D', 'E', 'F'],
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

    public function defaultLongestCommonSubsequenceProvider()
    {
        return [
            'Mixed types' => [
                ['A', 19, '56', 78, true, false, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                ['A', 19, 56, '78', true, 0, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                ['A', 19, true, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
            ],
        ];
    }

    public function customLongestCommonSubsequenceProvider()
    {
        return [
            'Mixed types' => [
                ['A', 19, '56', 78, true, false, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                ['A', 19, 56, '78', true, 0, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                // This results are different because we changed the comparator.
                //['A', 19, '56', 78, true, false, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
                // Also, we get the second value instead of the first one, because we changed the aggregator.
                ['A', 19, 56, '78', true, 0, '', null, 'Aenean lacinia bibendum nulla sed consectetur.'],
            ],
        ];
    }
}
