<?php

namespace App\Console\Commands;

use App\Services\Operations\HostingReadinessService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('hosting:verificar {--strict : Considera los avisos como errores}')]
#[Description('Verifica que la instalación cumpla los requisitos operativos para HostGator/cPanel.')]
class VerificarHostingCommand extends Command
{
    public function handle(HostingReadinessService $readiness): int
    {
        $results = $readiness->inspect();
        $labels = [
            HostingReadinessService::OK => 'OK',
            HostingReadinessService::WARNING => 'AVISO',
            HostingReadinessService::ERROR => 'ERROR',
        ];

        $this->table(
            ['Estado', 'Verificación', 'Detalle'],
            array_map(fn (array $result): array => [
                $labels[$result['status']],
                $result['check'],
                $result['detail'],
            ], $results),
        );

        $errors = count(array_filter($results, fn (array $result): bool => $result['status'] === HostingReadinessService::ERROR));
        $warnings = count(array_filter($results, fn (array $result): bool => $result['status'] === HostingReadinessService::WARNING));

        $this->newLine();
        $this->line("Resultado: {$errors} errores y {$warnings} avisos.");

        return $errors > 0 || ($this->option('strict') && $warnings > 0)
            ? self::FAILURE
            : self::SUCCESS;
    }
}
