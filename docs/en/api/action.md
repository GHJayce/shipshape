# Action

`\Ghjayce\Shipshape\Action\Action::class`

A concrete action, which is the smallest executable unit. You can access both the custom context and the Shipshape context object.

## return()

Indicates exit. After the current action is processed, the execution of the functional module will stop, and subsequent actions will not be executed.

```php
<?php
use Ghjayce\Shipshape\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class SomeAction extends Action
{
    public function process(ClientContext $context, ExecuteContext $executeContext)
    {
        // After this method is executed
    }
    
    public function return(ClientContext $context, ExecuteContext $executeContext): bool
    {
        return true;
    }
}
```

## result()

Set the data to be returned when exiting, i.e., the data returned by the functional module.

```php
<?php
use Ghjayce\Shipshape\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class SomeAction extends Action
{
    public function process(ClientContext $context, ExecuteContext $executeContext)
    {
    }
    
    public function return(ClientContext $context, ExecuteContext $executeContext): bool
    {
        return true;
    }
    
    public function result(ClientContext $context, ExecuteContext $executeContext): mixed
    {
        // your data
        return [];
    }
}
```