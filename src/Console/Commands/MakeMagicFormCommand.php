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
    protected $signature = 'make:magic-form {name : The name of the form class} {--group= : Group name for the form}';

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

    /**
     * Execute the console command.
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->requestGroup();

        parent::handle();
    }

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

    private function requestGroup(): void
    {
        $group = $this->ask('Enter group name', $this->group);

        if (!empty($group)) {
            $this->group = $group;
        }
    }

    protected function replaceClass($stub, $name): array|string
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('{{group}}', addslashes($this->group), $stub);
    }
}
