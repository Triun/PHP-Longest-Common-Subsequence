PHP - Longest Common Subsequence
================================

PHP implementation of an algorithm to solve the `longest common subsequence` problem.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage status][ico-codecov]][link-codecov]
[![Total Downloads][ico-downloads]][link-downloads]
[![The most recent stable version is 2.0.0][ico-semver]][link-semver]
[![Software License][ico-license]](LICENSE.md)

# About

*PHP-Longest-Common-Subsequence* is a PHP implementation of an algorithm to solve the 'longest common subsequence' problem.

From [Wikipedia - Longest common subsequence problem](https://en.wikipedia.org/wiki/Longest_common_subsequence_problem):

> The *longest common subsequence (LCS) problem* is the problem of finding the longest
> [subsequence](https://en.wikipedia.org/wiki/Subsequence) common to all sequences in a set of sequences
> (often just two sequences). It differs from the
> [longest common substring problem](https://en.wikipedia.org/wiki/Longest_common_substring_problem): unlike substrings,
> subsequences are not required to occupy consecutive positions within the original sequences. The longest common
> subsequence problem is a classic [computer science](https://en.wikipedia.org/wiki/Computer_science) problem, the
> basis of [data comparison](https://en.wikipedia.org/wiki/Data_comparison) programs such as the
> [diff utility](https://en.wikipedia.org/wiki/Diff_utility), and has applications in
> [bioinformatics](https://en.wikipedia.org/wiki/Bioinformatics). It is also widely used by
> [revision control systems](https://en.wikipedia.org/wiki/Revision_control) such as
> [Git](https://en.wikipedia.org/wiki/Git_(software)) for
> [reconciling](https://en.wikipedia.org/wiki/Merge_(revision_control)) multiple changes made to a revision-controlled
> collection of files.

This PHP implementation is based on [eloquent/php-lcs](https://github.com/eloquent/php-lcs), adding a little more
flexibility in order to handle, not only the common text, but also the differences.

# Installation

Require [triun/longest-common-subsequence package](https://packagist.org/packages/triun/longest-common-subsequence) with [composer](http://getcomposer.org/)
using the following command:

```bash
composer require triun/longest-common-subsequence
```

# Usage

```php
use Triun\LongestCommonSubsequence\Solver;

$solver = new Solver;

$sequenceA = array('B', 'A', 'N', 'A', 'N', 'A');
$sequenceB = array('A', 'T', 'A', 'N', 'A');

// calculates the LCS to be array('A', 'A', 'N', 'A')
$lcs = $solver->solve($sequenceA, $sequenceB);
```

# Issues
   
Bug reports and feature requests can be submitted on the
[Github Issue Tracker](https://github.com/Triun/PHP-Longest-Common-Subsequence/issues).

# Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for information.

# License

This repository is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

<!-- References -->

[ico-version]: https://img.shields.io/packagist/v/triun/longest-common-subsequence.svg "Stable version"
[ico-pre-release]: https://img.shields.io/packagist/vpre/triun/longest-common-subsequence.svg "Pre release version"
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://travis-ci.org/Triun/PHP-Longest-Common-Subsequence.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/triun/longest-common-subsequence.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/triun/longest-common-subsequence.svg?style=flat-square
[ico-unstable]: https://poser.pugx.org/triun/longest-common-subsequence/v/unstable
[ico-coveralls]: https://coveralls.io/repos/github/Triun/PHP-Longest-Common-Subsequence/badge.svg?branch=master "Current test coverage for the develop branch"
[ico-codecov]: https://codecov.io/gh/Triun/PHP-Longest-Common-Subsequence/branch/master/graph/badge.svg
[ico-semver]: http://img.shields.io/:semver-2.0.0-brightgreen.svg "This project uses semantic versioning"
[ico-php-v]: https://img.shields.io/packagist/php-v/triun/longest-common-subsequence.svg

[link-packagist]: https://packagist.org/packages/triun/longest-common-subsequence
[link-travis]: https://travis-ci.org/Triun/PHP-Longest-Common-Subsequence
[link-downloads]: https://packagist.org/packages/triun/longest-common-subsequence
[link-author]: https://github.com/triun
[link-coveralls]: https://coveralls.io/github/Triun/PHP-Longest-Common-Subsequence?branch=master
[link-codecov]: https://codecov.io/gh/Triun/PHP-Longest-Common-Subsequence
[link-semver]: http://semver.org/
