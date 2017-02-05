<?php

namespace common\fixtures\redis;

use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use yii\test\BaseActiveFixture;

/**
 * Class ActiveFixture
 *
 * @property ActiveRecord $modelClass
 * @property string $keyPrefix
 *
 * @package yii\redis
 */
class ActiveFixture extends BaseActiveFixture
{
    /**
     * @var Connection|string $db
     */
    public $db = 'redis';

    public $modelClass;

    public $keyPrefix;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!isset($this->modelClass) && !isset($this->keyPrefix)) {
            throw new InvalidConfigException('Either "modelClass" or "keyPrefix" must be set.');
        }
    }

    public function load()
    {
        parent::load();

        $model = $this->modelClass;

        foreach ($this->data as $row) {
            $pk = $this->getPrimaryKey($row);

            $this->db->executeCommand('RPUSH', [$model::keyPrefix(), $model::buildKey($pk)]);

            $key = $model::keyPrefix() . ':a:' . $model::buildKey($pk);
            // save attributes
            $args = [$key];
            foreach ($row as $attribute => $value) {
                $args[] = $attribute;
                $args[] = $value;
            }
            $this->db->executeCommand('HMSET', $args);
        }
    }

    public function unload()
    {
        $this->db->executeCommand('FLUSHDB');
    }

    protected function getPrimaryKey($row)
    {
        $pk = [];
        $model = $this->modelClass;

        $keys = $model::primaryKey();
        foreach ($keys as $key) {
            if (isset($row[$key])) {
                $pk[$key] = $row[$key];
            } else {
                $row[$key] = $pk[$key] = $this->db->executeCommand('INCR', [$model::keyPrefix() . ':s:' . $key]);
            }
        }

        return $pk;
    }

    /**
     * Returns the fixture data.
     *
     * The default implementation will try to return the fixture data by including the external file specified by [[dataFile]].
     * The file should return an array of data rows (column name => column value), each corresponding to a row in the table.
     *
     * If the data file does not exist, an empty array will be returned.
     *
     * @return array the data rows to be inserted into the database table.
     */
    protected function getData()
    {
        if ($this->dataFile === null) {
            $class = new \ReflectionClass($this->modelClass);

            $dataFile = dirname($class->getFileName()) . '/data/' . Inflector::camel2id($class->getShortName()) . '.php';

            return is_file($dataFile) ? require($dataFile) : [];
        } else {
            return parent::getData();
        }
    }


}