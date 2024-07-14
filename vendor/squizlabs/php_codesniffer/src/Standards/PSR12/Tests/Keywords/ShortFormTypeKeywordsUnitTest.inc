<?php
$bar = (bool)$foo;
$bar = (boolean)$foo;
$bar = (bool)$foo;
$bar = (boolean)$foo;
$bar = (int)$foo;
$bar = (integer)$foo;
$bar = (int)$foo;

// Test recognition with whitespace within the cast.
$bar = (  bool  )$foo;
$bar = ( int )$foo;
$bar = (     boolean   	    )$foo;
$bar = (  integer)$foo;
