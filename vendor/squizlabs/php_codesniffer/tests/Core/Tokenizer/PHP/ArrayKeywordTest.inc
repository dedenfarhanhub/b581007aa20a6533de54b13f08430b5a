<?php

/* testEmptyArray */
$var = array();

/* testArrayWithSpace */
$var = array(1 => 10);

/* testArrayWithComment */
$var = array /*comment*/
(1 => 10);

/* testNestingArray */
$var = array(
    /* testNestedArray */
    array(
        'key' => 'value',

        /* testClosureReturnType */
        'closure' => function ($a) use ($global): array {
        },
    ),
);

/* testFunctionDeclarationParamType */
function typedParam(array $a)
{
}

/* testFunctionDeclarationReturnType */
function returnType($a): int|array|null
{
}

class Bar
{
    /* testClassConst */
    const ARRAY = [];

    /* testClassMethod */
    public function array()
    {
    }

    /* testOOConstType */
    const array /* testTypedOOConstName */
        ARRAY = /* testOOConstDefault */
        array();

    /* testOOPropertyType */
    protected array $property;
}

class DNFTypes
{
    /* testOOConstDNFType */
    const (A&B)|array|(C&D) NAME = [];

    /* testOOPropertyDNFType */
    protected (A&B)|array|null $property;

    /* testFunctionDeclarationParamDNFType */
    public function name(null|array|(A&B) $param)
    {
        /* testClosureDeclarationParamDNFType */
        $cl = function (array|(A&B) $param) {
        };

        /* testArrowDeclarationReturnDNFType */
        $arrow = fn($a): (A&B)|array => new $a;
    }
}
