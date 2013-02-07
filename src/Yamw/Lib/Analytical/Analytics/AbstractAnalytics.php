<?php
namespace Yamw\Lib\Analytical\Analytics;

/**
 * Base class for analytics
 *
 * @author AnhNhan
 */
abstract class AbstractAnalytics
{
    private $data = array();
    private $processed = array();

    private $specifics;

    private $db;

    final public function __construct(array $specifics = null, $db = null)
    {
        $this->specifics = $specifics ?: array();
        $this->db = $db ?: \Yamw\Lib\Mongo\AdvMongo::getConn();

        $this->query();
        $this->process();
    }

    /**
     * Fetches the data from the database
     */
    final private function query()
    {
        $query = $this->db->selectCollection($this->getCollection())->find(
            $this->getQuery(),
            $this->getFields()
        );

        foreach ($query as $entry) {
            $this->data[] = $entry;
        }
    }

    /**
     * Returns the specifics passed to this object
     *
     * @return array
     */
    final protected function getSpecifics()
    {
        return $this->specifics;
    }

    /**
     * Returns the data from the query
     *
     * @return array
     */
    final protected function getData()
    {
        return $this->data;
    }

    /**
     * Returns the processed data
     *
     * @return array
     */
    final public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * Sets the processed data to the passed array
     *
     * @param array $processed
     */
    final public function setProcessed(array $processed)
    {
        $this->processed = $processed;
    }

    /**
     * Gets the name of this analytic
     *
     * @return string The name
     */
    abstract public function getName();

    /**
     * Which fields should be included in the query
     *
     * @return array The array describing which fiels the query should
     * include/exclude
     */
    abstract public function getFields();
    abstract public function getQuery();

    /**
     * In which collection the data should be fetched from
     *
     * @return string
     */
    abstract public function getCollection();

    /**
     * Processes the analytical data
     *
     * @return array The processed data
     */
    abstract protected function process();
}
