<?php

namespace App\ArgumentResolver;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QueryParamValueResolver implements ArgumentValueResolverInterface, LoggerAwareInterface
{
    public function __construct()
    {
    }

    private LoggerInterface $logger;

    /**
     * Whether this resolver can resolve the value for the given ArgumentMetadata.
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $attrs = $argument->getAttributes(QueryParam::class);
        // dd($attrs);
        return count($attrs) > 0;
    }

    /**
     * Returns the possible value(s).
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentName = $argument->getName();
//         dd($argumentName);
        $this->logger->info('Found [QueryParam] annotation/attribute "' . $argumentName . '", applying [QueryParamValueResolver]');

        $type = $argument->getType();
        // dd($type);
        $nullable = $argument->isNullable();

        $this->logger->debug('The method argument type: "' . $type . '" and nullable: "' . $nullable . '"');

        // read name property from QueryParam
        // dd($argument->getAttribute(QueryParam::class));
        $attr = $argument->getAttributes(QueryParam::class)[0]; // `QueryParam` is not repeatable
        $this->logger->debug('QueryParam: ' . $attr);
        
        // if name property is not set in `QueryParam`, use argument name instead
        $name = $attr->getName() ?? $argumentName;
        $required = $attr->isRequired() ?? false;
        $this->logger->debug('Polished QueryParam values: name="' . $name . '", required="' . $required . '"');

        // fetch query name from request
        $value = $request->query->get($name);
        $this->logger->debug('The request query parameter value: "' . $value . '"');

        // if default value is set and query param is not set, use default value instead
        if (!$value && $argument->hasDefaultValue()) {
            $value = $argument->getDefaultValue();
            $this->logger->debug('After set default value: "' . $value . '"');
        }

        if ($required && !$value) {
            throw new \InvalidArgumentException('Request query parameter "' . $name . '" is required, but not set.');
        }

        $this->logger->debug('final resolved value: "' . $value . '"');

        yield match($type) {
            'int'   => $value ? (int)$value : 0,
            'float' => $value ? (float)$value : .0,
            'bool'  => (bool)$value,
            'string'    => $value ? (string)$value : ($nullable ? null : ''),
            null => null
        };
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    


}