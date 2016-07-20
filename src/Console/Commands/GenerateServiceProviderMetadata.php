<?php namespace CharlesRumley\Saml\Console\Commands;

use CharlesRumley\Saml\Saml;
use Illuminate\Console\Command;

class GenerateServiceProviderMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saml:sp-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output service provider metadata file (.xml) based on configuration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Saml $saml)
    {
        $this->info('Generating metadata');
        $metadata = $saml->metadata();

        $filename = 'metadata.xml';
        $this->info("Writing metadata to $filename");
        file_put_contents($filename, $metadata);
    }
}
