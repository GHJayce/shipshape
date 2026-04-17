---
title: 使用
lang: zh
editLink: true
---

# 使用

## 概念

以烹饪为例，在代码中代表着需要实现的一个功能模块（这里指`cooking`这个方法）。

```php
<?php

function cooking(): mixed
{
    // 步骤1 获取食材
    // 步骤2 清洗食材
    // 步骤3 切菜
    // 步骤4 腌制食材
    // 步骤5 调味
    // 步骤6 烹饪食材
    // 步骤7 装盘
    // 步骤8 返回结果
    return;
}
```

随着功能模块的复杂度增加，步骤也会随之增加，代码就会变得臃肿，难以维护和扩展。

Shipshape就是为了解决这个问题而诞生的。

## 基础

基于Shipshape的实现，上面的代码可以改写为：

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
            // 步骤1 获取食材
            FetchIngredient::class,
            // 步骤2 清洗食材
            WashIngredient::class,
            // 步骤3 切菜
            ChopIngredient::class,
            // 步骤4 腌制食材
            MarinateIngredient::class,
            // 步骤5 调味
            SeasonDish::class,
            // 步骤6 烹饪食材
            CookIngredient::class,
            // 步骤7 装盘
            PlateDish::class,
            // 步骤8 返回结果
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

// 为了方便演示，这里只展示获取食材，其他action同理
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

### 概念

`cooking()`功能模块最终会按照步骤1直步骤8顺序执行，最终在步骤8进行退出并返回结果。

- `action`：每个步骤都是一个动作，例如`获取食材`、`烹饪食材`等，可以随意编排顺序、增加新的或者替换动作。
- `ActionConfig`：用于提供action配置的类。
- `Context`:功能模块专用的上下文类，用于存储动作之间需要用到的变量。
- `ExecuteContext`：Shipshape专用的上下文类。

## 进阶

> 仅举例说明使用方式，具体实现因人因场景发挥。

对烹饪这个功能模块进行抽象以后，大致有5个固定的步骤：

- 步骤1：准备食材，例如各种方式获得，可以是购买、从冰箱拿出、从地里挖出等等。
- 步骤2：处理食材，例如清洗、切菜、腌制、调味等等。
- 步骤3：烹饪食材，例如各种方式烹饪，可以是炒、炖、蒸、炸等等。
- 步骤4：装盘，例如各种方式装盘，可以是直接装盘、摆盘、分装等等。
- 步骤5：返回结果。

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
    public function prepare()
    {
    
    }
    public function handle()
    {
    
    }
    public function cook()
    {
    
    }
    public function plate()
    {
    
    }
}

// 为了方便演示，这里只展示获取食材，其他action同理
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

这样就可以通过更换不同的service来实现做不同的菜品，制作过程的关键步骤都是一致的。