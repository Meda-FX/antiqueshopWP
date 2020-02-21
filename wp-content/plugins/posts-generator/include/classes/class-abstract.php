<?php
/**
 * @since 		1.0         2019-09-07      Release
 * @package 	FDC
 * @subpackage 	FDC/Core
 */
// ───────────────────────────
namespace FDC\Core;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| Abstract Class
|--------------------------------------------------------------------------
|
| Functions for manipulating fake text
| This class exists but at the same time it does not exist.
|
*/
class Fdc_Abstract{
    /**
     * Returns randomly ordered subsequence of $count elements from a provided array
     *
     * @since   1.0         2019-09-09      Release
     * @since   1.2         2019-11-02      To avoid AJAX errors at the time of generating 0 item then it was
     *                                      removed throw new \Exception
     *
     * @param   array       $array          Array to take elements from. Defaults to a-f
     * @param   integer     $count          Number of elements to take.
     * @throws  \Exception  When requesting more elements than provided
     *
     * @return array New array with $count elements from $array
     */
    public static function randomElements( array $array = array('a', 'b', 'c'), $count = 1 ){
        $allKeys = array_keys($array);
        $numKeys = count($allKeys);

        if ($numKeys < $count) {
            return [];
            //throw new \Exception(sprintf('Cannot get %d elements, only %d in array', $count, $numKeys));
        }

        $highKey     = $numKeys - 1;
        $keys        = $elements = array();
        $numElements = 0;

        while ($numElements < $count) {
            $num = mt_rand(0, $highKey);
            if (isset($keys[$num])) {
                continue;
            }

            $keys[$num] = true;
            $elements[] = $array[$allKeys[$num]];
            $numElements++;
        }

        return $elements;
    }

    /**
     * Returns a random element from a passed array
     *
     * @since   1.0     2019-09-09      Release
     *
     * @param   array   $array          Array where it will take 1 value
     * @param   int     $frecuency      Possibility of 1 to 100 that primary array data is taken
     * @param   array   $array_primary  Primary array to be taken into account, only if the frequency parameter is greater than zero
     * @return  mixed
     */
    public static function randomElement( $array = array('a', 'b', 'c'), $frecuency = 0, $array_primary = [] ){
        if (!$array) {
            return null;
        }

        if(  ! empty( $frecuency ) && ! empty( $array_primary ) && \array_intersect( $array, $array_primary ) ){
            $value_random = rand(1,100);
            $value_freq   = $frecuency;
            if( $value_freq > $value_random){
                $elements = static::randomElements($array_primary, 1);
            }else{
                $elements = static::randomElements($array, 1);
            }
        }else{
            $elements = static::randomElements($array, 1);
        }

        return $elements[0];
    }

    /**
     * Calculate the exact number or random range that will be generated
     * in the end only returns a number already calculated.
     *
     * @since   1.0     2019-09-21      Release
     *
     * @param   integer $type           1 = Exact number, 0 or empty = a random range
     * @param   integer $number         Initial number
     * @param   integer $to             Final number if it is of the random range type
     * @return  int
     */
    public static function numberToGenerate( $type = 1, int $exact = 0, int $from = 0, int $to = 0 ){
        return $type == 1 ? $exact : rand( $from, $to );
    }
}