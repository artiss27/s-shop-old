<?php
declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CoreExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('instanceof', [$this, 'instanceof']),
        ];
    }

    /**
     * @param        $value
     * @param string $type
     * @return bool
     */
    public function instanceof($value, string $type): bool
    {
        return ('null' === $type && null === $value)
               || (\function_exists($func = 'is_' . $type) && $func($value))
               || $value instanceof $type;
    }
}