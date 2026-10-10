<?php

namespace Tests\Unit;

use App\Support\ContrastAudit;
use PHPUnit\Framework\TestCase;

/**
 * Guards the WCAG 2.2 AA requirement for the design system.
 *
 * Every documented colour pairing is resolved straight from the stylesheets in
 * both themes, so changing a token in style.css or dark-mode.css to something
 * that fails contrast breaks `php artisan test` rather than shipping silently.
 */
class DesignSystemContrastTest extends TestCase
{
    public function test_light_theme_meets_wcag_aa(): void
    {
        $this->assertNoFailures($this->rowsFor('LIGHT'), 'LIGHT');
    }

    public function test_dark_theme_meets_wcag_aa(): void
    {
        $this->assertNoFailures($this->rowsFor('DARK'), 'DARK');
    }

    public function test_audit_covers_both_themes_and_stays_comprehensive(): void
    {
        $rows = ContrastAudit::run();

        $themes = array_unique(array_column($rows, 'theme'));
        sort($themes);

        $this->assertSame(['DARK', 'LIGHT'], $themes);
        $this->assertGreaterThan(
            50,
            count($rows),
            'The documented pair list has shrunk -- check the audits is not silently skipping pairings.'
        );
    }

    public function test_every_pair_resolves_to_a_real_hex_colour(): void
    {
        foreach (ContrastAudit::run() as $row) {
            $pair = sprintf('%s on %s [%s]', $row['fg'], $row['bg'], $row['theme']);

            $this->assertMatchesRegularExpression(
                '/^#[0-9a-f]{3}([0-9a-f]{3})?$/i',
                $row['fgHex'],
                "Foreground did not resolve to a hex colour: $pair -> {$row['fgHex']}"
            );
            $this->assertMatchesRegularExpression(
                '/^#[0-9a-f]{3}([0-9a-f]{3})?$/i',
                $row['bgHex'],
                "Background did not resolve to a hex colour: $pair -> {$row['bgHex']}"
            );
        }
    }

    public function test_bootstrap_overrides_resolve_in_both_themes(): void
    {
        foreach (['LIGHT', 'DARK'] as $theme) {
            foreach (ContrastAudit::bootstrapOverrides($theme) as $token => $value) {
                $this->assertNotSame(
                    'MISSING',
                    $value,
                    "Bootstrap override $token is not defined for the $theme theme."
                );
            }
        }
    }

    /** The audit maths, checked against a known reference value. */
    public function test_wcag_contrast_maths_is_correct(): void
    {
        $this->assertEqualsWithDelta(21.0, ContrastAudit::contrast('#000000', '#FFFFFF'), 0.01);
        $this->assertEqualsWithDelta(1.0, ContrastAudit::contrast('#FFFFFF', '#FFFFFF'), 0.01);

        // #767676 on white is the canonical 4.54:1 boundary value.
        $this->assertEqualsWithDelta(4.54, ContrastAudit::contrast('#767676', '#FFFFFF'), 0.01);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function rowsFor(string $theme): array
    {
        $rows = array_values(array_filter(
            ContrastAudit::run(),
            static fn (array $row): bool => $row['theme'] === $theme
        ));

        $this->assertNotEmpty($rows, "No audit rows were produced for the $theme theme.");

        return $rows;
    }

    /**
     * @param  list<array<string, mixed>> $rows
     */
    private function assertNoFailures(array $rows, string $theme): void
    {
        $failures = ContrastAudit::failures($rows);

        if ($failures !== []) {
            $lines = array_map(
                static fn (array $row): string => sprintf(
                    '  %s on %s (%s on %s) = %s:1, needs %s:1 -- %s',
                    $row['fg'],
                    $row['bg'],
                    $row['fgHex'],
                    $row['bgHex'],
                    number_format($row['ratio'], 2),
                    number_format($row['min'], 1),
                    $row['note']
                ),
                $failures
            );

            $this->fail(sprintf(
                "%s theme: %d of %d pairings fail WCAG AA:%s%s",
                $theme,
                count($failures),
                count($rows),
                PHP_EOL,
                implode(PHP_EOL, $lines)
            ));
        }

        $this->assertCount(count($rows), $rows);
    }
}
