<?php

namespace App\ArgumentResolver;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class BodyValueResolver implements ArgumentValueResolverInterface, LoggerAwareInterface
{

    private LoggerInterface $logger;

    public function __construct(private readonly SerializerInterface $serializer) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $attrs = $argument->getAttributes(Body::class);
        return count($attrs) > 0;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        $this->logger->debug('The argument type: "' . $type . '"');

        $format = $request->getContentType() ?? 'json';
        $this->logger->debug('The request format: "' . $format . '"');

        // read request body
        $content = $request->getContent();
        $data = $this->serializer->deserialize($content, $type, $format);

        return [$data];
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}