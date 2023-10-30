<?php

namespace Tests\Unit\Dtos\FiniteStateMachine;

use App\Dtos\FiniteStateMachine\Input;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

/**
 * @group fsm
 */
class InputTest extends TestCase
{
    /**
     * @throws UnknownProperties
     */
    public function testConstructorSuccessfully(): void
    {
        $value = '1';

        $input = new Input([
            'value' => $value,
        ]);

        $this->assertEquals($value, $input->value);

    }

    /**
     * @throws UnknownProperties
     */
    public function testConstructorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        new Input([
            'unknown' => null,
        ]);
    }

    /**
     * @throws UnknownProperties
     */
    public function testEqualsWithAnother() {
        $input1 = new Input(['value' => '1']);
        $input2 = new Input(['value' => '1']);
        $this->assertTrue($input1->equals($input2));
        $this->assertTrue($input2->equals($input1));

        $input1 = new Input(['value' => '1']);
        $input2 = new Input(['value' => '2']);
        $this->assertFalse($input1->equals($input2));
        $this->assertFalse($input2->equals($input1));
    }
}
