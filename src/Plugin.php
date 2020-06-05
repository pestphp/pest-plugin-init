<?php

declare(strict_types=1);

namespace Pest\Init;

use Pest\Contracts\Plugins\HandlesArguments;
use Pest\Exceptions\ShouldNotHappen;
use Pest\TestSuite;
use Symfony\Component\Console\Output\OutputInterface;

final class Plugin implements HandlesArguments
{
    private const INIT_OPTION = 'init';

    private const STUBS = [
        'Pest.php'    => 'Pest.php',
        'Helpers.php' => 'Helpers.php',
    ];

    /** @var OutputInterface */
    private $output;

    /** @var TestSuite */
    private $testSuite;

    public function __construct(TestSuite $testSuite, OutputInterface $output)
    {
        $this->testSuite = $testSuite;
        $this->output    = $output;
    }

    public function handleArguments(array $originals): array
    {
        if (!isset($originals[1]) || $originals[1] !== self::INIT_OPTION) {
            return $originals;
        }

        $this->init();

        exit(0);
    }

    private function init(): void
    {
        $testsBaseDir = "{$this->testSuite->rootPath}/tests";

        if (!is_dir($testsBaseDir)) {
            if (!mkdir($testsBaseDir) && !is_dir($testsBaseDir)) {
                throw ShouldNotHappen::fromMessage("Directory `{$testsBaseDir}` was not created");
            }

            $this->output->writeln('Created `tests` directory');
        }

        foreach (self::STUBS as $from => $to) {
            $fromPath = __DIR__ . "/../stubs/$from";
            $toPath   = "$testsBaseDir/$to";

            if (file_exists($toPath)) {
                $this->output->writeln("File `tests/{$to}` already exists, skipped");

                continue;
            }

            if (!copy($fromPath, $toPath)) {
                throw ShouldNotHappen::fromMessage("Failed to copy stub `{$from}` to `{$toPath}`");
            }

            $this->output->writeln("Created `{$to}` file");
        }

        $this->output->writeln('Pest initialised!');
    }
}
