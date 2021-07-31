<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;


class ApplicationType extends AbstractType
{
    /**
     * @param string $label
     * @param string $placeholder
     * @param boolean $required
     * 
     * @return array
     */
    protected function getConfiguration(string $label, string $placeholder, bool $required = true): array
    {
        return [
            'label' => $label,
            'required' => $required,
            'attr'  => [
                'placeholder' => $placeholder,
            ]
        ];
    }
}