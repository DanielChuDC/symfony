<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\EqualToValidator;
use Symfony\Component\Validator\Tests\IcuCompatibilityTrait;

/**
 * @author Daniel Holmes <daniel@danielholmes.org>
 */
class EqualToValidatorTest extends AbstractComparisonValidatorTestCase
{
    use IcuCompatibilityTrait;

    protected function createValidator()
    {
        return new EqualToValidator();
    }

    protected static function createConstraint(?array $options = null): Constraint
    {
        return new EqualTo($options);
    }

    protected function getErrorCode(): ?string
    {
        return EqualTo::NOT_EQUAL_ERROR;
    }

    /**
     * {@inheritdoc}
     */
    public static function provideValidComparisons(): array
    {
        return [
            [3, 3],
            [3, '3'],
            ['a', 'a'],
            [new \DateTime('2000-01-01'), new \DateTime('2000-01-01')],
            [new \DateTime('2000-01-01'), '2000-01-01'],
            [new \DateTime('2000-01-01 UTC'), '2000-01-01 UTC'],
            [new ComparisonTest_Class(5), new ComparisonTest_Class(5)],
            [null, 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function provideValidComparisonsToPropertyPath(): array
    {
        return [
            [5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function provideInvalidComparisons(): array
    {
        return [
            [1, '1', 2, '2', 'int'],
            ['22', '"22"', '333', '"333"', 'string'],
            [new \DateTime('2001-01-01'), self::normalizeIcuSpaces("Jan 1, 2001, 12:00\u{202F}AM"), new \DateTime('2000-01-01'), self::normalizeIcuSpaces("Jan 1, 2000, 12:00\u{202F}AM"), 'DateTime'],
            [new \DateTime('2001-01-01'), self::normalizeIcuSpaces("Jan 1, 2001, 12:00\u{202F}AM"), '2000-01-01', self::normalizeIcuSpaces("Jan 1, 2000, 12:00\u{202F}AM"), 'DateTime'],
            [new \DateTime('2001-01-01 UTC'), self::normalizeIcuSpaces("Jan 1, 2001, 12:00\u{202F}AM"), '2000-01-01 UTC', self::normalizeIcuSpaces("Jan 1, 2000, 12:00\u{202F}AM"), 'DateTime'],
            [new ComparisonTest_Class(4), '4', new ComparisonTest_Class(5), '5', __NAMESPACE__.'\ComparisonTest_Class'],
        ];
    }

    public static function provideComparisonsToNullValueAtPropertyPath(): array
    {
        return [
            [5, '5', false],
        ];
    }
}
