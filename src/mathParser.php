<?php

namespace Dykyi;

/**
 * Class mathParser
 */
final class mathParser
{
    private $formula;

    /**
     * @param $a
     * @param $b
     * @param $operator
     *
     * @return float|int
     */
    private function calcAB($a, $b, $operator)
    {
        $a = (float)$a;
        $b = (float)$b;
        $result = 0;
        switch ($operator) {
            case '+':
                $result = $a + $b;
                break;
            case '-':
                $result = $a - $b;
                break;
            case '*':
                $result = $a * $b;
                break;
            case '/':
                $result = $a / $b;
                break;
            case '%':
                $result = $a % $b;
                break;
        }
        return $result;
    }

    private function toMinus($string)
    {
        return str_replace('_', '-', $string);
    }

    /**
     * @param $array
     * @param $operator
     *
     * @return bool
     */
    private function searchOperator(&$array, $operator)
    {
        $s1 = array_search($operator, $array);
        if ($s1) {
            $a = $this->toMinus($array[$s1 - 1]);
            $b = $this->toMinus($array[$s1 + 1]);
            $result = $this->calcAB($a, $b, $array[$s1]);
            unset($array[$s1 - 1], $array[$s1]);
            $array[$s1 + 1] = $result;
            $array = array_values($array);
            return $this->searchOperator($array, $operator);
        }
        return 0;
    }

    /**
     * @param $string
     * @return mixed
     */
    private function replaceMinusNumber($string)
    {
        $count = strlen($string);
        for ($i = 0; $i < $count; $i++) {
            if (($string[$i] == '(' && $string[$i + 1] == '-') ||
                (in_array($string[$i], ['*', '/', '%']) && ($string[$i + 1] == '-'))
            ) {
                $string[$i + 1] = '_';
            }
        }
        return $string;
    }

    /**
     * Generator function
     *
     * @yield string
     *
     * @param Generator
     *
     */
    private function calculate(&$array)
    {
        while (true) {
            $operator = yield;
            $this->searchOperator($array, $operator);
        }
    }

    /**
     * Переписати на генератори
     *
     * @param $string
     *
     * @return int
     */
    private function calcInString($string)
    {
        $string = str_replace(['-', '+', '*', '/', '%', '(', ')'], ['|-|', '|+|', '|*|', '|/|', '|%|', '', ''], $string);
        $array = explode('|', $string);
        if (!$array) {
            return 0;
        }

        $calc = $this->calculate($array);
        $calc->send('*');
        $calc->send('/');
        $calc->send('%');
        $calc->send('+');
        $calc->send('-');

        return $array[0];
    }

    /**
     * @param $string
     * @return mixed
     */
    private function prepare($string)
    {
        $string = str_replace(' ', '', $string);
        $string = str_replace('--', '+', $string);
        $string = str_replace('+-', '-', $string);
        $string = $this->replaceMinusNumber($string);

        $left = substr_count($string, '(');
        $right = substr_count($string, ')');
        if ($left !== $right) {
            exit('Find excess bracket');
        }

        return $string;
    }

    /**
     * @param $string
     * @return mixed
     */
    private function calcInBracket($string)
    {
        $b_r = strpos($string, ')');
        $tmp = substr($string, 0, $b_r + 1);
        $bracket = strrchr($tmp, '(');
        if ($bracket) {
            $result = $this->calcInString($bracket);
            $new_formula = str_replace($bracket, $result, $string);
            return $this->calcInBracket($new_formula);
        }
        return $this->replaceMinusNumber($string);
    }

    /**
     * Main function
     *
     * @param $formula
     * @return int
     */
    public function calc($formula)
    {
        $this->formula = $this->prepare($formula);
        $formula_without_bracket = $this->calcInBracket($this->formula);
        $result = $this->calcInString($formula_without_bracket);
        return $result;
    }
}
