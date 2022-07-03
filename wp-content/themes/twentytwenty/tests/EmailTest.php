<?php declare(strict_types=1);
use TwentyTwenty\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test(): void
    {
        $email = new Email('pg@');
        $this->assertInstanceOf(
            Email::class,
            $email
        );
    }

    public function test1(): void
    {
        $email = new Email('pg@localhost.com');
        $this->assertEquals(
            'pg@localhost.com',
            $email->getEmail()
        );
    }
}