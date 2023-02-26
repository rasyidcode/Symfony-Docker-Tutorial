<?php

namespace App\ArgumentResolver;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Uuid;

class UuidParamValueResolver implements ArgumentValueResolverInterface
{

    public function __construct(private readonly LoggerInterface $logger)
    {

    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $className = $argument->getType();
        return $className && $className == Uuid::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $this->logger->info('applying UuidParamConverter');
        $param = $argument->getName();
        if (!$request->attributes->has($param)) {
            return [];
        }

        $value = $request->attributes->get($param);
        $this->logger->debug('The request attribute name: "' . $param . '", value: "' . $value . '"');
        if (!$value && $argument->isNullable()) {
            return [];
        }

        return [Uuid::fromString($value)];
    }
}