
## 2025-11-06
### v1.0.0
- 大致结构、使用上基本不变，多个类或方法重新命名，不向后兼容。
- 使用`phparm/phparm`包。
- php版本最低支持`8.0.2`。
- 去除脚本和一些示例文件。

## 2024-09-22
### v0.3.1
- 改进bin/shipshape脚本中的`generator:propertyComment`命令，优化执行结果报告，当参数类型有多个时用`|`分隔。
- 改进shipshape的使用演示，偏向实战实例。基于`generator:propertyComment`命令的实现，列举了原生写法和使用shipshape后的三种写法。

## 2024-09-18
### v0.3.0
- 新增bin/shipshape脚本，基于Symfony/Console。命令有`generator:propertyComment`，将继承于`Ghjayce\Shipshape\Entity\Base\Property`的类，按照属性自动生成DocComment注释，减轻开发工作量。命令：`composer exec shipshape generator:propertyComment "\src/User/Business/Web"`

## 2024-08-31
### v0.2.0
- ShipshapeConfig默认添加TheEnd Action（而不再是ShipshapeHookConfig），可通过参数appendTheEndAction进行控制。
- 保留ShipshapeConfig类属性Actions的名称，不做特殊处理（上一版本会取不含命名空间的类名作为名称）。
- ShipshapeConfig的intoCallable方法变更为public。
- 继承抽象类Action的Action类，现在支持handle方法返回ClientContext类型了。
- 修复抽象类Action中ClientContext的上下文应保持最新。

## 2024-05-27
### v0.1.0
- 由Eutaxy正式更名为Shipshape。
- 重新设计配置的实现、调用。让整体使用更容易、且易于自定义和扩展。
- 删除无用的设计。

## 2024-02-06
### v0.0.7
- Eutaxy
  - pathGenerate剥离对BASE_PATH的处理。

### v0.0.6

- Eutaxy
  - 重新设计配置的实现，基本围绕着配置相关的改动。
    - 实现：
      - 调用相对优雅（静态调用、只需要知道配置的方法而不需要知道配置字段的命名和实现、想设置哪个配置调用哪个方法）。
      - 并且容易扩展（添加新的属性和方法很方便）
      - 容易替换（只要基于EutaxyConfig，多次继承、覆写方法都行）。
  - hook的callable、action的callable处理相对优雅了些。实现处理逻辑可复用。