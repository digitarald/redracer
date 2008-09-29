<?php
/*
 *  $Id: Db.php 4615 2008-07-02 03:26:56Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Database cache driver
 *
 * @package     Doctrine
 * @subpackage  Cache
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 4615 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Cache_Db extends Doctrine_Cache_Driver implements Countable
{
    /**
     * Configure Database cache driver. Specify instance of Doctrine_Connection
     * and tableName to store cache in
     *
     * @param array $_options      an array of options
     */
    public function __construct($options) 
    {
        if ( ! isset($options['connection']) || 
             ! ($options['connection'] instanceof Doctrine_Connection)) {

            throw new Doctrine_Cache_Exception('Connection option not set.');
        }
        
        if ( ! isset($options['tableName']) ||
             ! is_string($options['tableName'])) {
             
             throw new Doctrine_Cache_Exception('Table name option not set.');
        }
        

        $this->_options = $options;
    }

    /**
     * Get the connection object associated with this cache driver
     *
     * @return Doctrine_Connection $connection
     */
    public function getConnection() 
    {
        return $this->_options['connection'];
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else).
     *
     * @param string $id cache id
     * @param boolean $testCacheValidity        if set to false, the cache validity won't be tested
     * @return string cached datas (or false)
     */
    public function fetch($id, $testCacheValidity = true)
    {
        $sql = 'SELECT data, expire FROM ' . $this->_options['tableName']
             . ' WHERE id = ?';

        if ($testCacheValidity) {
            $sql .= ' AND (expire=0 OR expire > ' . time() . ')';
        }

        $result = $this->getConnection()->fetchAssoc($sql, array($id));
        
        if ( ! isset($result[0])) {
            return false;
        }
        
        return unserialize($result[0]['data']);
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param string $id cache id
     * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function contains($id) 
    {
        $sql = 'SELECT expire FROM ' . $this->_options['tableName']
             . ' WHERE id = ? AND (expire=0 OR expire > ' . time() . ')';

        return $this->getConnection()->fetchOne($sql, array($id));
    }

    /**
     * Save some string datas into a cache record
     *
     * Note : $data is always saved as a string
     *
     * @param string $id        cache id
     * @param string $data      data to cache
     * @param int $lifeTime     if != false, set a specific lifetime for this cache record (null => infinite lifeTime)
     * @return boolean true if no problem
     */
    public function save($id, $data, $lifeTime = false)
    {
        $sql = 'INSERT INTO ' . $this->_options['tableName']
             . ' (id, data, expire) VALUES (?, ?, ?)';
        
        if ($lifeTime) {
            $expire = time() + $lifeTime;
        } else {
            $expire = 0;
        }
        
        $params = array($id, serialize($data), $expire);

        return (bool) $this->getConnection()->exec($sql, $params);
    }

    /**
     * Remove a cache record
     * 
     * @param string $id cache id
     * @return boolean true if no problem
     */
    public function delete($id) 
    {
        $sql = 'DELETE FROM ' . $this->_options['tableName'] . ' WHERE id = ?';

        return (bool) $this->getConnection()->exec($sql, array($id));
    }

    /**
     * Removes all cache records
     *
     * $return bool true on success, false on failure
     */
    public function deleteAll()
    {
        $sql = 'DELETE FROM ' . $this->_options['tableName'];
        
        return (bool) $this->getConnection()->exec($sql);
    }

    /**
     * count
     * returns the number of cached elements
     *
     * @return integer
     */
    public function count()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_options['tableName'];
        
        return (int) $this->getConnection()->fetchOne($sql);
    }

    /**
     * Create the cache table
     *
     * @return void
     */
    public function createTable()
    {
        $name = $this->_options['tableName'];
        
        $fields = array(
            'id' => array(
                'type'   => 'string',
                'length' => 255
            ),
            'data' => array(
                'type'    => 'blob'
            ),
            'expire' => array(
                'type'    => 'timestamp'
            )
        );
        
        $options = array(
            'primary' => array('id')
        );
        
        $this->getConnection()->export->createTable($name, $fields, $options);
    }
}