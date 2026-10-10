<?php

namespace App\Support;

/**
 * Design-system contrast audit.
 *
 * Reads the real tokens out of public/assets/css/style.css (:root) and
 * dark-mode.css ([data-theme="dark"]), resolves var() chains, then checks every
 * documented foreground/background pairing against WCAG 2.2:
 *
 *   - normal text   : 4.5:1  (SC 1.4.3 Contrast (Minimum), AA)
 *   - UI boundaries : 3.0:1  (SC 1.4.11 Non-text Contrast)
 *
 * The audit parses the stylesheets instead of keeping a duplicated PHP colour
 * table, so editing a token that regresses contrast is caught the moment this
 * runs -- there is no second source of truth to drift.
 *
 * @see \App\Console\Commands\ContrastAuditCommand  CLI report  (php artisan audit:contrast)
 * @see \Tests\Unit\DesignSystemContrastTest        CI guard     (php artisan test)
 */
final class ContrastAudit
{
    /** WCAG 2.2 SC 1.4.3 -- minimum ratio for normal-size body text. */
    public const MIN_TEXT = 4.5;

    /** WCAG 2.2 SC 1.4.11 -- minimum ratio for control boundaries and focus rings. */
    public const MIN_UI = 3.0;

    /** Bootstrap variables whose resolution is worth spot-checking by hand. */
    public const BOOTSTRAP_TOKENS = [
        'LIGHT' => [
            '--bs-body-bg',
            '--bs-body-color',
            '--bs-secondary-color',
            '--bs-link-color-rgb',
            '--bs-border-color',
            '--bs-border-radius',
            '--bs-focus-ring-color',
        ],
        'DARK' => [
            '--bs-body-bg',
            '--bs-body-color',
            '--bs-secondary-color',
            '--bs-link-color-rgb',
        ],
    ];

    /**
     * Documented pairings: [foreground, background, minimum, kind, note].
     *
     * @var list<array{0: string, 1: string, 2: float, 3: string, 4: string}>
     */
    private const PAIRS = [
        // --- body & secondary text ------------------------------------------
        ['--color-text',       '--color-bg',             self::MIN_TEXT, 'text', 'body copy'],
        ['--color-text',       '--color-surface',        self::MIN_TEXT, 'text', 'panel copy'],
        ['--color-text',       '--color-surface-raised', self::MIN_TEXT, 'text', 'card / input copy'],
        ['--color-text',       '--color-primary-soft',   self::MIN_TEXT, 'text', 'mark on tint'],
        ['--color-text',       '--color-accent-soft',    self::MIN_TEXT, 'text', 'mark on coral tint'],
        ['--color-text-muted', '--color-bg',             self::MIN_TEXT, 'text', 'captions, hints'],
        ['--color-text-muted', '--color-surface',        self::MIN_TEXT, 'text', 'page-header lede'],
        ['--color-text-muted', '--color-surface-raised', self::MIN_TEXT, 'text', 'card meta'],

        // --- links ------------------------------------------------------------
        ['--color-link',       '--color-bg',             self::MIN_TEXT, 'text', 'inline links'],
        ['--color-link',       '--color-surface',        self::MIN_TEXT, 'text', 'links on panels'],
        ['--color-link-hover', '--color-bg',             self::MIN_TEXT, 'text', 'link hover'],

        // --- navigation / brand -----------------------------------------------
        ['--color-primary',    '--color-bg',             self::MIN_TEXT, 'text', 'active nav, ghost btn'],
        ['--color-primary',    '--color-surface',        self::MIN_TEXT, 'text', 'nav hover'],
        ['--color-primary',    '--color-primary-soft',   self::MIN_TEXT, 'text', 'badge on tint'],
        ['--color-primary',    '--color-text-inverse',   self::MIN_TEXT, 'text', 'inverse check'],

        // --- buttons -----------------------------------------------------------
        ['--color-text-inverse', '--color-primary',       self::MIN_TEXT, 'text', 'primary button'],
        ['--color-text-inverse', '--color-primary-hover', self::MIN_TEXT, 'text', 'primary button hover'],
        ['--color-text-inverse', '--color-accent',        self::MIN_TEXT, 'text', 'accent button'],
        ['--color-text-inverse', '--color-accent-hover',  self::MIN_TEXT, 'text', 'accent button hover'],
        ['--color-text-inverse', '--color-success',       self::MIN_TEXT, 'text', 'success button'],
        ['--color-text-inverse', '--color-success-hover', self::MIN_TEXT, 'text', 'success hover'],
        ['--color-text',         '--color-surface',       self::MIN_TEXT, 'text', 'outline button hover'],
        ['--color-primary-hover','--color-primary-soft',  self::MIN_TEXT, 'text', 'ghost button hover'],

        // --- badges --------------------------------------------------------------
        ['--color-accent-strong', '--color-accent-soft',  self::MIN_TEXT, 'text', 'accent badge'],
        ['--color-success',       '--color-success-soft', self::MIN_TEXT, 'text', 'success badge'],
        ['--color-danger',        '--color-danger-soft',  self::MIN_TEXT, 'text', 'danger badge'],
        ['--color-warning',       '--color-warning-soft', self::MIN_TEXT, 'text', 'warning badge'],
        ['--color-info',          '--color-info-soft',    self::MIN_TEXT, 'text', 'info badge'],

        // --- alerts (body copy sits on the tint, not in the hue) -----------------
        ['--color-text',          '--color-success-soft', self::MIN_TEXT, 'text', 'success alert body'],
        ['--color-text',          '--color-danger-soft',  self::MIN_TEXT, 'text', 'danger alert body'],
        ['--color-text',          '--color-warning-soft', self::MIN_TEXT, 'text', 'warning alert body'],
        ['--color-text',          '--color-info-soft',    self::MIN_TEXT, 'text', 'info alert body'],

        // --- semantic text on the page canvas -------------------------------------
        ['--color-success', '--color-bg', self::MIN_TEXT, 'text', 'success text'],
        ['--color-danger',  '--color-bg', self::MIN_TEXT, 'text', 'error text'],
        ['--color-warning', '--color-bg', self::MIN_TEXT, 'text', 'warning text'],
        ['--color-info',    '--color-bg', self::MIN_TEXT, 'text', 'info text'],
        ['--color-accent',  '--color-bg', self::MIN_TEXT, 'text', 'coral accents'],

        // --- non-text: control boundaries, focus rings (SC 1.4.11) -----------------
        ['--color-border-strong', '--color-bg',            self::MIN_UI, 'ui', 'input border'],
        ['--color-border-strong', '--color-surface-raised', self::MIN_UI, 'ui', 'input border on card'],
        ['--color-focus',         '--color-bg',            self::MIN_UI, 'ui', 'focus ring'],
        ['--color-focus',         '--color-surface',       self::MIN_UI, 'ui', 'focus ring on panel'],
        ['--color-primary',       '--color-bg',            self::MIN_UI, 'ui', 'brand mark / progress'],
    ];

    /* ------------------------------------------------------------------ audit */

    /**
     * Evaluate every documented pairing in both themes.
     *
     * @return list<array{
     *     theme: string, fg: string, bg: string, fgHex: string, bgHex: string,
     *     ratio: float, min: float, kind: string, note: string, pass: bool
     * }>
     */
    public static function run(): array
    {
        $light = self::readTokens('style.css');
        $dark  = array_merge($light, self::readTokens('dark-mode.css'));

        $rows = [];
        foreach (['LIGHT' => $light, 'DARK' => $dark] as $theme => $map) {
            foreach (self::PAIRS as [$fg, $bg, $min, $kind, $note]) {
                $rows[] = self::evaluate($theme, $fg, $bg, $min, $kind, $note, $map);
            }
        }

        return $rows;
    }

    /**
     * Rows that failed their threshold -- including tokens that would not
     * resolve to a hex colour, which is a failure in its own right.
     *
     * @param  list<array<string, mixed>> $rows
     * @return list<array<string, mixed>>
     */
    public static function failures(array $rows): array
    {
        return array_values(array_filter($rows, static fn (array $row): bool => !$row['pass']));
    }

    /**
     * Resolve a subset of Bootstrap 5 variables -- useful for confirming the
     * framework overrides really inherited the theme.
     *
     * @return array<string, string> token => resolved value
     */
    public static function bootstrapOverrides(string $theme): array
    {
        $map   = $theme === 'DARK'
            ? array_merge(self::readTokens('style.css'), self::readTokens('dark-mode.css'))
            : self::readTokens('style.css');
        $names = self::BOOTSTRAP_TOKENS[$theme] ?? [];

        $out = [];
        foreach ($names as $name) {
            $out[$name] = isset($map[$name])
                ? self::resolveVars($map[$name], $map)
                : 'MISSING';
        }

        return $out;
    }

    /* ------------------------------------------------------- colour resolution */

    /**
     * @param  array<string, string> $map
     * @return array<string, mixed>
     */
    private static function evaluate(
        string $theme,
        string $fg,
        string $bg,
        float $min,
        string $kind,
        string $note,
        array $map
    ): array {
        $fgHex = isset($map[$fg]) ? self::resolveVars($map[$fg], $map) : 'MISSING';
        $bgHex = isset($map[$bg]) ? self::resolveVars($map[$bg], $map) : 'MISSING';

        $resolvable = self::isHex($fgHex) && self::isHex($bgHex);
        $ratio      = $resolvable ? self::contrast($fgHex, $bgHex) : 0.0;

        return [
            'theme' => $theme,
            'fg'    => $fg,
            'bg'    => $bg,
            'fgHex' => $fgHex,
            'bgHex' => $bgHex,
            'ratio' => $ratio,
            'min'   => $min,
            'kind'  => $kind,
            'note'  => $resolvable
                ? $note
                : "unresolvable token ($fg = $fgHex, $bg = $bgHex)",
            'pass'  => $resolvable && $ratio >= $min,
        ];
    }

    private static function isHex(string $value): bool
    {
        return (bool) preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value);
    }

    /** Absolute path of one of the audited stylesheets. */
    private static function cssFile(string $name): string
    {
        return dirname(__DIR__, 2) . '/public/assets/css/' . $name;
    }

    /**
     * Pull every `--token: value;` declaration out of a stylesheet.
     *
     * Later declarations win, which is what makes merging dark-mode.css over
     * style.css behave the way CSS does.
     *
     * @return array<string, string>
     */
    private static function readTokens(string $name): array
    {
        $file = self::cssFile($name);
        $css  = @file_get_contents($file);

        if ($css === false) {
            throw new \RuntimeException("Unable to read stylesheet: $file");
        }

        $out = [];
        preg_match_all('/(--[a-zA-Z0-9-]+)\s*:\s*([^;]+);/', $css, $matches, PREG_SET_ORDER);
        foreach ($matches as $declaration) {
            $out[$declaration[1]] = trim($declaration[2]);
        }

        if ($out === []) {
            throw new \RuntimeException("No design tokens found in: $file");
        }

        return $out;
    }

    /**
     * Replace every var(--x[, fallback]) with its resolved value, repeatedly.
     *
     * @param  array<string, string> $map
     */
    public static function resolveVars(string $value, array $map, int $depth = 0): string
    {
        if ($depth > 20) {
            return $value;
        }

        $next = preg_replace_callback(
            '/var\(\s*(--[a-zA-Z0-9-]+)\s*(?:,\s*([^)]*))?\)/',
            static function (array $match) use ($map, $depth): string {
                if (isset($map[$match[1]])) {
                    return self::resolveVars($map[$match[1]], $map, $depth + 1);
                }

                return isset($match[2]) && $match[2] !== '' ? trim($match[2]) : $match[0];
            },
            $value
        );

        return $next === $value ? $value : self::resolveVars($next, $map, $depth + 1);
    }

    /* -------------------------------------------------------------- colour math */

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public static function hexToRgb(string $hex): array
    {
        $hex = ltrim(trim($hex), '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    /** WCAG relative luminance. */
    public static function luminance(string $hex): float
    {
        [$r, $g, $b] = array_map(static fn (int $channel): float => ($c = $channel / 255) <= 0.04045
            ? $c / 12.92
            : pow(($c + 0.055) / 1.055, 2.4), self::hexToRgb($hex));

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    public static function contrast(string $fg, string $bg): float
    {
        $a     = self::luminance($fg);
        $b     = self::luminance($bg);
        $light = max($a, $b);
        $dark  = min($a, $b);

        return ($light + 0.05) / ($dark + 0.05);
    }
}
