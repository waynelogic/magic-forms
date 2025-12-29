<?php

namespace Waynelogic\MagicForms\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;

class MakeMagicFormCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:magic-form {name : The name of the form class}';

    /**
     * The group name for the form.
     *
     * @var string
     */
    protected string $group = 'Common Form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new magic form class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Magic Form';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/magic-form.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\MagicForms';
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form class'],
        ];
    }

    protected function replaceClass($stub, $name): array|string
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('{{group}}', addslashes($this->argument('name')), $stub);
    }
}
