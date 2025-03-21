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

use GB\PublisherBook\Api\Data\PublisherInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\File\Csv;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList as AppDirectoryList;
use GB\PublisherBook\Api\PublisherRepositoryInterface;
use GB\PublisherBook\Api\Data\PublisherInterfaceFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class ProcessImport extends Action
{
    protected $csvProcessor;
    protected $uploaderFactory;
    protected $filesystem;
    protected $publisherRepository;
    protected $publisherFactory;
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param Csv $csvProcessor
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param PublisherRepositoryInterface $publisherRepository
     * @param PublisherInterfaceFactory $publisherFactory
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        Csv $csvProcessor,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        PublisherRepositoryInterface $publisherRepository,
        PublisherInterfaceFactory $publisherFactory,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->csvProcessor = $csvProcessor;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->publisherRepository = $publisherRepository;
        $this->publisherFactory = $publisherFactory;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        try {
            if (!isset($_FILES['import_file']['tmp_name'])) {
                throw new \Exception(__('No files uploaded.'));
            }

            $uploader = $this->uploaderFactory->create(['fileId' => 'import_file']);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $mediaDirectory = $this->filesystem->getDirectoryWrite(AppDirectoryList::VAR_DIR);
            $filePath = $mediaDirectory->getAbsolutePath('import/') . $uploader->save($mediaDirectory->getAbsolutePath('import/'))['file'];

            $data = $this->csvProcessor->getData($filePath);
            array_shift($data);

            foreach ($data as $row) {
                if (count($row) < 2) {
                    continue;
                }

                /** @var PublisherInterface $publisher */
                $publisher = $this->publisherFactory->create();
                $publisher->setName($row[0]);
                $publisher->setStatus($row[1]);

                $this->publisherRepository->save($publisher);
            }

            return $this->jsonFactory->create()->setData(['success' => true, 'message' => __('Publishers imported successfully.')]);
        } catch (\Exception $e) {
            return $this->jsonFactory->create()->setData(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
