<?php

/**
 * Directus – <http://getdirectus.com>
 *
 * @link      The canonical repository – <https://github.com/directus/directus>
 * @copyright Copyright 2006-2016 RANGER Studio, LLC – <http://rangerstudio.com>
 * @license   GNU General Public License (v3) – <http://www.gnu.org/copyleft/gpl.html>
 */

namespace Directus\SDK\Response;

use Directus\Util\ArrayUtils;

/**
 * Entry Collection
 *
 * @author Welling Guzmán <welling@rngr.org>
 */
class EntryCollection implements ResponseInterface, \IteratorAggregate , \Countable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * EntryCollection constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->metadata = ArrayUtils::omit($data, 'rows');

        $rows = isset($data['rows']) ? $data['rows'] : [];
        $items = [];
        foreach($rows as $row) {
            $items[] = new Entry($row);
        }

        $this->items = $items;
    }

    /**
     * Get the response raw data
     *
     * @return array
     */
    public function getRawData()
    {
        return $this->data;
    }

    /**
     * Get the response entries
     *
     * @return array
     */
    public function getData()
    {
        return $this->items;
    }

    /**
     * Get the response metadata
     *
     * @return array
     */
    public function getMetaData()
    {
        return $this->metadata;
    }

    /**
     * Create a new iterator based on this collection
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Gets the number of entries in this collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Gets an object representation of this collection
     *
     * @return object
     */
    public function jsonSerialize()
    {
        return (object) [
            'metadata' => $this->metadata,
            'data' => $this->items
        ];
    }
}