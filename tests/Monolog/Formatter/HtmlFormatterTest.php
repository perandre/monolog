<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Formatter;

use Monolog\Level;

/**
 * @covers Monolog\Formatter\HtmlFormatter
 */
class HtmlFormatterTest extends \Monolog\Test\MonologTestCase
{
    public function testFormat()
    {
        $formatter = new HtmlFormatter();
        $record = $this->getRecord(Level::Warning, 'test message', channel: 'test');
        $output = $formatter->format($record);

        $this->assertStringContainsString('<h1', $output);
        $this->assertStringContainsString('WARNING', $output);
        $this->assertStringContainsString('test message', $output);
        $this->assertStringContainsString('test', $output);
        $this->assertStringContainsString('<table', $output);
    }

    public function testFormatWithContext()
    {
        $formatter = new HtmlFormatter();
        $record = $this->getRecord(Level::Error, 'test', context: ['foo' => 'bar']);
        $output = $formatter->format($record);

        $this->assertStringContainsString('Context', $output);
        $this->assertStringContainsString('foo', $output);
    }

    public function testFormatWithExtra()
    {
        $formatter = new HtmlFormatter();
        $record = $this->getRecord(Level::Info, 'test', extra: ['ip' => '127.0.0.1']);
        $output = $formatter->format($record);

        $this->assertStringContainsString('Extra', $output);
        $this->assertStringContainsString('ip', $output);
        $this->assertStringContainsString('127.0.0.1', $output);
    }

    public function testFormatBatch()
    {
        $formatter = new HtmlFormatter();
        $records = [
            $this->getRecord(Level::Warning, 'first'),
            $this->getRecord(Level::Error, 'second'),
        ];
        $output = $formatter->formatBatch($records);

        $this->assertStringContainsString('first', $output);
        $this->assertStringContainsString('second', $output);
        $this->assertSame(2, substr_count($output, '<h1'));
    }

    public function testLevelColors()
    {
        $formatter = new HtmlFormatter();

        $debugOutput = $formatter->format($this->getRecord(Level::Debug, 'debug'));
        $this->assertStringContainsString('#CCCCCC', $debugOutput);

        $errorOutput = $formatter->format($this->getRecord(Level::Error, 'error'));
        $this->assertStringContainsString('#FD7E14', $errorOutput);

        $emergencyOutput = $formatter->format($this->getRecord(Level::Emergency, 'emergency'));
        $this->assertStringContainsString('#000000', $emergencyOutput);
    }

    public function testHtmlEscaping()
    {
        $formatter = new HtmlFormatter();
        $record = $this->getRecord(Level::Warning, '<script>alert("xss")</script>');
        $output = $formatter->format($record);

        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringContainsString('&lt;script&gt;', $output);
    }

    public function testCustomDateFormat()
    {
        $formatter = new HtmlFormatter('Y/m/d');
        $record = $this->getRecord(Level::Info, 'test');
        $output = $formatter->format($record);

        $this->assertStringContainsString(date('Y/m/d'), $output);
    }

    public function testEmptyContextAndExtraOmitted()
    {
        $formatter = new HtmlFormatter();
        $record = $this->getRecord(Level::Info, 'test');
        $output = $formatter->format($record);

        $this->assertStringNotContainsString('Context', $output);
        $this->assertStringNotContainsString('Extra', $output);
    }
}
