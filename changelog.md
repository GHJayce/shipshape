
## version
### v0.2.0 pre
- ShipshapeConfig默认添加TheEnd Action（而不再是ShipshapeHookConfig），可通过参数appendTheEndAction进行控制。
- 保留ShipshapeConfig类属性Actions的名称，不做特殊处理（上一版本会取不含命名空间的类名作为名称）。
- ShipshapeConfig的intoCallable方法变更为public。
- 继承抽象类Action的Action类，现在支持handle方法返回ClientContext类型了。
- 修复抽象类Action中ClientContext的上下文应保持最新。

### v0.1.0
- 由Eutaxy正式更名为Shipshape。
- 重新设计配置的实现、调用。让整体使用更容易、且易于自定义和扩展。
- 删除无用的设计。

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