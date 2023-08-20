<?php declare(strict_types=1);

namespace Amp\Redis;

use Amp\PHPUnit\AsyncTestCase;
use Amp\Redis\Connection\ChannelLink;
use Amp\Redis\Connection\SocketChannelFactory;

abstract class IntegrationTest extends AsyncTestCase
{
    protected Redis $redis;

    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->getUri()) {
            $this->markTestSkipped('AMPHP_TEST_REDIS_INSTANCE is not set');
        }

        $this->redis = $this->createInstance();

        $this->redis->flushAll();
    }

    final protected function createInstance(): Redis
    {
        $config = RedisConfig::fromUri($this->getUri());
        return new Redis(new RedisClient(new ChannelLink($config, new SocketChannelFactory($config))));
    }

    final protected function getUri(): ?string
    {
        return \getenv('AMPHP_TEST_REDIS_INSTANCE') ?: null;
    }
}
