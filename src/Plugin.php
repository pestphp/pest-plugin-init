<?php

declare(strict_types=1);

namespace Pest\PluginInit;

use Pest\Contracts\Plugins\HandlesArguments;
use Pest\TestSuite;

/**
 * @internal
 */
final class Plugin implements HandlesArguments
{
    private const INIT_OPTION = 'init';

    private const STUBS = [
        'Pest.php' => 'Pest.php',
        'Helpers.php' => 'Helpers.php',
    ];

    public function handleArguments(TestSuite $testSuite, array $originals): array
    {
        if (!isset($originals[1]) || $originals[1] !== self::INIT_OPTION) {
            return $originals;
        }

        $this->initialisePest();

        exit(0);
    }

    private function initialisePest(): void
    {
        $testsBaseDir = getcwd() . '/tests';

        if (!is_dir($testsBaseDir)) {
            if (!mkdir($testsBaseDir) && !is_dir($testsBaseDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $testsBaseDir));
            }

            echo "Created 'tests' directory\n";
        }

        foreach (self::STUBS as $from => $to) {
            $fromPath = __DIR__ . "/../stubs/$from";
            $toPath   = "$testsBaseDir/$to";

            if (file_exists($toPath)) {
                echo "File 'tests/{$to}' already exists, skipped\n";

                continue;
            }

            if (!copy($fromPath, $toPath)) {
                throw new \RuntimeException("Failed to copy stub '$stubs' to '$toPath'");
            }

            echo "Created '{$to}' file\n";
        }

        echo "Pest initialised!\n";
    }
}
