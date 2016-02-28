<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Authentication\Tokens\Signatures\Factories;

use InvalidArgumentException;
use Opulence\Authentication\Tokens\Signatures\Algorithms;
use Opulence\Authentication\Tokens\Signatures\IAsymmetricSigner;
use Opulence\Authentication\Tokens\Signatures\ISymmetricSigner;

/**
 * Tests the signer factory
 */
class SignerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var SignerFactory The factory to use in tests */
    private $factory = null;

    /**
     * Sets up the tests
     */
    public function setUp()
    {
        $this->factory = new SignerFactory();
    }

    /**
     * Tests creating asymmetric signers
     */
    public function testCreatingAsymmetricSigners()
    {
        $algorithms = [Algorithms::RSA_SHA256, Algorithms::RSA_SHA384, Algorithms::RSA_SHA512];

        foreach ($algorithms as $algorithm) {
            $signer = $this->factory->createSigner($algorithm, "public", "private");
            $this->assertInstanceOf(IAsymmetricSigner::class, $signer);
            $this->assertEquals($algorithm, $signer->getAlgorithm());
        }
    }

    /**
     * Tests creating symmetric signers
     */
    public function testCreatingSymmetricSigners()
    {
        $algorithms = [Algorithms::SHA256, Algorithms::SHA384, Algorithms::SHA512];

        foreach ($algorithms as $algorithm) {
            $signer = $this->factory->createSigner($algorithm, "public");
            $this->assertInstanceOf(ISymmetricSigner::class, $signer);
            $this->assertEquals($algorithm, $signer->getAlgorithm());
        }
    }

    /**
     * Tests that an exception is thrown when no private key is specified for an asymmetric algorithm
     */
    public function testExceptionThrownWhenNoPrivateKeySpecifiedForAsymmetricAlgorithm()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->factory->createSigner(Algorithms::RSA_SHA256, "public");
    }
}