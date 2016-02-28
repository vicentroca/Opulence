<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Authentication\Tokens\JsonWebTokens;

use InvalidArgumentException;
use Opulence\Authentication\Tokens\Signatures\Algorithms;
use Opulence\Authentication\Tokens\Signatures\AsymmetricSigner;
use Opulence\Authentication\Tokens\Signatures\SymmetricSigner;

/**
 * Tests the signed JWT
 */
class SignedJwtTest extends \PHPUnit_Framework_TestCase
{
    /** @var JwtHeader The header to use in tests */
    private $header = null;
    /** @var JwtPayload The payload to use in tests */
    private $payload = null;

    /**
     * Sets up the tests
     */
    public function setUp()
    {
        $this->header = new JwtHeader();
        $this->payload = new JwtPayload();
    }

    /**
     * Tests creating token from string
     */
    public function testDecodingEncodedToken()
    {
        $signer = new SymmetricSigner(Algorithms::SHA256, "public");
        $unsignedJwt = new UnsignedJwt($this->header, $this->payload);
        $signature = $signer->sign($unsignedJwt->getUnsignedValue());
        $signedJwt = new SignedJwt($this->header, $this->payload, $signature);
        $token = $signedJwt->encode();
        $this->assertTokensEqual($signedJwt, SignedJwt::createFromString($token), false);
    }

    /**
     * Tests encoding and decoding RSA algorithms
     */
    public function testEncodingDecodingRsaAlgorithms()
    {
        $algorithms = [
            Algorithms::RSA_SHA256 => "sha256",
            Algorithms::RSA_SHA384 => "sha384",
            Algorithms::RSA_SHA512 => "sha512"
        ];

        foreach ($algorithms as $algorithm => $digestAlgorithm) {
            $privateKey = openssl_pkey_new(
                [
                    "digest_alg" => $digestAlgorithm,
                    "private_key_bits" => 1024,
                    "private_key_type" => OPENSSL_KEYTYPE_RSA
                ]
            );
            $publicKey = openssl_pkey_get_details($privateKey);
            $signer = new AsymmetricSigner($algorithm, $publicKey["key"], $privateKey);
            $unsignedJwt = new UnsignedJwt($this->header, $this->payload);
            $unsignedJwt->getHeader()->add("alg", $algorithm);
            $signature = $signer->sign($unsignedJwt->getUnsignedValue());
            $signedJwt = new SignedJwt($this->header, $this->payload, $signature);
            $token = $signedJwt->encode();
            $this->assertTokensEqual($signedJwt, SignedJwt::createFromString($token), false);
        }
    }

    /**
     * Tests that an exception is thrown with an invalid number of segments
     */
    public function testExceptionThrownWithInvalidNumberSegments()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        SignedJwt::createFromString("foo.bar");
    }

    /**
     * Tests that an exception is thrown with no algorithm set
     */
    public function testExceptionThrownWithNoAlgorithmSet()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        SignedJwt::createFromString(base64_encode("foo") . "." . base64_encode("bar") . "." . base64_encode("baz"));
    }

    /**
     * Tests getting the signature
     */
    public function testGettingSignature()
    {
        $jwt = new SignedJwt($this->header, $this->payload, "signature");
        $this->assertEquals("signature", $jwt->getSignature());
    }

    /**
     * Asserts whether two tokens are equal
     *
     * @param SignedJwt $a The first token
     * @param SignedJwt $b The second token
     * @param bool $checkSignature Whether or not to check the signatures
     */
    private function assertTokensEqual(SignedJwt $a, SignedJwt $b, bool $checkSignature)
    {
        $this->assertEquals($a->getHeader(), $b->getHeader());
        $this->assertEquals($a->getPayload(), $b->getPayload());

        if ($checkSignature) {
            $this->assertEquals($a->getSignature(), $b->getSignature());
        }
    }
}