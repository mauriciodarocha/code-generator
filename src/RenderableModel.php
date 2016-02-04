<?php

namespace Krlove\Generator;

use Krlove\Generator\Exception\GeneratorException;

/**
 * Class RenderableModel
 * @package Krlove\Generator
 */
abstract class RenderableModel implements RenderableInterface, LineableInterface
{
    /**
     * {@inheritDoc}
     */
    final public function render($indent = 0, $delimiter = PHP_EOL)
    {
        $lines = $this->toLines();

        if (!is_array($lines)) {
            $lines = [$lines];
        }

        if ($indent > 0) {
            array_walk($lines, function (&$item) use ($indent) {
                $item = str_repeat(' ', $indent) . $item;
            });
        }

        return implode($delimiter, $lines);
    }

    /**
     * @param RenderableInterface[] $array
     * @param int $indent
     * @param string $delimiter
     * @return string
     * @throws GeneratorException
     */
    protected function renderArrayLn(array $array, $indent = 0, $delimiter = PHP_EOL)
    {
        return $this->ln($this->renderArray($array, $indent, $delimiter));
    }

    /**
     * @param RenderableInterface[] $array
     * @param int $indent
     * @param string $delimiter
     * @return string
     * @throws GeneratorException
     */
    protected function renderArray(array $array, $indent = 0, $delimiter = PHP_EOL)
    {
        $lines = [];
        foreach ($array as $item) {
            if (!$item instanceof RenderableInterface) {
                throw new GeneratorException('Invalid item type');
            }

            $lines[] = $item->render($indent);
        }

        return implode($delimiter, $lines);
    }

    /**
     * @param string $line
     * @return string
     */
    protected function ln($line)
    {
        return $line . PHP_EOL;
    }
}