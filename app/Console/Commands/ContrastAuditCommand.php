<?php

namespace App\Console\Commands;

use App\Support\ContrastAudit;
use Illuminate\Console\Command;

/**
 * Prints the design-system contrast audit as a table.
 *
 * Exits non-zero when any pairing misses its WCAG 2.2 threshold, so it can be
 * used directly as a CI gate.
 *
 *   php artisan audit:contrast
 *   php artisan audit:contrast -v   # also resolve the Bootstrap 5 overrides
 */
class ContrastAuditCommand extends Command
{
    protected $signature = 'audit:contrast';

    protected $description = 'Check every design-system colour pairing against WCAG 2.2 AA in light and dark themes';

    public function handle(): int
    {
        $rows     = ContrastAudit::run();
        $failures = ContrastAudit::failures($rows);

        $this->table(
            ['Theme', 'Pair', 'Foreground', 'Background', 'Ratio', 'Needs', 'Result'],
            array_map(static fn (array $row): array => [
                $row['theme'],
                str_replace('--color-', '', $row['fg']) . ' on ' . str_replace('--color-', '', $row['bg']),
                $row['fgHex'],
                $row['bgHex'],
                number_format($row['ratio'], 2) . ':1',
                number_format($row['min'], 1) . ':1',
                $row['pass'] ? 'PASS' : 'FAIL',
            ], $rows)
        );

        $this->newLine();
        $this->line(sprintf(
            'Pairs checked: %d   Failures: %d   %s',
            count($rows),
            count($failures),
            $failures === []
                ? '<info>ALL PASS (WCAG AA)</info>'
                : '<error>REVIEW REQUIRED</error>'
        ));

        if ($this->output->isVerbose()) {
            $this->newLine();
            $this->line('Bootstrap 5 overrides resolved from the stylesheets:');
            foreach (array_keys(ContrastAudit::BOOTSTRAP_TOKENS) as $theme) {
                foreach (ContrastAudit::bootstrapOverrides($theme) as $token => $value) {
                    $this->line(sprintf('  %-6s %-24s %s', $theme, $token, $value));
                }
            }
        }

        if ($failures !== []) {
            $this->newLine();
            foreach ($failures as $failure) {
                $this->error(sprintf(
                    '[%s] %s on %s = %.2f:1 (needs %.1f:1) -- %s',
                    $failure['theme'],
                    $failure['fg'],
                    $failure['bg'],
                    $failure['ratio'],
                    $failure['min'],
                    $failure['note']
                ));
            }

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
