<?php

namespace Tests\Commands;

use Tests\TestCase;

class MakeModelCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('beyond:make:module User');
    }

    public function testCanMakeModel(): void
    {
        $this->artisan('beyond:make:model User.User');

        $file = beyond_modules_path('User/Domain/Models/User.php');
        $contents = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertStringNotContainsString('{{ namespace }}', $contents);
        $this->assertStringNotContainsString('{{ className }}', $contents);
    }

    public function testCanMakeModelWithFactory(): void
    {
        $this->artisan('beyond:make:model User.User --factory');

        $file = beyond_modules_path('User/Domain/Models/User.php');
        $contents = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertStringNotContainsString('{{ namespace }}', $contents);
        $this->assertStringNotContainsString('{{ className }}', $contents);

        $file = beyond_modules_path('User/Infrastructure/factories/UserFactory.php');
        $contents = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertStringNotContainsString('{{ namespace }}', $contents);
        $this->assertStringNotContainsString('{{ model }}', $contents);
    }

    public function testCanMakeModelWithMigration(): void
    {
        $this->artisan('beyond:make:model User.User --migration');

        $now = new \DateTime();

        $file = beyond_modules_path('User/Domain/Models/User.php');
        $contents = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertStringNotContainsString('{{ namespace }}', $contents);
        $this->assertStringNotContainsString('{{ className }}', $contents);

        $file = beyond_modules_path('User/Infrastructure/Database/migrations/'.$now->format('Y_m_d_His').'_create_users_table.php');
        $contents = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertStringNotContainsString('{{ table }}', $contents);
        $this->assertStringNotContainsString('Schema::', $contents);
    }
}
