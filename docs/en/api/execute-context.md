# ExecuteContext

`\Ghjayce\Shipshape\Entity\Context\ExecuteContext::class`

The context object during Shipshape execution, containing information about the current action and some process operations.

## exit()

Mark an exit signal.

```php
$executeContext->exit();
```

For example: Exit from a certain point in the current action, and all subsequent actions will not be executed.

```php
<?php
use Ghjayce\Shipshape\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class SomeAction extends Action
{
    public function process(ClientContext $context, ExecuteContext $executeContext)
    {
        // Business logic
        if ($someCondition) {
            return $executeContext->exit();
        }
        // Further business logic
    }
}
```