# Action

`\Ghjayce\Shipshape\Action\Action::class`

一个具体的动作，它是执行的最小单元，可以拿到自定义的上下文和shipshape的上下文对象。

## return()

标记退出，在当前action处理完以后，退出功能模块的执行，后续的action都不再执行。

```php
<?php
use Ghjayce\Shipshape\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class SomeAction extends Action
{
    public function process(ClientContext $context, ExecuteContext $executeContext)
    {
        // 该方法执行完以后
    }
    
    public function return(ClientContext $context, ExecuteContext $executeContext): bool
    {
        return true;
    }
}
```

## result()

设置在退出时返回的数据，即功能模块返回的数据。

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