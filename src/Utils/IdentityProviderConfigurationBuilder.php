<?php namespace CharlesRumley\Saml\Utils;

use Exception;
use SimpleXMLElement;

/**
 * Class IdentityProviderConfigurationBuilder
 *
 * Provides methods for initializing OneLogin identity provider configuration from XML file
 *
 * @package CharlesRumley\SamlProvider
 */
class IdentityProviderConfigurationBuilder
{
    /**
     * @var SimpleXmlElement
     */
    protected $identityProviderMetadata;

    public function withIdentityProviderMetadataFile($path)
    {
        $this->identityProviderMetadata = simplexml_load_file($path);

        $this->assertDocumentConformsToOasisMetadataSpec($this->identityProviderMetadata);

        return $this;
    }

    protected function assertDocumentConformsToOasisMetadataSpec(SimpleXMLElement $document)
    {
        $namespaces = $document->getNamespaces();
        $expectedNamespace = 'md';
        $expectedSpec = 'urn:oasis:names:tc:SAML:2.0:metadata';

        if (!array_key_exists($expectedNamespace, $namespaces)) {
            throw new Exception("Failed asserting document does not contain expected namespace '$expectedNamespace'");
        }

        if ($namespaces[$expectedNamespace] != $expectedSpec) {
            throw new Exception(
                "Failed asserting document namespace '$expectedNamespace' matches expected spec '$expectedNamespace''"
            );
        }
    }

    public function identityProviderSettings()
    {
        return [
            'entityId' => $this->identityProviderEntityId(),
            'singleSignOnService' => [
                'url' => $this->identityProviderSsoLocation('urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST'),
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ],
            'singleLogoutService' => [
                'url' => $this->identityProviderSlsLocation('urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect'),
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ],
            'x509cert' => $this->certificate('signing')
        ];
    }

    public function identityProviderEntityId()
    {
        // todo only checking the first one, can there be more?
        return (string)$this->identityProviderMetadata->xpath('/md:EntityDescriptor/@entityID')[0];
    }

    public function identityProviderSsoLocation($forBinding)
    {
        foreach ($this->identityProviderMetadata->xpath(
            '/md:EntityDescriptor/md:IDPSSODescriptor/md:SingleSignOnService'
        ) as $ssoEntry) {
            $binding = (string)$ssoEntry->attributes()->Binding;
            if ($binding == $forBinding) {
                return (string)$ssoEntry->attributes()->Location;
            }
        }

        return null;
    }

    public function identityProviderSlsLocation($forBinding)
    {
        foreach ($this->identityProviderMetadata->xpath(
            '/md:EntityDescriptor/md:IDPSSODescriptor/md:SingleLogoutService'
        ) as $slsEntry) {
            $binding = (string)$slsEntry->attributes()->Binding;
            if ($binding == $forBinding) {
                return (string)$slsEntry->attributes()->Location;
            }
        }

        return null;
    }

    /**
     * @param string $forUse Use certificate is designated for (i.e. 'signing' or 'encryption')
     * @return string
     */
    public function certificate($forUse)
    {
        foreach ($this->identityProviderMetadata->xpath(
            '/md:EntityDescriptor/md:IDPSSODescriptor/md:KeyDescriptor'
        ) as $keyDescriptor) {
            $use = $keyDescriptor->attributes()->use;

            if ($use == $forUse) {
                // register a prefix for the default namespace so we can use it in XPath
                $prefix = 'sig';
                $keyDescriptor->registerXPathNamespace($prefix, $keyDescriptor->getDocNamespaces(true, true)['']);

                $certificateText = $keyDescriptor->xpath(
                    "$prefix:KeyInfo/$prefix:X509Data/$prefix:X509Certificate/text()"
                )[0];

                return trim((string)$certificateText);
            }
        }

        return null;
    }

}