# Swooen框架

受Lumen启发，基于Symfony、Swoole的一个自研框架。

- 专门面向接口，对于HTTP协议的处理不擅长
- 容器化
- FPM下可运行HTTP服务
- WebSocket作为HTTP的壳
- 未来可扩展gRPC、TCP等协议
- 无论请求来自何种协议，都应当封装成统一的请求对象。
- 