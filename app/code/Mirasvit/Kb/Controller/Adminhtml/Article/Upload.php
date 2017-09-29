<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit-extended
 * @package   mirasvit/module-kb
 * @version   1.0.29
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Kb\Controller\Adminhtml\Article;

class Upload extends \Magento\Backend\App\Action
{
    /***
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directory_list;

    /***
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /***
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Filesystem\Io\File $io,
        \Mirasvit\Kb\Model\DocumentFactory $documentFactory
    )
    {
        parent::__construct($context);
        $this->directory_list = $directory_list;
        $this->registry = $registry;
        $this->request = $request;
        $this->io = $io;
        $this->documentFactory = $documentFactory;
    }

    public function execute()
    {
        $mediaPath = $this->directory_list->getPath('media');
        $media = $mediaPath . '/kb/documents/';

        // Create directory if not exist
        if (!file_exists($media)) {
            //mkdir($this->directory_list->getPath('media') . '/import/images', '775', true);
            $this->io->mkdir($media, 0775);
        }

        $prefix = 'kb_';
        $file_name = $prefix . $_FILES['files']['name'][0];
        $file_tmp = $_FILES['files']['tmp_name'][0];
        $file_size =$_FILES['files']['size'][0];

        if (move_uploaded_file($file_tmp, $media . $file_name)) {
            // Save in db
            if ($this->saveDocument($file_name, $file_size)) {
                $this->success();
            } else {
                $this->fail("File uploaded but could not save to system");
            }
        } else {
            $this->fail("Could not upload file");
        }
    }

    private function json_out($status, $message)
    {
        echo json_encode(
            array(
                "status" => $status,
                "message" => $message)
        );
    }

    protected function success($msg)
    {
        $this->json_out('success', $msg);
    }

    protected function fail($msg)
    {
        $this->json_out('fail', $msg);
    }

    protected function saveDocument($filename, $size)
    {
        $docModel = $this->documentFactory->create();
        //$docModel->setData(array("" => $filename);
        $docModel->setName($filename);
        $docModel->setType( pathinfo($filename, PATHINFO_EXTENSION) );
        $docModel->setSize( $size );
        $docModel->setUploaded( date("Y-m-d") );
        $docModel->save();
        //$docsCollection = $docModel->getCollection();
        //var_dump($docsCollection->getData());
    }
}