<?php
namespace Ttree\OutOfBandRendering\Controller;

use Ttree\OutOfBandRendering\Factory\PresetDefinitionFactory;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Exception;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Property\PropertyMapper;
use TYPO3\Neos\Controller\Exception\NodeNotFoundException;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\View\TypoScriptView;

/**
 * Suggest Controller
 */
class RenderingController extends ActionController {

    /**
     * @var TypoScriptView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = 'TYPO3\Neos\View\TypoScriptView';

    /**
     * @var PropertyMapper
     * @Flow\Inject
     */
    protected $propertyMapper;

    /**
     * @Flow\Inject
     * @var PresetDefinitionFactory
     */
    protected $presetDefinitionFactory;

    /**
     * @param string $node
     * @param string $preset
     * @throws Exception
     */
    public function showAction($node, $preset) {
        /** @var NodeInterface $node */
        $node = $this->propertyMapper->convert($node, 'TYPO3\TYPO3CR\Domain\Model\NodeInterface');
        if ($node === NULL) {
            throw new NodeNotFoundException('The requested node does not exist or isn\'t accessible to the current user', 1442327533);
        }
        $this->view->assign('value', $node);
        $presetDefinition = $this->presetDefinitionFactory->create($preset, $node);
        $this->view->setTypoScriptPath($presetDefinition->getTypoScriptPath($node));
    }

}
