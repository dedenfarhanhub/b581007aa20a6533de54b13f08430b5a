<?php

// True
function myFunction($arg1, $arg2 = true)
{
}

function myFunction($arg1, $arg2 = TRUE)
{
}

function myFunction($arg1, $arg2 = True)
{
}

if ($variable === true) {
}
if ($variable === TRUE) {
}
if ($variable === True) {
}


// False
function myFunction($arg1, $arg2 = false)
{
}

function myFunction($arg1, $arg2 = FALSE)
{
}

function myFunction($arg1, $arg2 = False)
{
}

if ($variable === false) {
}
if ($variable === FALSE) {
}
if ($variable === False) {
}


// Null
function myFunction($arg1, $arg2 = null)
{
}

function myFunction($arg1, $arg2 = NULL)
{
}

function myFunction($arg1, $arg2 = Null)
{
}

if ($variable === null) {
}
if ($variable === NULL) {
}
if ($variable === Null) {
}

$x = new stdClass();
$x->NULL = 7;

use Zend\Log\Writer\NULL as NullWriter;

new \Zend\Log\Writer\NULL();

namespace False;

class True extends Null implements False
{
}

use True\Something;
use Something\True;

class MyClass
{
    public function myFunction()
    {
        $var = array('foo' => new True());
    }
}

$x = $f ? FALSE : true;
$x = $f ? FALSE : true;

class MyClass
{
    // Spice things up a little.
    const TRUE = false;
}

var_dump(MyClass::TRUE);

function tRUE()
{
}

$input->getFilterChain()->attachByName('Null', ['type' => Null::TYPE_STRING]);

// Issue #3332 - ignore type declarations, but not default values.
class TypedThings
{
    const MYCONST = FALSE;

    public int|false $int = FALSE;
    public Type|null $int = new MyObj(NULL);

    private function typed(int|false $param = NULL, Type|null $obj = new MyObj(FALSE)): string|false|null
    {
        if (TRUE === FALSE) {
            return NULL;
        }
    }
}

$cl = function (int|false $param = NULL, Type|null $obj = new MyObj(FALSE)): string|false|null {
};

// Adding some extra tests to safeguard that function declarations which don't create scope are handled correctly.
interface InterfaceMethodsWithReturnTypeNoScopeOpener
{
    private function typed($param = TRUE): string|false|null;
}

abstract class ClassMethodsWithReturnTypeNoScopeOpener
{
    abstract public function typed($param = FALSE): true;
}

// Additional tests to safeguard improved property type skip logic.
readonly class Properties
{
    use SomeTrait {
        sayHello as private myPrivateHello;
    }

    public Type|false|null $propertyA = array(
        'itemA' => TRUE,
        'itemB' => FALSE,
        'itemC' => NULL,
    ), $propertyB = FALSE;

    protected \FullyQualified&Partially\Qualified&namespace\Relative $propertyC;
    var ?true $propertyD;
    static array|callable|false|self|parent $propertyE = TRUE;
    private
        // phpcs:ignore Stnd.Cat.Sniff -- for reasons.
    true      /*comment*/
        $propertyF = TRUE;

    public function __construct(
        public false|null      $promotedPropA,
        readonly callable|true $promotedPropB,
    )
    {
        static $var;
        echo static::class;
        static::foo();
        $var = $var instanceof static;
        $obj = new static();
    }

    public static function foo(): static|self|false
    {
        $callable = static function () {
        };
    }
}

// PHP 8.3 introduces typed constants.
class TypedConstants
{
    const MyClass|null|true|false MYCONST = FALSE;
}

// Global constants can not be typed.
const MYCONST = TRUE;

// Last coding/parse error.
// This has to be the last test in the file.
function UnclosedCurly(): false
{
