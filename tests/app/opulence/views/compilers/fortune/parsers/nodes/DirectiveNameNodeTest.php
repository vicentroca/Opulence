<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Tests the directive name node
 */
namespace Opulence\Views\Compilers\Fortune\Parsers\Nodes;

class DirectiveNameNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the "is a" methods
     */
    public function testIsAMethods()
    {
        $node = new DirectiveNameNode();
        $this->assertFalse($node->isComment());
        $this->assertFalse($node->isDirective());
        $this->assertTrue($node->isDirectiveName());
        $this->assertFalse($node->isExpression());
        $this->assertFalse($node->isSanitizedTag());
        $this->assertFalse($node->isUnsanitizedTag());
    }
}