<?php

/**
 * Directus – <http://getdirectus.com>
 *
 * @link      The canonical repository – <https://github.com/directus/directus>
 * @copyright Copyright 2006-2016 RANGER Studio, LLC – <http://rangerstudio.com>
 * @license   GNU General Public License (v3) – <http://www.gnu.org/copyleft/gpl.html>
 */

namespace Directus\SDK;

use Directus\SDK\Response\Item;
use Directus\SDK\Response\ItemCollection;

/**
 * Requests Interface
 *
 * @author Welling Guzmán <welling@rngr.org>
 */
interface RequestsInterface
{
    /**
     * Finds a list of activity items
     *
     * @param array $params
     *
     * @return Item
     */
    public function getActivityList(array $params = []);

    /**
     * Find one or more activity items
     *
     * @param int|array $id
     * @param array $params
     *
     * @return Item
     */
    public function getActivity($id, array $params = []);

    /**
     * Creates a new comment in a collection's item
     *
     * @param string $collection
     * @param mixed $item
     * @param string $message
     *
     * @return Item
     */
    public function createComment($collection, $item, $message);

    /**
     * Updates a comment from a collection's item
     *
     * @param int $id
     * @param string $message
     *
     * @return Item
     */
    public function updateComment($id, $message);

    /**
     * Deletes a comment from a collection's item
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteComment($id);

    /**
     * Creates a collection
     *
     * @param string $name
     * @param array $data
     * @param array $params
     *
     * @return Item
     */
    public function createCollection($name, array $data, array $params = []);

    /**
     * Finds a list of all Collections
     *
     * @param array $params
     *
     * @return ItemCollection
     */
    public function getCollections(array $params = []);

    /**
     * Finds a Collection by name
     *
     * @param string $name
     * @param array $params
     *
     * @return Item
     */
    public function getCollection($name, array $params = []);

    /**
     * Updates a collection
     *
     * @param $name
     * @param array $data
     * @param array $params
     *
     * @return Item
     */
    public function updateCollection($name, array $data, array $params = []);

    /**
     * Deletes a collection
     *
     * @param string $name
     *
     * @return void
     */
    public function deleteCollection($name);

    /**
     * Creates a new Collection Preset
     *
     * @param array $data
     * @param array $params
     *
     * @return Item
     */
    public function createCollectionPresets(array $data, array $params = []);

    /**
     * Finds a list of all Collection Presets
     *
     * @param array $params
     *
     * @return Item
     */
    public function getCollectionPresets(array $params = []);

    /**
     * Find a Collection Preset with the given ID
     *
     * @param int $id
     * @param array $params
     *
     * @return Item
     */
    public function getCollectionPreset($id, array $params = []);

    /**
     * Updates a Collection Preset
     *
     * @param int $id
     * @param array $data
     * @param array $params
     *
     * @return Item
     */
    public function updateCollectionPreset($id, array $data, array $params = []);

    /**
     * Deletes a bookmark
     *
     * @param int $id
     * @param array $params
     *
     * @return Item
     */
    public function deleteCollectionPresets($id, array $params = []);

    /**
     * Creates a field in the given collection
     *
     * @param string $collection
     * @param string $name
     * @param array $data
     * @param array $params
     *
     * @return Item
     */
    public function createField($collection, $name, array $data, array $params = []);

    /**
     * Finds all fields that belongs to a given collection
     *
     * @param string $collection
     * @param array $params
     *
     * @return ItemCollection
     */
    public function getFields($collection, array $params = []);

    /**
     * Finds all fields from all collections
     *
     * @param array $params
     *
     * @return ItemCollection
     */
    public function getAllFields(array $params = []);

    /**
     * Gets the details of a given table's column
     *
     * @param $collection
     * @param $name
     *
     * @return Item
     */
    public function getField($collection, $name);

    /**
     * Updates a field in a given collection
     *
     * @param string $collection
     * @param string $name
     * @param array $data
     * @param array $params
     *
     * @return Item
     */
    public function updateField($collection, $name, array $data, array $params = []);

    /**
     * Deletes a field
     *
     * @param string $collection
     * @param string $name
     *
     * @return void
     */
    public function deleteField($collection, $name);

    /**
     * Fetch Items from a given table
     *
     * @param string $tableName
     * @param array $options
     *
     * @return ItemCollection
     */
    public function getItems($tableName, array $options = []);

    /**
     * Get an entry in a given table by the given ID
     *
     * @param mixed $id
     * @param string $tableName
     * @param array $options
     *
     * @return Item
     */
    public function getItem($tableName, $id, array $options = []);

    /**
     * Gets the list of users
     *
     * @param array $params
     *
     * @return ItemCollection
     */
    public function getUsers(array $params = []);

    /**
     * Gets a user by the given id
     *
     * @param $id
     * @param array $params
     *
     * @return Item
     */
    public function getUser($id, array $params = []);

    /**
     * Gets a list of User groups
     *
     * @return ItemCollection
     */
    public function getRoles();

    /**
     * Gets the information of a given user group
     *
     * @param $groupID
     *
     * @return Item
     */
    public function getRole($groupID);

    /**
     * Get a given group privileges
     *
     * @param $groupID
     *
     * @return ItemCollection
     */
    public function getRolePermissions($groupID);

    /**
     * Gets a list fo files
     *
     * @param array $params - Parameters
     *
     * @return ItemCollection
     */
    public function getFiles(array $params = []);

    /**
     * Gets the information of a given file ID
     *
     * @param $fileID
     *
     * @return Item
     */
    public function getFile($fileID);

    /**
     * Gets all settings
     *
     * @return object
     */
    public function getSettings();

    /**
     * Updates settings in the given collection
     *
     * @param $collection
     * @param $data
     *
     * @return Item
     */
    public function updateSettings($collection, array $data);

    /**
     * Create a new item in the given table name
     *
     * @param $tableName
     * @param array $data
     *
     * @return Item
     */
    public function createItem($tableName, array $data);

    /**
     * Update the item of the given table and id
     *
     * @param $tableName
     * @param $id
     * @param array $data
     *
     * @return mixed
     */
    public function updateItem($tableName, $id, array $data);

    /**
     * Deletes the given item id(s)
     *
     * @param string $tableName
     * @param string|array|Item|ItemCollection $ids
     * @param bool $hard
     *
     * @return int
     */
    public function deleteItem($tableName, $ids, $hard = false);

    /**
     * Creates a new user
     *
     * @param array $data
     *
     * @return Item
     */
    public function createUser(array $data);

    /**
     * Updates the given user id
     *
     * @param $id
     * @param array $data
     *
     * @return mixed
     */
    public function updateUser($id, array $data);

    /**
     * Deletes the given user id(s)
     *
     * @param string|array|Item|ItemCollection $ids
     * @param bool $hard
     *
     * @return int
     */
    public function deleteUser($ids, $hard = false);

    /**
     * Creates a new file
     *
     * @param File $file
     *
     * @return Item
     */
    public function createFile(File $file);

    /**
     * Updates the given file id
     *
     * @param $id
     * @param array|File $data
     *
     * @return mixed
     */
    public function updateFile($id, $data);

    /**
     * Deletes the given file id(s)
     *
     * @param string|array|Item|ItemCollection $ids
     * @param bool $hard
     *
     * @return int
     */
    public function deleteFile($ids, $hard = false);

    /**
     * Creates a new group
     *
     * @param $data
     *
     * @return Item
     */
    public function createRole(array $data);

    /**
     * Creates a new privileges/permissions
     *
     * @param array $data
     *
     * @return Item
     */
    public function createPermissions(array $data);

    /**
     * Deletes a group
     *
     * @param $id
     * @param bool $hard
     *
     * @return Item
     */
    public function deleteRole($id, $hard = false);

    /**
     * Gets a random alphanumeric string
     *
     * @param array $options
     *
     * @return Item
     */
    public function getRandomString(array $options = []);

    /**
     * Gets a hashed value from the given string
     *
     * @param string $string
     * @param array $options
     *
     * @return Item
     */
    public function getHash($string, array $options = []);
}
