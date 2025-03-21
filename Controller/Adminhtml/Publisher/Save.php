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

namespace GB\PublisherBook\Controller\Adminhtml\Publisher;

use Exception;
use GB\PublisherBook\Model\ResourceModel\Publisher;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Catalog\Model\ImageUploader;
use GB\PublisherBook\Model\PublisherFactory;

class Save extends Action implements HttpPostActionInterface
{
    public const URL_GRID = 'publisher_book/publisher/grid';
    public const URL_FORM = 'publisher_book/publisher/add';
    /**
     * @var PublisherFactory
     */
    protected PublisherFactory $publisherFactory;
    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;
    /**
     * @var ImageUploader
     */
    protected ImageUploader $imageUploader;
    /**
     * @var Publisher
     */
    protected Publisher $publisherResourceModel;
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $managerInterface;

    /**
     * Button Save action Admin Form Save Logo Book Publisher
     *
     * @param Action\Context $context
     * @param PublisherFactory $publisherFactory
     * @param UploaderFactory $uploaderFactory
     * @param ImageUploader $imageUploader
     * @param Publisher $publisherResourceModel
     * @param ManagerInterface $managerInterface
     */
    public function __construct(
        Action\Context $context,
        PublisherFactory $publisherFactory,
        UploaderFactory $uploaderFactory,
        ImageUploader $imageUploader,
        Publisher $publisherResourceModel,
        ManagerInterface $managerInterface,
    ) {
        parent::__construct($context);
        $this->publisherFactory = $publisherFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->imageUploader = $imageUploader;
        $this->publisherResourceModel = $publisherResourceModel;
        $this->managerInterface = $managerInterface;
    }

    /**
     * Execute Save Image
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute(): ResultInterface|ResponseInterface
    {
        $postData = $this->getRequest()->getPostValue();

        if (!$postData) {
            $this->messageManager->addErrorMessage(__('No data to save.'));
            return $this->_redirect(self::URL_FORM);
        }

        try {
            $publisher = $this->publisherFactory->create();

            if (!empty($data['entity_id'])) {
                $publisher->load($data['entity_id']);
            }

            $publisher->setData($postData);

            if (!empty($postData['logo'])) {
                try {
                    $logo = $postData['logo'][0];

                    if (strpos($logo['url'], 'media/label') !== false) {

                        if (isset($logo['file']) && !empty($logo['file'])) {
                            $image = (string)$logo['name'];
                            $publisher->setData('logo', $image);
                            $imageName = $this->imageUploader->moveFileFromTmp($image);
                            $publisher->setData('logo', $imageName);
                        } else {
                            $imageName = (explode("/", $logo['url']));
                            $publisher->setData('logo', $imageName[6]);
                        }
                    } else {
                        $imageName = (explode("/", $logo['url']));
                        $publisher->setData('logo', $imageName[6]);
                    }
                } catch (Exception $e) {
                    $imageName = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
                }
            }
            $this->publisherResourceModel->save($publisher);
            $this->messageManager->addSuccessMessage('Book Publisher has been saved');
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setPath(self::URL_GRID);

            return $result;
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Error saving a Publisher ' . $e->getMessage()));
        }

        return $this->_redirect(self::URL_FORM);
    }
}
