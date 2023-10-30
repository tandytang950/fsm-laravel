<?php

namespace Tests\Unit\Dtos\FiniteStateMachine;

use App\Dtos\FiniteStateMachine\Output;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

/**
 * @group fsm
 */
class OutputTest extends TestCase
{
    /**
     * @throws UnknownProperties
     */
    public function testConstructorSuccessfully(): void
    {
        $value = '1';

        $output = new Output([
            'value' => $value,
        ]);

        $this->assertEquals($value, $output->value);

    }

    /**
     * @throws UnknownProperties
     */
    public function testConstructorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        new Output([
            'unknown' => null,
        ]);
    }

    /**
     * @throws UnknownProperties
     */
    public function testEqualsWithAnother() {
        $output1 = new Output(['value' => '1']);
        $output2 = new Output(['value' => '1']);
        $this->assertTrue($output1->equals($output2));
        $this->assertTrue($output2->equals($output1));

        $output1 = new Output(['value' => '1']);
        $output2 = new Output(['value' => '2']);
        $this->assertFalse($output1->equals($output2));
        $this->assertFalse($output2->equals($output1));
    }
}
