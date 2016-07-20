<?php namespace Tests\Unit;

use CharlesRumley\SamlProvider\Saml;
use Tests\UnitTest;

class SamlTest extends UnitTest
{

    /**
     * @test
     */
    public function testItMayBeConstructedWithOneLogin()
    {
        $onelogin = new \OneLogin_Saml2_Auth();
        $this->assertInstanceOf(Saml::class, new Saml());
    }

}
