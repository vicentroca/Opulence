<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Authentication\Tokens;

/**
 * The interface for unsigned tokens to implement
 */
interface IUnsignedToken
{
    /**
     * Gets the unsigned value of the token
     *
     * @return string The unsigned value
     */
    public function getUnsignedValue() : string;
}