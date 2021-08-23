<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;


class ApplicationType extends AbstractType
{
    /**
     * @param string $label
     * @param string $placeholder
     * @param boolean $required
     * @param array $options
     * 
     * @return array
     */
    protected function getConfiguration(
        string $label, 
        string $placeholder, 
        bool $required = true, 
        array $options = []
        ): array {
        
            return array_merge_recursive([
                'label' => $label,
                'required' => $required,
                'attr'  => [
                    'placeholder' => $placeholder,
                ]
        ], $options);
    }
}