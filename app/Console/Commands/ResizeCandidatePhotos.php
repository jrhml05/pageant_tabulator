<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Makes the small copies of candidate photos that the judge and admin pages load.
 * The originals (about 2 MB, 1290x2048) stay untouched; copies go to img/{mr,ms}/web/.
 */
class ResizeCandidatePhotos extends Command
{
    protected $signature = 'candidates:photos
                            {--width=640 : Width of the copies in pixels}
                            {--force : Rebuild copies that are already up to date}';

    protected $description = 'Create web-sized copies of candidate photos for faster loading over the LAN';

    public function handle(): int
    {
        if (! function_exists('imagecreatefromjpeg')) {
            $this->error('The PHP GD extension (with JPEG support) is required. Enable it in php.ini and run this again.');

            return self::FAILURE;
        }

        $width = max(200, (int) $this->option('width'));
        $made = $skipped = 0;

        foreach (['mr', 'ms'] as $division) {
            $dir = public_path("assets/img/{$division}");
            if (! is_dir("{$dir}/web")) {
                mkdir("{$dir}/web", 0755, true);
            }

            foreach (glob("{$dir}/*.jpg") as $original) {
                $copy = "{$dir}/web/".basename($original);

                if (! $this->option('force') && is_file($copy) && filemtime($copy) >= filemtime($original)) {
                    $skipped++;

                    continue;
                }

                $source = @imagecreatefromjpeg($original);
                if (! $source) {
                    $this->warn("Skipped {$division}/".basename($original).': not a readable JPEG.');

                    continue;
                }

                $resized = imagescale($source, min($width, imagesx($source)), -1, IMG_BICUBIC);
                imageinterlace($resized, true);
                imagejpeg($resized, $copy, 80);
                imagedestroy($source);
                imagedestroy($resized);

                $this->line(sprintf('  %s/%s  %s → %s', $division, basename($original), $this->kb($original), $this->kb($copy)));
                $made++;
            }
        }

        $this->info("Photos resized: {$made}. Already up to date: {$skipped}.");

        return self::SUCCESS;
    }

    private function kb(string $file): string
    {
        return round(filesize($file) / 1024).' KB';
    }
}
