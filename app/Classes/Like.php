<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 15.04.2017
 * Time: 19:21
 */

namespace App\Classes;

/**
 * object value for like status
 * Class Like
 * @package App\Classes
 */
class Like implements \JsonSerializable
{
    private $status;

    /**
     * Like constructor.
     * @param Boolean null $status
     */
    public function __construct($status = null)
    {
        $this->status = $status;
    }

    /**
     * return like status like boolean
     * true = like, false = dislike, null = null
     *
     * @return boolean|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * return like status
     * 1 = like, -1 = dislike, 0 = null
     *
     * @return int
     */
    public function getStatusLikeInt()
    {
        if ($this->status === true) return 1;
        elseif ($this->status === false) return -1;
        else  return 0;
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->status = unserialize($serialized);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->status;
    }
}