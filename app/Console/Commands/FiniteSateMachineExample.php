<?php

namespace App\Console\Commands;

use App\Contracts\FiniteStateMachineBuilderServiceInterface;
use App\Contracts\FiniteStateMachineProcessorServiceInterface;
use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\Output;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalFsmBuilderFinalStateException;
use App\Exceptions\IllegalFsmBuilderInitialStateException;
use App\Exceptions\IllegalFsmBuilderInputException;
use App\Exceptions\IllegalFsmBuilderStateException;
use App\Exceptions\IllegalFsmBuilderTransitionException;
use App\Exceptions\IllegalFsmProcessorCurrentStateException;
use App\Exceptions\IllegalFsmProcessorFinalStateException;
use App\Exceptions\IllegalFsmProcessorInitialStateException;
use App\Exceptions\IllegalFsmProcessorInputException;
use App\Exceptions\IllegalFsmProcessorTransitionException;
use Illuminate\Console\Command;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class FiniteSateMachineExample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:finite-sate-machine-example';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print out modulo three examples';


    private FiniteStateMachineBuilderServiceInterface $builderService;
    private FiniteStateMachineProcessorServiceInterface $processorService;

    public function __construct(FiniteStateMachineBuilderServiceInterface $builderService,
                                FiniteStateMachineProcessorServiceInterface $processorService)
    {
        parent::__construct();
        $this->builderService = $builderService;
        $this->processorService = $processorService;
    }

    /**
     * Execute the console command.
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws IllegalFsmProcessorCurrentStateException
     * @throws IllegalFsmProcessorFinalStateException
     * @throws IllegalFsmProcessorInitialStateException
     * @throws IllegalFsmProcessorInputException
     * @throws IllegalFsmProcessorTransitionException
     * @throws UnknownProperties
     */
    public function handle(): void
    {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);
        $state2 = new FinalState(['name' => 'S2', 'output' => new Output(['value' => '2'])]);

        $input0 = new Input(['value' => '0']);
        $input1 = new Input(['value' => '1']);

        $transitions = collect([
            new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]),
            new Transition(['fromState' => $state0, 'input' => $input1, 'toState' => $state1,]),
            new Transition(['fromState' => $state1, 'input' => $input0, 'toState' => $state2,]),
            new Transition(['fromState' => $state1, 'input' => $input1, 'toState' => $state0,]),
            new Transition(['fromState' => $state2, 'input' => $input0, 'toState' => $state1,]),
            new Transition(['fromState' => $state2, 'input' => $input1, 'toState' => $state2,]),
        ]);

        $finiteStateMachine = $this->builderService->create(
            collect([$state0, $state1, $state2]),
            collect([$input0, $input1]),
            $state0,
            collect([$state0, $state1, $state2]),
            $transitions,
        );

        echo "Example 1 - 110\n";
        $inputs = collect([$input1, $input1, $input0]);
        $finalState = $this->processorService->processList($finiteStateMachine, $state0, $inputs);

        echo 'Result: ';
        echo $finalState->output->value. "\n\n";


        echo "Example 2 - 1010\n";
        $inputs = collect([$input1, $input0, $input1, $input0]);
        $finalState = $this->processorService->processList($finiteStateMachine, $state0, $inputs);

        echo 'Result: ';
        echo $finalState->output->value. "\n\n";

        echo "Example 3 - 1101\n";
        $inputs = collect([$input1, $input1, $input0, $input1]);
        $finalState = $this->processorService->processList($finiteStateMachine, $state0, $inputs);

        echo 'Result: ';
        echo $finalState->output->value. "\n\n";


        echo "Example 4 - 1110\n";
        $inputs = collect([$input1, $input1, $input1, $input0]);
        $finalState = $this->processorService->processList($finiteStateMachine, $state0, $inputs);

        echo 'Result: ';
        echo $finalState->output->value. "\n\n";

        echo "Example 5 - 1111\n";
        $inputs = collect([$input1, $input1, $input1, $input1]);
        $finalState = $this->processorService->processList($finiteStateMachine, $state0, $inputs);

        echo 'Result: ';
        echo $finalState->output->value. "\n\n";
    }
}
