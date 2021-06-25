# Swooen框架

受Lumen启发，基于Symfony、Swoole的一个自研框架。

- 面向接口优化，对于页面渲染没有特殊优化，但可以支持
- 容器化
- FPM下可运行HTTP服务
- 支持WebSocket
- 未来可扩展gRPC、TCP等协议
- 无论请求来自何种协议，都应当封装成统一的请求对象。
- 对于持久连接，如WebSocket，请求帧会被封装成请求对象
- 容器分级别，连接、进程
- 抽象HTTP和WebSocket

## 数据包
数据包元素分为

- 输入 主要数据
- 元数据 辅助数据
- 追踪数据 追踪对端信息的数据

### HTTP协议处理成数据包
- Body和Query合并成输入
- Header映射为元数据
- Cookie 映射为追踪数据

### WebSocket
- 数据帧和Query合并为成输入
- Header映射为元数据
- Cookie 映射为追踪数据

### 数据包的路由
#### 可路由数据包
将会被路由到指定处理器

#### 不可路由数据包
会指定路由到`<NOT_ROUTEABLE>`对应的处理器
