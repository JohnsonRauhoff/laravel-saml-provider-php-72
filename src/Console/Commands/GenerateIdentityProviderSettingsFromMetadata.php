<?php namespace CharlesRumley\Saml\Console\Commands;

use CharlesRumley\Saml\Utils\IdentityProviderConfigurationBuilder;
use Exception;
use Illuminate\Console\Command;

class GenerateIdentityProviderSettingsFromMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saml:idp-settings-from-metadata {metadataPath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OneLogin settings from a metadata file (.xml) provided by identity provider';

    /**
     * @var IdentityProviderConfigurationBuilder
     */
    protected $builder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IdentityProviderConfigurationBuilder $builder)
    {
        parent::__construct();

        $this->builder = $builder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('metadataPath');

        $this->assertFileExists($path);

        $this->assertFileIsXml($path);

        $this->comment('Place the following in saml.php, under `idp`');

        $this->info(
            var_export($this->builder->withIdentityProviderMetadataFile($path)->identityProviderSettings(), true)
        );
    }

    private function assertFileExists($path)
    {
        if (!file_exists($path)) {
            throw new Exception("Failed asserting $path exists");
        }
    }

    private function assertFileIsXml($path)
    {
        if (!($actualMimeType = mime_content_type($path)) == ($expectedMimeType = 'text/xml')) {
            throw new Exception(
                "Failed asserting $path is an XML file. Expected $expectedMimeType, got $actualMimeType"
            );
        }
    }
}
