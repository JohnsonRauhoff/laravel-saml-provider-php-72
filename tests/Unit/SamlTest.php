<?php namespace Tests\Unit;

use CharlesRumley\SamlProvider\Saml;
use OneLogin\Saml2\Auth;
use Tests\UnitTest;

class SamlTest extends UnitTest
{

    /**
     * @test
     */
    public function testItMayBeConstructedWithOneLogin()
    {
        $onelogin = new Auth();
        $this->assertInstanceOf(Saml::class, new Saml());
    }

}
