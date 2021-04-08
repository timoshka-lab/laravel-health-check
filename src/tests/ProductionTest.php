<?php

namespace HealthCheckTests;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

final class ProductionTest extends TestCase
{
    /**
     * @return void
     */
    public function testBasicEnvironments()
    {
        $this->assertStringNotContainsString('Laravel', env('APP_NAME'), 'The application name is same as default configuration.');
        $this->assertEquals('production', env('APP_ENV'), 'The application might be driven with non production environment.');
        $this->assertNotNull(env('APP_KEY'), 'You have to set application key.');
        $this->assertFalse(env('APP_DEBUG'), 'Debug mode is enabled.');
        $this->assertStringNotContainsString('localhost', env('APP_URL'), 'The application url is same as default configuration.');
        $this->assertStringEndsNotWith('.test', env('APP_URL'), 'The application might be driven with .test domain.');
    }

    /**
     * @return void
     */
    public function testDatabaseEnvironments()
    {
        if (HC_TEST_WITHOUT_DATABASE) return;

        $this->testRequiredEnvironment('DB_CONNECTION', 'database driver');
        $this->assertArrayHasKey(env('DB_CONNECTION'), config('database.connections'), 'The database driver was not found in your configuration.');

        $this->testRequiredEnvironment('DB_HOST', 'database host name');
        $this->assertIsNumeric(env('DB_PORT'), 'The database port number is not in numeric format.');

        $this->testRequiredEnvironment('DB_DATABASE', 'database name');
        $this->assertNotEquals('laravel', env('DB_DATABASE'), 'The database name is the same as default configuration.');
        $this->assertStringNotContainsStringIgnoringCase('develop', env('DB_DATABASE'), 'The database name including develop keyword.');
        $this->assertStringNotContainsStringIgnoringCase('test', env('DB_DATABASE'), 'The database name including test keyword.');

        $this->testRequiredEnvironment('DB_USERNAME', 'database user name');
        $this->assertNotEquals('root', env('DB_USERNAME'), 'It\'s danger to run database with root user.');
        $this->assertStringNotContainsStringIgnoringCase('develop', env('DB_USERNAME'), 'The database user name including develop keyword.');
        $this->assertStringNotContainsStringIgnoringCase('test', env('DB_USERNAME'), 'The database user name including test keyword.');

        $this->testRequiredEnvironment('DB_PASSWORD', 'database password');
        // TODO: パスワードの強度を検証すべき？

        try {
            DB::connection()->getPdo();
        } catch (\PDOException $exception) {
            $this->fail('The database connection test end in failure.');
        }
    }

    /**
     * @return void
     */
    public function testEmailEnvironments()
    {
        if (HC_TEST_WITHOUT_MAIL) return;

        $this->testRequiredEnvironment('MAIL_MAILER', 'mail driver');
        $this->testRequiredEnvironment('MAIL_HOST', 'mail host');
        $this->assertNotEquals('smtp.mailtrap.io', env('MAIL_HOST'), 'The email host might to be fake smtp server.');
        $this->testRequiredEnvironment('MAIL_PORT', 'mail port');
        $this->assertIsNumeric(env('MAIL_PORT'), 'Mail port number is not in numeric format.');
        $this->testRequiredEnvironment('MAIL_USERNAME', 'mail username');
        $this->testRequiredEnvironment('MAIL_PASSWORD', 'mail password');
        $this->testRequiredEnvironment('MAIL_FROM_ADDRESS', 'mail port');
        $this->assertTrue(filter_var(env('MAIL_FROM_ADDRESS'), FILTER_VALIDATE_EMAIL));
        $this->assertStringNotContainsStringIgnoringCase('test', env('MAIL_FROM_ADDRESS'), 'The email from address including test keyword.');
        $this->assertStringNotContainsStringIgnoringCase('develop', env('MAIL_FROM_ADDRESS'), 'The email from address including test keyword.');

        try {
            $mailer = Container::getInstance()->make('mailer')->getSwiftMailer();
            $mailer->getTransport()->start();
        } catch (\Exception $exception) {
            $this->fail('Mail connection test end in failure.');
        }
    }

    /**
     * @return void
     */
    public function testQueueEnvironments()
    {
        if (HC_TEST_WITHOUT_QUEUE) return;

        $this->testRequiredEnvironment('QUEUE_CONNECTION', 'queue driver');
        $this->assertArrayHasKey(env('QUEUE_CONNECTION'), config('queue.connections'), 'The queue driver was not found in your configuration.');
        $this->assertNotEquals('sync', env('QUEUE_CONNECTION'), 'The queue driver is defined as sync.');

        try {
            Queue::size();
        } catch (\Exception $exception) {
            $this->fail('Queue connection test end in failure.');
        }
    }

    /**
     * @param string $key
     * @param string|null $trans
     */
    private function testRequiredEnvironment(string $key, string $trans = null)
    {
        $trans = $trans ?: $key . ' value';
        $this->assertNotEmpty(env($key), sprintf('The %s is null or empty.', $trans));
    }
}