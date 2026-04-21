# Usage

Take cooking as an example. In code, this represents a functional module to be implemented (here referring to the `cooking` method).

```php
<?php

function cooking(): mixed
{
    // Step 1: Fetch ingredients
    // Step 2: Wash ingredients
    // Step 3: Chop ingredients
    // Step 4: Marinate ingredients
    // Step 5: Season
    // Step 6: Cook ingredients
    // Step 7: Plate the dish
    // Step 8: Return result
    return;
}
```

As the complexity of the functional module increases, the process steps also become more complex, making the code bloated and difficult to maintain or extend.

Shipshape was created to solve this problem.

## Basics

With Shipshape, the above code can be refactored as follows:

```php
<?php

use Ghjayce\Shipshape\Shipshape;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Action\Action;

function cooking(): mixed
{
    $config = ActionConfig::make()
        ->setActions([
            // Step 1: Fetch ingredients
            FetchIngredient::class,
            // Step 2: Wash ingredients
            WashIngredient::class,
            // Step 3: Chop ingredients
            ChopIngredient::class,
            // Step 4: Marinate ingredients
            MarinateIngredient::class,
            // Step 5: Season
            SeasonDish::class,
            // Step 6: Cook ingredients
            CookIngredient::class,
            // Step 7: Plate the dish
            PlateDish::class,
            // Step 8: Return result
            TheEnd::class,
        ]);
    $context = Context::make();
    $executeContext = ExecuteContext::make()
        ->setConfig($config)
        ->setClientContext($context);
    return (new Shipshape())->execute($executeContext);
}

class Context extends ClientContext
{
    public array $ingredients = [];
}

// For demonstration, only FetchIngredient is shown here. Other actions follow the same pattern.
class FetchIngredient extends Action
{
    /**
     * @param Context $context
     * @param ExecuteContext $executeContext
     * @return void
     */
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
        // do something...
        $context->ingredients = ['beef', 'lemon'];
    }
}
```

### Concepts

The `cooking()` function module will execute steps 1 through 8 in order, and finally exit and return the result at step 8.

- `action`: Each step is an action, such as `FetchIngredient`, `CookIngredient`, etc. You can freely arrange the order, add new actions, or replace actions.
- `ActionConfig`: The class used to provide action configuration.
- `Context`: A context class dedicated to the functional module, used to store variables shared between actions.
- `ExecuteContext`: The context class used internally by Shipshape.

## Advanced

> The following is just an example of usage. The actual implementation can be adapted as needed.

After abstracting the cooking module, there are generally 5 fixed steps:

- Step 1: Prepare ingredients, e.g., by purchasing, taking from the fridge, or digging from the ground.
- Step 2: Handle ingredients, e.g., washing, chopping, marinating, seasoning, etc.
- Step 3: Cook ingredients, e.g., stir-frying, stewing, steaming, deep-frying, etc.
- Step 4: Plate the dish, e.g., plating directly, arranging, or dividing into portions.
- Step 5: Return the result.

```php
<?php

use Phparm\Entity\Attribute;
use Ghjayce\Shipshape\Shipshape;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Action\Action;

function cooking(CookingParam $param): mixed
{
    $config = ActionConfig::make()
        ->setActions([
            PrepareIngredient::class,
            HandleIngredient::class,
            CookIngredient::class,
            Plate::class,
            TheEnd::class,
        ]);
    $context = Context::make()
        ->setParam($param)
        ->setService(new Service);
    $executeContext = ExecuteContext::make()
        ->setConfig($config)
        ->setClientContext($context);
    return (new Shipshape())->execute($executeContext);
}

class CookingParam extends Attribute
{
    public string $dishName;
}
class Context extends ClientContext
{
    public array $ingredients = [];
    public CookingParam $param;
    public ServiceInterface $service;
}
interface ServiceInterface
{
    public function prepare();
    public function handle();
    public function cook();
    public function plate();
}
class Service implements ServiceInterface
{
    public function prepare() {}
    public function handle() {}
    public function cook() {}
    public function plate() {}
}

// For demonstration, only PrepareIngredient is shown here. Other actions follow the same pattern.
class PrepareIngredient extends Action
{
    /**
     * @param Context $context
     * @param ExecuteContext $executeContext
     * @return void
     */
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
        $context->service->prepare();
    }
}
```

This allows you to implement different dishes by swapping in different services, while keeping the key steps of the process consistent.