<?php


namespace Fabs\Rest\Utilities;

class Dictionary
{
    /**
     * @var array|\IteratorAggregate|\Iterator
     */
    private $source = null;

    /**
     * Dictionary constructor.
     * @param array|\IteratorAggregate|\Iterator
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @param array|\IteratorAggregate|\Iterator $source
     * @return Dictionary
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return array|\Iterator|\IteratorAggregate
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $key
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function has($key)
    {
        return array_key_exists($this->prepareKey($key), $this->source);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->source[$this->prepareKey($key)];
        }

        return $default;
    }

    /**
     * @param string $key
     * @param int $default
     * @return int
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getInt($key, $default = 0)
    {
        return (int)$this->get($key, $default);
    }

    public function getBoolean($key, $default = false)
    {
        return $this->filter($key, $default, FILTER_VALIDATE_BOOLEAN);
    }

    public function filter($key, $default, $filter, $options = [])
    {
        $value = $this->get($key, $default);

        if (!is_array($options) && $options) {
            $options = ['flags' => $options];
        }

        if (is_array($value) && (!array_key_exists('flags', $options) || $options['flags'] === null)) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }

    /**
     * @param string $key
     * @return string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function prepareKey($key)
    {
        return $key;
    }
}