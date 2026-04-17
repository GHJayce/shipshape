# ExecuteContext

`\Ghjayce\Shipshape\Entity\Context\ExecuteContext::class`

是Shipshape在执行期间的上下文对象，包含了当前action的相关信息和一些流程操作。

## exit()

标记退出信号。

```php
$executeContext->exit();
```

例如： 从当前action的某个位置退出，后续的action都不再执行。

```php
<?php
use Ghjayce\Shipshape\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class SomeAction extends Action
{
    public function process(ClientContext $context, ExecuteContext $executeContext)
    {
        // 业务逻辑
        if ($someCondition) {
            return $executeContext->exit();
        }
        // 后续的业务逻辑
    }
}
```
