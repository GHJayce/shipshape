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
        A::class,
        B::class,
        C::class,
    ]);
```

这里的A、B、C类，每个都是一个action，必须继承`\Ghjayce\Shipshape\Action\Action::class`。

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
    ])
```

### NamespaceConfig