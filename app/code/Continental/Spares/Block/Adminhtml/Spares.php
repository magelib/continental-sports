<?php

namespace Continental\Spares\Block;

class Spares

{

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Spares\Model\ResourceModel\Spares\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\FormFactory $formFactory,
        ObjectManagerInterface $objectManager
    ) {

        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->_storeManager = $storeManager;
        $this->_formFactory = $formFactory;
        $this->objectManager = $objectManager;

        parent::__construct($context);
    }

    protected function _prepareForm()
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );

        $model = $this->getModel();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );


        $imageUrl = $mediaDirectory . 'cart2quote/images/' . $model->getLogoImage();
        $flag = false;
        if ($model->getLogoImage()) {
            $flag = true;
        }

        $fieldset = $form->addFieldset(
            'base_fieldset', ['legend' => __('Quotation Information'), 'class' => 'fieldset-wide']
        );


        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id', 'value' => $model->getId()]);
        }

        $fieldset->addField(
            'product_id', 'label', [
                'name' => 'product_id',
                'label' => __('Product Name'),
                'title' => __('Product Name'),
                'value' => $this->getLoadProduct($model->getProductId())->getName()
            ]
        );
        $fieldset->addField(
            'quote_type', 'label', [
                'name' => 'quote_type',
                'label' => __('Quotation Type'),
                'title' => __('Quotation Type'),
                'value' => $model->getQuoteType()
            ]
        );


        $fieldset->addField(
            'company', 'label', [
                'name' => 'company',
                'label' => __('Company'),
                'title' => __('Company'),
                'value' => $model->getCompany()
            ]
        );
        $fieldset->addField(
            'salutation', 'label', [
                'name' => 'salutation',
                'label' => __('Salutation'),
                'title' => __('Salutation'),
                'value' => $model->getSalutation()
            ]
        );
        $fieldset->addField(
            'first_name', 'label', [
                'name' => 'first_name',
                'label' => __('First Name'),
                'title' => __('First Name'),
                'value' => $model->getFirstName()
            ]
        );
        $fieldset->addField(
            'last_name', 'label', [
                'name' => 'last_name',
                'label' => __('Last Name'),
                'title' => __('Last Name'),
                'value' => $model->getLastName()
            ]
        );
        $fieldset->addField(
            'street', 'label', [
                'name' => 'street',
                'label' => __('Street'),
                'title' => __('Street'),
                'value' => $model->getStreet()
            ]
        );
        $fieldset->addField(
            'number', 'label', [
                'name' => 'number',
                'label' => __('Number'),
                'title' => __('Number'),
                'value' => $model->getNumber()
            ]
        );
        $fieldset->addField(
            'zipcode', 'label', [
                'name' => 'zipcode',
                'label' => __('Zip Code'),
                'title' => __('Zip Code'),
                'value' => $model->getZipcode()
            ]
        );
        $fieldset->addField(
            'city', 'label', [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'value' => $model->getCity()
            ]
        );
        $fieldset->addField(
            'country', 'label', [
                'name' => 'country',
                'label' => __('Country'),
                'title' => __('Country'),
                'value' => $model->getCountry()
            ]
        );
        $fieldset->addField(
            'email', 'label', [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'value' => $model->getEmail()
            ]
        );
        $fieldset->addField(
            'phone', 'label', [
                'name' => 'phone',
                'label' => __('Phone'),
                'title' => __('Phone'),
                'value' => $model->getPhone()
            ]
        );
        $fieldset->addField(
            'extra_info', 'label', [
                'name' => 'extra_info',
                'label' => __('Extra Information'),
                'title' => __('Extra Information'),
                'value' => $model->getExtraInfo()
            ]
        );
        $fieldset->addField(
            'message', 'label', [
                'name' => 'message',
                'label' => __('Message'),
                'title' => __('Message'),
                'value' => $model->getMessage()
            ]
        );
        $fieldset->addField(
            'delivery_date', 'label', [
                'name' => 'delivery_date',
                'label' => __('Delivery Date'),
                'title' => __('Delivery Date'),
                'value' => $model->getDeliveryDate()
            ]
        );
        $fieldset->addField(
            'delivery_date_status', 'label', [
                'name' => 'delivery_date_status',
                'label' => __('Delivery Date Status'),
                'title' => __('Delivery Date Status'),
                'value' => $model->getDeliveryDateStatus()
            ]
        );
        if ($flag === true) {
            $fieldset->addField(
                'logo_image', 'label', [
                    'name' => 'logo_image',
                    'label' => __('Logo Image'),
                    'title' => __('Logo Image'),
                    'value' => '<img src="' . $imageUrl . '" width="50"/>',
                ]
            );
       }


        $form->setAction($this->getUrl('*/* /save'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}