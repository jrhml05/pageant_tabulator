<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Starts the tabulator for judges' tablets on the venue WLAN, with the setup that keeps it fast offline:
 * built assets (no Vite dev server), small candidate photos, and several PHP workers so one judge's
 * request doesn't queue behind another's.
 */
class ServeForEvent extends Command
{
    protected $signature = 'event:serve
                            {--port=80 : Port the tablets connect to}
                            {--workers=4 : PHP worker processes (ignored on Windows, where PHP runs one)}';

    protected $description = 'Serve the tabulator to tablets on the local network';

    public function handle(): int
    {
        if (! is_file(public_path('build/manifest.json'))) {
            $this->error('Assets are not built. Run `npm run build` first.');

            return self::FAILURE;
        }

        // Left behind by `npm run dev`: it points pages at a dev server the tablets can't reach.
        if (is_file(public_path('hot'))) {
            unlink(public_path('hot'));
            $this->warn('Removed public/hot left by `npm run dev`; pages now use the built assets.');
        }

        if (config('app.debug')) {
            $this->warn('APP_DEBUG is on. For the event, set APP_ENV=production and APP_DEBUG=false in .env, then run `php artisan optimize`.');
        }

        $this->call('candidates:photos');

        $port = (int) $this->option('port');
        $this->newLine();
        $this->info('Tablets on this network can open:');
        foreach ($this->lanAddresses() as $address) {
            $this->line('  http://'.$address.($port === 80 ? '' : ":{$port}"));
        }
        $this->newLine();

        // `serve` reads this when it starts the PHP built-in server.
        $workers = (string) max(1, (int) $this->option('workers'));
        putenv("PHP_CLI_SERVER_WORKERS={$workers}");
        $_ENV['PHP_CLI_SERVER_WORKERS'] = $_SERVER['PHP_CLI_SERVER_WORKERS'] = $workers;

        return $this->call('serve', ['--host' => '0.0.0.0', '--port' => $port, '--no-reload' => true]);
    }

    /** IPv4 addresses of this machine's network interfaces, skipping loopback. */
    private function lanAddresses(): array
    {
        $addresses = [];
        foreach (function_exists('net_get_interfaces') ? net_get_interfaces() : [] as $interface) {
            foreach ($interface['unicast'] ?? [] as $unicast) {
                $ip = $unicast['address'] ?? null;
                if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && ! str_starts_with($ip, '127.') && ! str_starts_with($ip, '169.254.')) {
                    $addresses[] = $ip;
                }
            }
        }

        return $addresses ?: [gethostbyname(gethostname())];
    }
}
