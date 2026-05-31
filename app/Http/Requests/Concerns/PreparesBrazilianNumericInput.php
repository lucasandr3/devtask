<?php

namespace App\Http\Requests\Concerns;

trait PreparesBrazilianNumericInput
{
    /**
     * @param  list<string>  $keys
     */
    protected function prepareBrazilianMoneyFields(array $keys): void
    {
        $this->prepareBrazilianNumericFields($keys, parse_brazilian_money(...));
    }

    /**
     * @param  list<string>  $keys
     */
    protected function prepareBrazilianDecimalFields(array $keys): void
    {
        $this->prepareBrazilianNumericFields($keys, parse_brazilian_decimal(...));
    }

    /**
     * @param  list<string>  $keys
     * @param  callable(mixed): (?float)  $parser
     */
    private function prepareBrazilianNumericFields(array $keys, callable $parser): void
    {
        $merge = [];

        foreach ($keys as $key) {
            if (! $this->has($key)) {
                continue;
            }

            $parsed = $parser($this->input($key));

            if ($parsed !== null) {
                $merge[$key] = $parsed;
            }
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }
}
