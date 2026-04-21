# Config

`\Ghjayce\Shipshape\Entity\Config\Config::class`

The configuration class for Shipshape, used as a property of ExecuteContext to configure the actions to be executed.

Config is an abstract class. You should choose one of the following implementations:

- `ActionConfig`: Specify multiple action classes.
- `ClassConfig`: Generate actions based on the provided class.
- `NamespaceConfig`: Generate actions based on the provided namespace.

## Configuration Methods

### ActionConfig

Basic usage:

```php
<?php

use Ghjayce\Shipshape\Entity\Config\ActionConfig;

ActionConfig::make()
    ->setActions([
        \Your\Namespace\Prepare::class,
        \Your\Namespace\Handle::class,
        \Your\Namespace\Cook::class,
    ]);
```

Here, Prepare, Handle, and Cook are all action classes. Each must extend `\Ghjayce\Shipshape\Action\Action::class` and implement the `process()` method.

> This configuration method is recommended.

### ClassConfig

```php
<?php

use Ghjayce\Shipshape\Entity\Config\ClassConfig;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;

class Cooking
{
    public function prepare(ClientContext $context, ExecuteContext $executeContext)
    {
    
    }
    public function handle(ClientContext $context, ExecuteContext $executeContext)
    {
    
    }
    public function cook(ClientContext $context, ExecuteContext $executeContext)
    {
    
    }
}

ClassConfig::make()
    ->setClass(Cooking::class)
    ->setNames([
        'prepare',
        'handle',
        'cook'
    ]);
```

The usage is similar, except that actions are now methods within a class, and the name corresponds to the method name.

### NamespaceConfig

```php
<?php

use Ghjayce\Shipshape\Entity\Config\NamespaceConfig;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;

NamespaceConfig::make()
    ->setNamespace('\\Your\\Namespace\\')
    ->setNames([
        'prepare',
        'handle',
        'cook'
    ]);
```

The corresponding directory structure under the namespace:

```
└── Your
    └── Namespace
        ├── Prepare.php
        ├── Handle.php
        └── Cook.php
```

For example, the method accessed for `prepare` is `\\Your\\Namespace\\Prepare::execute`, which means it will look for the class and method under the specified namespace according to the provided name.

> Prepare.php can also extend `\Ghjayce\Shipshape\Action\Action::class`.

## $container

Set the container instance. This property determines whether the instance should be retrieved from the container.

If not set, the instance is created using `new`.

## $hook

Set hook methods, which are divided into before, process, and after stages. These are triggered before, during, and after each action execution. The hook method parameters are the same as the action's process method, making it suitable for debugging.