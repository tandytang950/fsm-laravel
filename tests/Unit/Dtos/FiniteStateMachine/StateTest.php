<?php

namespace Tests\Unit\Dtos\FiniteStateMachine;

use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Output;
use App\Dtos\FiniteStateMachine\State;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

/**
 * @group fsm
 */
class StateTest extends TestCase
{
    /**
     * @throws UnknownProperties
     */
    public function testConstructorSuccessfully(): void
    {
        $name = 'S0';

        $state = new State([
            'name' => $name,
        ]);

        $this->assertEquals($name, $state->name);

    }

    /**
     * @throws UnknownProperties
     */
    public function testConstructorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        new State([
            'unknown' => null,
        ]);
    }

    /**
     * @throws UnknownProperties
     */
    public function testEqualsWithAnother() {
        $s1 = new State(['name' => 'state test']);
        $s2 = new State(['name' => 'state test']);
        $this->assertTrue($s1->equals($s2));
        $this->assertTrue($s2->equals($s1));

        $s1 = new State(['name' => 'state test 1']);
        $s2 = new State(['name' => 'state test 2']);
        $this->assertFalse($s1->equals($s2));
        $this->assertFalse($s2->equals($s1));

        $s1 = new State(['name' => 'state test']);
        $s2 = new FinalState(['name' => 'state test', 'output' => new Output(['value' => '1'])]);
        $this->assertFalse($s1->equals($s2));
        $this->assertFalse($s2->equals($s1));
    }
}
