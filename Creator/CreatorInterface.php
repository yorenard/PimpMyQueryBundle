<?php
namespace YoRenard\PimpMyQueryBundle\Creator;

/**
 * Interface CreatorInterface
 * @package YoRenard\PimpMyQueryBundle\Creator
 */
interface CreatorInterface
{
    /**
     * Create and return un object
     *
     * @return object
     */
    public function create();
}
