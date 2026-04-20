# Config

`\Ghjayce\Shipshape\Entity\Config\Config::class`

Shipshape的配置类，ExecuteContext的属性，用于配置需要执行的action。

Config只是一个抽象类，应该从下面的类实现中选择一个来使用：

- `ActionConfig`：指定多个action类。
- `ClassConfig`：按照提供的类生成。
- `NamesapceConfig`：按照提供的命名空间生成。

## 配置方式

### ActionConfig

基本使用：

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

这里的Prepare、Handle、Cook类，每个都是一个action，必须继承`\Ghjayce\Shipshape\Action\Action::class`，实现`process()`方法。

> 推荐使用该配置方式。

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

使用上基本一致，只是action不再是一个独立的类，而是一个类中的方法，name对应方法名。

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

对应命名空间下的目录结构：

```
└── Your
    └── Namespace
        ├── Prepare.php
        ├── Handle.php
        └── Cook.php
```

例如prepare最终访问的方法是`\\Your\\Namespace\\Prepare::execute`，也就是会在指定的命名空间下，按照提供的name去寻找类、方法。

> Prepare.php同样可以继承`\Ghjayce\Shipshape\Action\Action::class`。

## $container

设置容器实例，该属性决定实例是否需要从容器中取出。

没有设置的情况下，实例通过new的方式创建。

## $hook

设置钩子方法，分为before、process、after三个时机，分别在每个action执行前、执行时、执行后触发。钩子方法的参数与action的process方法一致，适合在调试时使用。