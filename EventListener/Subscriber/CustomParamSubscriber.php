<?php
namespace YoRenard\PimpMyQueryBundle\EventListener\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class CustomParamSubscriber
 * @package YoRenard\PimpMyQueryBundle\EventListener\Subscriber
 */
class CustomParamSubscriber implements EventSubscriberInterface
{
    /**
     * Construct
     *
     */
    public function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
//            FormEvents::PRE_SUBMIT => 'preSetData',
//            FormEvents::SUBMIT => 'preSetData',
//            FormEvents::POST_SUBMIT => 'preSetData',
//            FormEvents::PRE_SET_DATA => 'preSetData',
//            FormEvents::POST_SET_DATA => 'preSetData',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
//        $product = $event->getData();
//        $form = $event->getForm();
//
//        if (!$product || null === $product->getId()) {
//            $form->add('name', 'text');
//        }

        $form        = $event->getForm();
        $customParam = $event->getData();

        $form->add('name', 'text');
//        $customParam = $event->getData();

//        $form = $this->container->get('form.factory')->create(new CustomParamType());
//        $form->setData(array('method' => 'get'));
//
//        $paramList = $this->pmqParamManager->getQueryParamList($this->pmqQuery->getIdQuery());
//        foreach($paramList as $param) {
//            $includeForm = $this->container->get('form.factory')->create(GeneratedFormFactory::create($param));
//            $form->add($includeForm);
//
//            if (in_array($param->getFieldType(), self::$postParameters)) {
//                $form->setData(array('method' => 'post'));
//            }
//        }
    }
} 