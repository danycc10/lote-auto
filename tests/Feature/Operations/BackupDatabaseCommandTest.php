<?php

namespace Tests\Feature\Operations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BackupDatabaseCommandTest extends TestCase
{
    private string $basePath;

    private string $backupPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basePath = storage_path('framework/testing/backup-source.sqlite');
        $this->backupPath = storage_path('framework/testing/backups');
        File::delete($this->basePath);
        File::deleteDirectory($this->backupPath);
        File::put($this->basePath, '');

        config([
            'database.default' => 'backup_test',
            'database.connections.backup_test' => [
                'driver' => 'sqlite',
                'database' => $this->basePath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'backup.directory' => $this->backupPath,
        ]);
        DB::purge();

        Schema::create('control_backup', function (Blueprint $table): void {
            $table->id();
            $table->string('valor');
        });
        DB::table('control_backup')->insert(['valor' => 'dato-verificable']);
    }

    protected function tearDown(): void
    {
        DB::disconnect();
        File::delete($this->basePath);
        File::deleteDirectory($this->backupPath);

        parent::tearDown();
    }

    public function test_crea_respaldo_sqlite_verificable_con_hash(): void
    {
        $this->artisan('app:backup-database', ['--keep' => 14])
            ->assertSuccessful();

        $respaldos = File::glob($this->backupPath.DIRECTORY_SEPARATOR.'*.sqlite');

        $this->assertCount(1, $respaldos);
        $this->assertFileExists($respaldos[0].'.sha256');

        config([
            'database.connections.backup_verify' => [
                'driver' => 'sqlite',
                'database' => $respaldos[0],
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        $this->assertSame(
            'dato-verificable',
            DB::connection('backup_verify')->table('control_backup')->value('valor'),
        );
    }

    public function test_elimina_archivos_que_superan_la_retencion(): void
    {
        File::ensureDirectoryExists($this->backupPath);
        $vencido = $this->backupPath.DIRECTORY_SEPARATOR.'database-vencida.sqlite';
        File::put($vencido, 'obsoleto');
        touch($vencido, now()->subDays(2)->getTimestamp());

        $this->artisan('app:backup-database', ['--keep' => 1])
            ->assertSuccessful();

        $this->assertFileDoesNotExist($vencido);
    }
}
