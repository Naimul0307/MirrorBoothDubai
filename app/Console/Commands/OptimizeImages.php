<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeImages extends Command
{
    protected $signature   = 'images:optimize
                                {--dry-run : Show what would be processed without making changes}
                                {--quality=45 : WebP quality 1–100, lower = smaller (default 45)}';

    protected $description = 'Re-compress hero slide images and generate responsive logo sizes using PHP GD (no external tools needed)';

    private string $heroDir = 'uploads/hero_slides/thumb/large';
    private string $logoSrc = 'assets/images/logo.webp';
    private string $logo1x  = 'assets/images/logo.webp';
    private string $logo2x  = 'assets/images/logo@2x.webp';

    public function handle(): int
    {
        $this->checkDependencies();

        $dryRun  = $this->option('dry-run');
        $quality = (int) $this->option('quality');

        $this->newLine();
        $this->info('═══════════════════════════════════════════════');
        $this->info('  Mirror Booth Dubai — Image Optimiser');
        $this->info('  Using PHP ' . PHP_VERSION . ' with ' . $this->getDriver());
        $this->info('═══════════════════════════════════════════════');
        $dryRun && $this->warn('  DRY RUN — no files will be changed');
        $this->newLine();

        $this->optimiseHeroSlides($quality, $dryRun);
        $this->optimiseLogo($dryRun);

        $this->newLine();
        $this->info('Done ✓');
        return self::SUCCESS;
    }

    // ─── HERO SLIDES ──────────────────────────────────────────────────────────
    // AVIF files are re-saved as highly compressed WebP.
    // WebP at q45 is ~40–50% smaller than AVIF at default quality,
    // and is supported by all modern browsers.
    // The <img> src paths don't change — we replace the file in-place
    // but change extension from .avif to .webp and update the DB references.
    private function optimiseHeroSlides(int $quality, bool $dryRun): void
    {
        $dir   = public_path($this->heroDir);
        $files = array_merge(
            File::glob("{$dir}/*.avif"),
            File::glob("{$dir}/*.webp"),
            File::glob("{$dir}/*.jpg"),
            File::glob("{$dir}/*.jpeg"),
            File::glob("{$dir}/*.png")
        );

        if (empty($files)) {
            $this->warn("No image files found in {$this->heroDir}");
            return;
        }

        $this->line("<fg=cyan>Hero slides — recompressing " . count($files) . " images to WebP at quality {$quality}</>");
        $this->newLine();

        $totalBefore = 0;
        $totalAfter  = 0;
        $renamed     = [];

        foreach ($files as $file) {
            $before    = filesize($file);
            $name      = basename($file);
            $ext       = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $noExt     = pathinfo($file, PATHINFO_FILENAME);
            $destFile  = $dir . '/' . $noExt . '.webp';
            $destName  = $noExt . '.webp';

            if ($dryRun) {
                $this->line("  [dry-run] {$name} (" . $this->kb($before) . ") → {$destName}");
                continue;
            }

            // Load image into GD
            $image = $this->loadImage($file, $ext);
            if (!$image) {
                $this->error("  ✗ Cannot load: {$name} (unsupported format or corrupt)");
                continue;
            }

            // Save as WebP to a temp file first
            $tmp = $destFile . '.tmp.webp';
            $ok  = imagewebp($image, $tmp, $quality);
            imagedestroy($image);

            if (!$ok || !file_exists($tmp)) {
                $this->error("  ✗ Failed to encode: {$name}");
                @unlink($tmp);
                continue;
            }

            $after = filesize($tmp);

            // Only replace if actually smaller
            if ($after < $before) {
                // If converting from non-webp, write to .webp destination
                rename($tmp, $destFile);

                // If original was .avif/.jpg/.png, delete it (we have .webp now)
                if ($ext !== 'webp' && file_exists($file)) {
                    unlink($file);
                    $renamed[$name] = $destName;
                }

                $totalBefore += $before;
                $totalAfter  += $after;
                $this->line(sprintf(
                    "  <fg=green>✓</> %-50s %s → %s <fg=green>(-%s)</>",
                    $name . ($ext !== 'webp' ? " → {$destName}" : ''),
                    $this->kb($before),
                    $this->kb($after),
                    $this->kb($before - $after)
                ));
            } else {
                @unlink($tmp);
                $totalBefore += $before;
                $totalAfter  += $before;
                $this->line("  <fg=yellow>–</> {$name} — already optimal, skipped");
            }
        }

        if (!$dryRun) {
            $this->newLine();
            $this->line(sprintf(
                '  Total saved: <fg=green>%s</> (%s → %s)',
                $this->kb($totalBefore - $totalAfter),
                $this->kb($totalBefore),
                $this->kb($totalAfter)
            ));

            // ── Update DB: if any .avif files were renamed to .webp ──────────
            if (!empty($renamed)) {
                $this->newLine();
                $this->line('<fg=cyan>Updating database references...</>');
                $updated = 0;
                foreach ($renamed as $oldName => $newName) {
                    // hero_slides table stores just the filename in the 'image' column
                    $rows = \DB::table('hero_slides')
                        ->where('image', $oldName)
                        ->update(['image' => $newName]);
                    if ($rows > 0) {
                        $this->line("  <fg=green>✓</> DB updated: {$oldName} → {$newName}");
                        $updated += $rows;
                    }
                }
                $updated === 0 && $this->warn('  No DB rows matched — check your hero_slides table column name');
            }
        }
    }

    // ─── LOGO ─────────────────────────────────────────────────────────────────
    private function optimiseLogo(bool $dryRun): void
    {
        $this->newLine();
        $this->line('<fg=cyan>Logo — generating responsive WebP sizes</>');
        $this->newLine();

        $src = public_path($this->logoSrc);

        if (!file_exists($src)) {
            $this->error("  Logo not found at: {$this->logoSrc}");
            return;
        }

        $before = filesize($src);
        $ext    = strtolower(pathinfo($src, PATHINFO_EXTENSION));
        $image  = $this->loadImage($src, $ext);

        if (!$image) {
            $this->error("  ✗ Cannot load logo");
            return;
        }

        // Keep original for 2x generation before we overwrite 1x
        $origW = imagesx($image);
        $origH = imagesy($image);

        if ($dryRun) {
            $this->line("  [dry-run] Would resize logo {$origW}×{$origH} → 440×152 (1×) and 770×266 (2×)");
            imagedestroy($image);
            return;
        }

        // ── Generate 2× first (770×266) before overwriting source ─────────────
        $logo2x = $this->resizeImage($image, 770, 266);
        $path2x = public_path($this->logo2x);
        imagewebp($logo2x, $path2x, 85);
        imagedestroy($logo2x);
        $this->line(sprintf('  <fg=green>✓</> logo 2× (770×266) → %s', $this->kb(filesize($path2x))));

        // ── Generate 1× (440×152) ──────────────────────────────────────────────
        $logo1x = $this->resizeImage($image, 440, 152);
        $path1x = public_path($this->logo1x);
        imagewebp($logo1x, $path1x, 80);
        imagedestroy($logo1x);
        imagedestroy($image);
        $this->line(sprintf('  <fg=green>✓</> logo 1× (440×152) → %s', $this->kb(filesize($path1x))));

        $after = filesize($path1x);
        $this->newLine();
        $this->line(sprintf(
            '  Logo saved: <fg=green>%s</> (%s → %s)',
            $this->kb($before - $after),
            $this->kb($before),
            $this->kb($after)
        ));
    }

    // ─── HELPERS ──────────────────────────────────────────────────────────────

    private function loadImage(string $path, string $ext): \GdImage|false
    {
        return match($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png'         => @imagecreatefrompng($path),
            'webp'        => @imagecreatefromwebp($path),
            'avif'        => function_exists('imagecreatefromavif')
                                ? @imagecreatefromavif($path)
                                : $this->loadAvifViaImagick($path),
            'gif'         => @imagecreatefromgif($path),
            default       => false,
        };
    }

    // Fallback: use Imagick to load AVIF and convert to GD resource
    private function loadAvifViaImagick(string $path): \GdImage|false
    {
        if (!extension_loaded('imagick')) {
            $this->warn("    PHP GD cannot read AVIF and Imagick is not available.");
            $this->warn("    Skipping {$path}");
            return false;
        }

        try {
            $imagick = new \Imagick($path);
            $imagick->setImageFormat('png');
            $blob = $imagick->getImageBlob();
            $imagick->destroy();

            $tmp = sys_get_temp_dir() . '/avif_convert_' . uniqid() . '.png';
            file_put_contents($tmp, $blob);
            $gd = @imagecreatefrompng($tmp);
            @unlink($tmp);
            return $gd;
        } catch (\Exception $e) {
            $this->error("    Imagick error: " . $e->getMessage());
            return false;
        }
    }

    private function resizeImage(\GdImage $src, int $width, int $height): \GdImage
    {
        $dest = imagecreatetruecolor($width, $height);

        // Preserve transparency for WebP/PNG
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
        imagefilledrectangle($dest, 0, 0, $width, $height, $transparent);
        imagealphablending($dest, true);

        imagecopyresampled(
            $dest, $src,
            0, 0, 0, 0,
            $width, $height,
            imagesx($src), imagesy($src)
        );

        return $dest;
    }

    private function getDriver(): string
    {
        if (extension_loaded('imagick')) return 'GD + Imagick';
        if (extension_loaded('gd'))     return 'GD';
        return 'unknown';
    }

    private function checkDependencies(): void
    {
        if (!extension_loaded('gd')) {
            $this->error('PHP GD extension is not installed or enabled.');
            $this->line('Enable it in php.ini: extension=gd');
            exit(1);
        }

        if (!function_exists('imagewebp')) {
            $this->error('PHP GD is installed but WebP support is missing.');
            $this->line('Recompile PHP with --with-webp or upgrade to PHP 8+');
            exit(1);
        }

        $this->line('<fg=green>✓</> PHP GD with WebP support detected — no external tools needed');
    }

    private function kb(int $bytes): string
    {
        return round($bytes / 1024, 1) . ' KiB';
    }
}
