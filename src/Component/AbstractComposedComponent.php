<?php

namespace PlatformPHP\ComposedViews\Component;

abstract class AbstractComposedComponent extends AbstractComponent implements ComponentContainerInterface
{
    use ComponentContainerTrait;
}