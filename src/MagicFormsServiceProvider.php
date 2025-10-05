<?php

namespace Waynelogic\MagicForms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Waynelogic\MagicForms\Console\Commands\MakeMagicFormCommand;

class MagicFormsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'magic-forms';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->discoversMigrations()
            ->runsMigrations()
            ->hasCommand(MakeMagicFormCommand::class);
    }
}
