<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Exception;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class AssetNotFoundException extends ComposedViewsException
{
    public function __construct(string $assetId)
    {
        parent::__construct("Asset '{$assetId}' not found.");
    }
}
