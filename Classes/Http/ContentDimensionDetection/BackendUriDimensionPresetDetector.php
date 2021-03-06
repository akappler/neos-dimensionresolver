<?php
namespace Flowpack\Neos\DimensionResolver\Http\ContentDimensionDetection;

/*
 * This file is part of the Neos.Neos package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\Domain\Utility\NodePaths;
use Neos\Flow\Http;

/**
 * Backend URI based dimension preset detector
 */
final class BackendUriDimensionPresetDetector implements ContentDimensionPresetDetectorInterface
{
    /**
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * @param string $dimensionName
     * @param array $presets
     * @param Http\Component\ComponentContext $componentContext
     * @param array|null $overrideOptions
     * @return array|null
     */
    public function detectPreset(string $dimensionName, array $presets, Http\Component\ComponentContext $componentContext, array $overrideOptions = null)
    {
        $path = $componentContext->getHttpRequest()->getUri()->getPath();
        $path = '/' . mb_substr($path, mb_strpos($path, '@'));
        if (mb_strpos($path, '.') !== false) {
            $path = mb_substr($path, 0, mb_strrpos($path, '.'));
        }
        $nodePathAndContext = NodePaths::explodeContextPath($path);
        if (isset($nodePathAndContext['dimensions'][$dimensionName])) {
            foreach ($presets as $preset) {
                if ($preset['values'] === $nodePathAndContext['dimensions'][$dimensionName]) {
                    return $preset;
                }
            }
        }

        return null;
    }
}
