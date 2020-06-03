<?php

namespace Binocle\Component;

class Taxonomy
{
    /**
     * Taxonomies to register
     * @var array
     */
    private $taxonomies = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        \Hook::add('init', [$this, 'registerTaxonomies']);
    }
    /**
     * Add new taxonomy
     *
     * @param string $taxonomyName
     * @param array|string $posttypes
     * @param array $args
     * @return object Binocle\Component\Taxonomy
     */
    public function add($taxonomyName, $postTypes = null, $args = [])
    {
        $this->taxonomies[$taxonomyName] = [
            'postTypes' => $postTypes,
            'args' => $args,
        ];

        return $this;
    }

    public function registerTaxonomies()
    {
        foreach ($this->taxonomies as $taxonomyName => $info) {
            register_taxonomy($taxonomyName, $info['postTypes'], $info['args']);
        }
    }
}