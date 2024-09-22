<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command;

use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;
use Ghjayce\Shipshape\Shipshape;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    protected Shipshape $shipshape;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->shipshape = new Shipshape();
    }

    abstract public function handle(InputInterface $input, OutputInterface $output): void;

    public function execute(InputInterface $input, OutputInterface $output): int
    {
//        try {
            $this->handle($input, $output);
//        } catch (\Throwable $exception) {
//            $output->writeln($exception->getMessage());
//        }
        return 0;
    }


    /**
     * @param ShipshapeConfig $config
     * @param ClientContext $clientContext
     * @return mixed
     */
    protected function shipshapeExecute(ShipshapeConfig $config, ClientContext $clientContext): mixed
    {
        $method = __METHOD__;
        $config->setAfterHandleHook(function (ShipshapeConfig $config, ShipshapeContext $context) use ($method) {
            //var_dump([$method, $context->getActionName()]);
        });
        return $this->shipshape->execute($config->build(), ShipshapeContext::make()->setClientContext($clientContext));
    }
}
