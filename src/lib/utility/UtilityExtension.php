<?php
/**
 *
 * Utility Twig Extension is the utility belt of useful functions
 * {{github url here}}
 *
 * Utility Library
 *
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Sim Kim Sia
 * @link http://simkimsia.com
 * @author Sim Kim Sia (kimcity@gmail.com)
 * @package twig
 * @subpackage twig.extensions.utility
 * @filesource
 * @version 0.1
 * @lastmodified 2013-03-13
 */

//namespace UtilityTwigExtension;

class Utility_Twig_Extension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'utility';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {

        $fonctions = array();

        $fonctions[] = new \Twig_SimpleFunction('pad_leading_zero', array($this, 'padLeadingZero'));

        return $fonctions;

    }

    /**
     * Pads any string with leading zeroes
     * 
     * @param string $string
     * @return int 
     */
    public function padLeadingZero($string, $length=8)
    {
        return str_pad($string, $length, '0', STR_PAD_LEFT);
    }

}