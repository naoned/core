<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\HandlerProviders;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Onyx\Services\CQS\Queries\NullQuery;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\HandlerProviders\Exceptions\NoValidHandlerFound;

class MultipleHandlerProviderTest extends TestCase
{
    private
        $emptyProvider,
        $provider;

    protected function setUp(): void
    {
        $containerQ1 = new Container();
        $containerQ2 = new Container([
            NullQuery::class => $this->nullQueryHandler(),
        ]);

        $containerC1 = new Container();
        $containerC2 = new Container([
            NullCommand::class => $this->nullCommandHandler(),
        ]);

        $this->provider = new MultipleHandlerProvider([
            new PimpleClassBased($containerQ1),
            new PimpleClassBased($containerC1),
            new PimpleClassBased($containerC2),
            new PimpleClassBased($containerQ2),
        ]);

        $this->emptyProvider = new MultipleHandlerProvider([
            new PimpleClassBased($containerQ1),
            new PimpleClassBased($containerC1),
        ]);
    }

    private function nullQueryHandler(): QueryHandler
    {
        return new class implements QueryHandler {
            public function accept(Query $query): bool {}
            public function handle(Query $query): QueryResult {}
        };
    }

    private function nullCommandHandler(): CommandHandler
    {
        return new class implements CommandHandler {
            public function accept(Command $command): bool {}
            public function handle(Command $command): void {}
        };
    }

    public function testFindQueryHandlerFor()
    {
        $handler = $this->provider->findQueryHandlerFor(new NullQuery());

        $this->assertEquals($this->nullQueryHandler(), $handler);
    }

    public function testFindCommandHandlerFor()
    {
        $handler = $this->provider->findCommandHandlerFor(new NullCommand());

        $this->assertEquals($this->nullCommandHandler(), $handler);
    }

    public function testQueryHandlerNotFound()
    {
        $this->expectException(NoValidHandlerFound::class);
        $this->emptyProvider->findQueryHandlerFor(new NullQuery());
    }

    public function testCommandHandlerNotFound()
    {
        $this->expectException(NoValidHandlerFound::class);
        $this->emptyProvider->findCommandHandlerFor(new NullCommand());
    }
}
