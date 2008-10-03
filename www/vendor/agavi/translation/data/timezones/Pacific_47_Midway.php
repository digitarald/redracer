<?php

/**
 * Data file for Pacific/Midway timezone, compiled from the olson data.
 *
 * Auto-generated by the phing olson task on 09/19/2008 17:06:40
 *
 * @package    agavi
 * @subpackage translation
 *
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: Pacific_47_Midway.php 2905 2008-09-19 17:08:53Z david $
 */

return array (
  'types' => 
  array (
    0 => 
    array (
      'rawOffset' => -39600,
      'dstOffset' => 0,
      'name' => 'NST',
    ),
    1 => 
    array (
      'rawOffset' => -39600,
      'dstOffset' => 3600,
      'name' => 'NDT',
    ),
    2 => 
    array (
      'rawOffset' => -39600,
      'dstOffset' => 0,
      'name' => 'BST',
    ),
    3 => 
    array (
      'rawOffset' => -39600,
      'dstOffset' => 0,
      'name' => 'SST',
    ),
  ),
  'rules' => 
  array (
    0 => 
    array (
      'time' => -2177410232,
      'type' => 0,
    ),
    1 => 
    array (
      'time' => -428504400,
      'type' => 1,
    ),
    2 => 
    array (
      'time' => -420645600,
      'type' => 0,
    ),
    3 => 
    array (
      'time' => -86878800,
      'type' => 2,
    ),
    4 => 
    array (
      'time' => 439038000,
      'type' => 3,
    ),
  ),
  'finalRule' => 
  array (
    'type' => 'static',
    'name' => 'SST',
    'offset' => -39600,
    'startYear' => 1984,
  ),
  'name' => 'Pacific/Midway',
);

?>