<?php

class ReleasesModel extends RedRacerBaseModel
{
	/**
	 * @var        Doctrine_Tree_NestedSet
	 */
	protected $tree = null;

	/**
	 * Create a new query
	 *
	 * @return     Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query();
		$query->from('Release release')
			->select('release.*, resource.*, files.*, release_dependencies.id, resource_dependencies.id, files_dependencies.id')
			->innerJoin('release.resource AS resource')
			->leftJoin('release.files AS files ON release.id = files.release_id AND files.lft != 1 INDEXBY files.id')
			->leftJoin('release.dependencies AS release_dependencies INDEXBY release_dependencies.id')
			->leftJoin('resource.dependencies AS resource_dependencies INDEXBY resource_dependencies.id')
			->leftJoin('files.dependencies AS files_dependencies INDEXBY files_dependencies.id')
			->addOrderBy('files.lft ASC');

		return $query;
	}

	/**
	 * findOneByIdAndIdent
	 *
	 * @param      int id
	 * @param      string ident
	 *
	 * @return     Release
	 */
	public function findOneByIdAndIdent($id, $ident)
	{
		$query = $this->getQuery();
		$query->where('release.id = ? AND resource.ident = ?', array($id, $ident));
		return $query->fetchOne();
	}

	/**
	 * @return     Doctrine_Tree_NestedSet
	 */
	public function getTree()
	{
		if ($this->tree === null) {
			$this->tree = Doctrine::getTable('File')->getTree();
		}

		return $this->tree;
	}

	/**
	 * @param      id
	 *
	 * @return     File
	 */
	public function getRootByReleaseId($release_id)
	{
		$tree = $this->getTree();
		$query = $tree->getBaseQuery();
		$query->addWhere($tree->getBaseAlias() . '.lft = 1 AND ' . $tree->getBaseAlias() . '.release_id = ?', $release_id);

		return $query->fetchOne();
	}

	/**
	 * @param      id
	 * @param      string
	 * @param      mixed
	 *
	 * @return     File
	 */
	public function getOneNodeByReleaseIdAndColumn($release_id, $col, $value)
	{
		$tree = $this->getTree();
		$query = $tree->getBaseQuery();

		$query->addWhere($tree->getBaseAlias() . '.release_id = ?', $release_id);
		$query->addWhere($tree->getBaseAlias() . '.' . $col . ' = ?', $value);
		$query->orderBy($tree->getBaseAlias() . '.level');

		return $query->fetchOne();
	}

	public function updateFilesMap($release_id, $base_url, array $map)
	{
		$root = $this->getRootByReleaseId($release_id);
		$fresh = false;

		if (!$root) {
			$fresh = true;
			$root = new File();
			$root['release_id'] = $release_id;
			$root['folder'] = true;
			$root->save();
			$tree = $this->getTree();
			$tree->createRoot($root);
		}

		return $this->updateFiles($base_url, $map, $root, $fresh);
	}

	protected function updateFiles($base_url, array $map, File $parent, $fresh = false)
	{
		$updated = 0;
		$names = array();

		if (!$fresh) {
			$children = $parent->getNode()->getChildren();
			if ($children && count($children)) {
				foreach ($children as $child) {
					$names[$child['name']] = $child;
				}
			}
		}

		if (substr($base_url, -1, 1) != '/') {
			$base_url .= '/';
		}

		foreach ($map as $name => $fields) {
			$url = $base_url . $name;
			if (isset($names[$name])) {
				$fresh = false;

				$node = $names[$name];
				$node->refresh();
				unset($names[$name]);
			} else {
				$fresh = true;

				$node = new File();
				$node['release_id'] = $parent['release_id'];
				$node['name'] = $name;
				$node['folder'] = is_array($fields);
			}
			$node['role'] = $this->detectRole($node, $parent);
			$node['url'] = $url;
			$updated++;

			if ($fresh) {
				$node->getNode()->insertAsLastChildOf($parent);
			} else {
				$node->getNode()->moveAsLastChildOf($parent);
			}
			// $parent->refresh();

			if (is_array($fields) && $node['level'] < 4) {
				$updated += $this->updateFiles($url, $fields, $node, $fresh);
			}
		}

		foreach ($names as $node) {
			$node->delete();
		}
		return $updated;
	}

	public function updateRoles($release_id)
	{
		$root = $this->getRootByReleaseId($release_id);

		if (!$root) {
			return false;
		}
		$parents = array($root);

		foreach ($root->getNode()->getDescendants() as $node) {
			$role = (string) $this->detectRole($node, $parents[$node['level'] - 1]);
			// echo "$node[name] : $role\n";
			if ($role != $node['role']) {
				$node['role'] = $role;
				$node->save();
			}
			$parents[$node['level']] = $node;
		}
		return true;
	}

	public function fetchDependencyMap($release_id)
	{
		$node = $this->getOneNodeByReleaseIdAndColumn($release_id, 'role', 1);

		if (!$node || !$node['url']) {
			return false;
		}

		$params = array();
		if (strpos($node['url'], 'github') !== false) {
			$params['raw'] = 'true';
		}
		$peer = $this->context->getModel('Curl');

		return $peer->fetchJsonFromUrl($node['url'], $params);
	}

	/**
	 * @todo add logging
	 */
	public function resolveDependencyMap($release_id, array $map)
	{
		$created = 0;
		$query = new Doctrine_Query();
		$query->select('file.*, release.*, resource.ident, dependencies.*')
			->from('File file')
			->innerJoin('file.release AS release')
			->innerJoin('release.resource AS resource')
			->leftJoin('file.dependencies AS dependencies')
			->where('file.release_id = ?', $release_id)
			->andWhereIn('file.role', array(2, 3, 4))
			->orderBy('file.lft');

		$files = array();
		$parents = array(false);

		foreach ($query->execute() as $node) {
			$parents[$node['level']] = $node;
			if ($node['folder']) {
				continue;
			}
			$files[$node['name']] = $node;

			if (isset($parents[$node['level'] - 1])) {
				$files[$parents[$node['level'] - 1]['name'] . $node['name']] = $node;
			}
		}

		foreach ($map as $folder_name => $folder) {
			foreach ($folder as $file => $opts) {
				$path = $folder_name . '/' . $file;

				// thanks to aarons weird class naming (XY.ui), can be removed when he changes that
				if (!preg_match('/\.[a-z]{2,4}$/', $file) && !preg_match('/\.ui$/', $file)) {
					$path .= '.js';
				}

				if (!isset($files[$path])) {
					continue;
				}

				$node = $files[$path];

				if (isset($opts['desc'])) {
					$node['description'] = $opts['desc'];
				}
				if (isset($node['dependencies']) && count($node['dependencies'])) {
					$node['dependencies']->delete();
				}

				if (isset($opts['deps'])) {
					$deps = (array) $opts['deps'];
					foreach ($deps as $dep_id => $dep) {
						$resolved = $this->createDependency($dep);
						$resolved['file_id'] = $node['id'];
						$node['dependencies'][] = $resolved;
						$created++;
					}
				}

				if (isset($opts['optional'])) {
					$deps = (array) $opts['optional'];
					foreach ($deps as $dep_id => $dep) {
						$resolved = $this->createDependency($dep);
						$resolved['file_id'] = $node['id'];
						$resolved['optional'] = true;
						$node['dependencies'][] = $resolved;
						$created++;
					}
				}

				if (isset($opts['assets'])) {
					$deps = (array) $opts['assets'];
					foreach ($deps as $dep) {
						$resolved = $this->createDependency($dep);
						$resolved['file_id'] = $node['id'];
						$node['dependencies'][] = $resolved;
						$created++;
					}
				}
				$node->save();
			}
		}
		return $created;
	}

	/**
	 * parseDependency
	 *
	 * @return     Dependency
	 */
	public function createDependency($target)
	{
		$ret = new Dependency();
		$ret['target'] = $target;
		return $ret;
	}


	public function updateDependencyMap($release_id)
	{
		$json = $this->fetchDependencyMap($release_id);
		if ($json) {
			return $this->resolveDependencyMap($release_id, $json);
		}
		return 0;
	}

	protected function detectRole(File $file, File $parent)
	{
		$parent_role = $parent['role'];
		if ($parent_role && in_array($parent_role, array(4, 5, 6, 7, 8, 9))) {
			return $parent_role;
		}

		$name = $file['name'];
		if ((!$parent['level'] || $parent_role == 2) && preg_match('/^scripts?\.json$/i', $name)) {
			return 1;
		}

		if (!$parent['level'] && $file['folder']) {
			if (preg_match('/^(source|src)\\/$/i', $name)) {
				return 2;
			}
			if (preg_match('/^assets?\\/$/i', $name)) {
				return 4;
			}
			if (preg_match('/^tests?\\/$/i', $name)) {
				return 5;
			}
			if (preg_match('/^specs?\\/$/i', $name)) {
				return 6;
			}
			if (preg_match('/^docs?\\/$/i', $name)) {
				return 7;
			}
			if (preg_match('/^demos?\\/$/i', $name)) {
				return 8;
			}
			if (preg_match('/^compat(ibility)?\\/$/i', $name)) {
				return 9;
			}
			return 0;
		}

		if (!$parent['level'] && preg_match('/^readme(\.[a-z]{2,8})?$/i', $name)) {
			return 10;
		}

		if (preg_match('/^licen[sc]e(\.[a-z]{2,8})?$/i', $name)) {
			return 0;
		}

		if ($parent_role == 2 || !$parent_role) {
			if (preg_match('/\.js$/', $name)) {
				// Delayed detect
				if (!$parent_role) {
					$parent['role'] = 2;
					$parent->save();
				}
				return 3;
			}
			if ($parent_role) {
				return 2;
			}
		}

		if (preg_match('/\.(rar|[7g]?zip|gz|tar|arj|ace|jar)$/', $name)) {
			return 11;
		}

		if (preg_match('/\.(css|gif|jpg|png|swf|as|fla|ico|json|html?|svg)$/', $name)) {
			return 4;
		}

		return 0;
	}

}


?>