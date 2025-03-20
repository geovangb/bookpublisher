<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */
declare(strict_types=1);

namespace GB\PublisherBook\Controller\Adminhtml\Publisher;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class InlineEdit extends Action
{

    protected $jsonFactory;
    protected \GB\PublisherBook\Api\PublisherRepositoryInterface $publisherRepository;
    protected \GB\PublisherBook\Api\Data\PublisherInterface $publisher;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param \GB\PublisherBook\Api\PublisherRepositoryInterface $publisherRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \GB\PublisherBook\Api\Data\PublisherInterface $publisher,
        \GB\PublisherBook\Api\PublisherRepositoryInterface $publisherRepository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->publisherRepository = $publisherRepository;
        $this->publisher = $publisher;
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $publisherId) {

                    $model = $this->publisherRepository->getById($publisherId);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$publisherId]));
                        $this->publisherRepository->save($model);
                    } catch (\Exception $e) {
                        $messages[] = "[Publisher ID: {$publisherId}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
