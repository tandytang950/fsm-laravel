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
class FinalStateTest extends TestCase
{
    /**
     * @throws UnknownProperties
     */
    public function testConstructorSuccessfully(): void
    {
        $name = 'S0';
        $output = new Output(['value' => '1']);

        $finalState = new FinalState([
            'name' => $name,
            'output' => $output
        ]);

        $this->assertEquals($name, $finalState->name);
        $this->assertTrue($finalState->output->equals($output));
    }

    /**
     * @throws UnknownProperties
     */
    public function testConstructorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        new FinalState([
            'unknown' => null,
        ]);
    }

    /**
     * @throws UnknownProperties
     */
    public function testEqualsWithAnother() {
        $s1 = new FinalState(['name' => 'final state test', 'output' => new Output(['value' => '1'])]);
        $s2 = new FinalState(['name' => 'final state test', 'output' => new Output(['value' => '1'])]);
        $this->assertTrue($s1->equals($s2));
        $this->assertTrue($s2->equals($s1));

        $s1 = new FinalState(['name' => 'final state test 1', 'output' => new Output(['value' => '1'])]);
        $s2 = new FinalState(['name' => 'final state test 2', 'output' => new Output(['value' => '1'])]);
        $this->assertFalse($s1->equals($s2));
        $this->assertFalse($s2->equals($s1));

        $s1 = new FinalState(['name' => 'final state test', 'output' => new Output(['value' => '1'])]);
        $s2 = new FinalState(['name' => 'final state test', 'output' => new Output(['value' => '2'])]);
        $this->assertFalse($s1->equals($s2));
        $this->assertFalse($s2->equals($s1));

        $s1 = new State(['name' => 'state test']);
        $s2 = new FinalState(['name' => 'state test', 'output' => new Output(['value' => '1'])]);
        $this->assertFalse($s1->equals($s2));
        $this->assertFalse($s2->equals($s1));
    }
}
