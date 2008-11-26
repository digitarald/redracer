<?php

/**
 * RedRacerDoctrineRecord
 *
 * @package    redracer
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedRacerDoctrineRecord extends Doctrine_Record
{

	/**
	 * @var			AgaviContext
	 */
	protected $context = null;

	/**
	 * @var			array
	 */
	protected $_getters = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current AgaviContext instance.
	 */
	public final function getContext()
	{
		if ($this->context === null) {
			$this->context = $this->getTable()->getConnection()->getParam('context', 'org.agavi');
		}

		return $this->context;
	}

  /**
   * toArray
   * returns the record as an array
   *
   * @param boolean $deep - Return also the relations
   * @return array
   */
  public function toArray($deep = false, $prefixKey = false)
  {
  	$ret = parent::toArray($deep, $prefixKey);
		return $ret;
	}

	/**
	 * getIdsByRelations
	 *
	 * Get id array by relations
	 *
	 * @param      Doctrine_Collection Relations
	 * @param      string $col_id
	 */
	protected function getIdsByRelations(Doctrine_Collection $rels, $col_id = 'id')
	{
		$ret = array();
		foreach ($rels as $rel) {
			$ret[] = $rel[$col_id];
		}
		return $ret;
	}

	/**
	 * setRelationsByIds
	 *
	 * Synchronize relations by id array
	 *
	 * @param      Doctrine_Collection Relations
	 * @param      array $rel_ids
	 * @param      string $col_id
	 */
	protected function setRelationsByIds(Doctrine_Collection $rels, array $rel_ids, $col_id, $ref_class)
	{
		foreach ($rels as &$ref) {
			$idx = array_search($ref[$col_id], $rel_ids);

			if ($idx !== false) {
				unset($rel_ids[$idx]);
			} else {
				$ref->delete();
			}
		}

		foreach ($rel_ids as $rel_id) {
			if ($rel_ids === '0') {
				continue;
			}
			$rel = new $ref_class();
			$rel[$col_id] = $rel_id;
			$rels[] = $rel;
		}
	}

}

?>