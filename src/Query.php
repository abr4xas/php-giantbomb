<?php

/**
 * This file is part of the GiantBomb PHP API created by Davide Borsatto.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright (c) 2015, Davide Borsatto
 */
namespace dborsatto\GiantBomb;

/**
 * Class Query.
 *
 * @author Davide Borsatto <davide.borsatto@gmail.com>
 */
class Query
{
    /**
     * @var Repository
     */
    private $repository = null;

    /**
     * A list of active filter.
     *
     * @var array
     */
    private $filterBy = array();

    /**
     * The active sorting field.
     *
     * @var string
     */
    private $sortBy = null;

    /**
     * A list of fields that will be loaded.
     *
     * @var array
     */
    private $fieldList = array();

    /**
     * The repository resource ID.
     *
     * @var string
     */
    private $resourceId = null;

    /**
     * A list of active parameters.
     *
     * @var array
     */
    private $parameters = array();

    /**
     * Class constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Adds a field to the current filtering set.
     *
     * @param string $field
     * @param string $value
     *
     * @return Query
     */
    public function addFilterBy($field, $value)
    {
        $this->filterBy[$field] = $value;

        return $this;
    }

    /**
     * Sorts by the given value.
     *
     * @param string $field
     * @param string $direction
     *
     * @return Query
     */
    public function sortBy($field, $direction = 'asc')
    {
        $this->sortBy = array($field, $direction);

        return $this;
    }

    /**
     * Sets the field list to be included in the result.
     *
     * @param array $list
     *
     * @return Query
     */
    public function setFieldList(array $list)
    {
        $this->fieldList = $list;

        return $this;
    }

    /**
     * Sets a parameter for the current query.
     *
     * @param string $parameter
     * @param string $value
     *
     * @return Query
     */
    public function setParameter($parameter, $value)
    {
        $this->parameters[$parameter] = $value;

        return $this;
    }

    /**
     * Sets a resource ID for the current query.
     *
     * @param string $id
     *
     * @return Query
     */
    public function setResourceId($id)
    {
        $this->resourceId = $id;

        return $this;
    }

    /**
     * Loads an array of resource Model given the current data.
     *
     * @return array
     */
    public function find()
    {
        return $this->repository->find($this->compileParameters());
    }

    /**
     * Loads a single resource Model given the current data.
     *
     * @return Model
     */
    public function findOne()
    {
        return $this->repository->findOne($this->compileParameters());
    }

    /**
     * Returns an array of the current parameters.
     *
     * @return array
     */
    private function compileParameters()
    {
        $return = array(
            'query' => array(),
            'resource_id' => $this->resourceId,
        );

        if ($this->filterBy) {
            $return['query']['filter_by'] = $this->filterBy;
        }

        if ($this->sortBy) {
            $return['query']['sort_by'] = $this->sortBy;
        }

        if ($this->fieldList) {
            $return['query']['field_list'] = $this->fieldList;
        }

        if ($this->parameters) {
            foreach ($this->parameters as $name => $value) {
                $return['query'][$name] = $value;
            }
        }

        return $return;
    }
}
