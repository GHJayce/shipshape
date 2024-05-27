<?php

require 'vendor/autoload.php';

use Ghjayce\Shipshape\Shipshape;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Context;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;
use GhjayceExample\Shipshape\Cases\BrushTeeth\BrushTeeth;
use GhjayceExample\Shipshape\Mock\Container;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Enum;
use Ghjayce\Shipshape\Entity\Config\{
    ShipshapeConfig,
    ShipshapeHookConfig,
};
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\{
    TakeTheCup,
    CleanTheCup,
    FillCupWithWater,
    SqueezingTheTube,
    BrushTeething,
};

class Main
{
    public function __construct(protected Shipshape $shipshape, protected $container)
    {
        $this->shipshape = new Shipshape();
    }

    public function caseA(): mixed
    {
        return $this->shipshape->execute(
            ShipshapeConfig::make()
                ->setClass(BrushTeeth::class)
                ->setNames(Enum::NAMES)
                ->build(),
            ShipshapeContext::make()->setClientContext(Context::make())
        );
    }

    public function caseB(): mixed
    {
        return $this->shipshape->execute(
            ShipshapeConfig::make()
                ->setNamespace('\\GhjayceExample\\Shipshape\\Cases\\BrushTeeth\\Action\\')
                ->setNames(Enum::NAMES)
                ->build(),
            ShipshapeContext::make()->setClientContext(Context::make())
        );
    }

    public function caseC(): mixed
    {
        return $this->shipshape->execute(
            ShipshapeHookConfig::make()
                ->setContainer($this->container)
                ->setActions([
                    TakeTheCup::class,
                    CleanTheCup::class,
                    FillCupWithWater::class,
                    SqueezingTheTube::class,
                    BrushTeething::class,
                ])
                ->build(),
            ShipshapeContext::make()->setClientContext(Context::make())
        );
    }
}


$shipshape = new Shipshape();
$container = new Container();
$main = new Main($shipshape, $container);
var_dump('Case A: ', $main->caseA());
var_dump('Case B: ', $main->caseB());
var_dump('Case C: ', $main->caseC());