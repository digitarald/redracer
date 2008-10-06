<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2008 the Agavi Project.                                |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * Manages Phing-based events.
 *
 * @package    agavi
 * @subpackage build
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      1.0.0
 *
 * @version    $Id: AgaviPhingEventDispatcher.class.php 2596 2008-07-09 10:15:10Z impl $
 */
final class AgaviPhingEventDispatcher extends AgaviEventDispatcher implements BuildListener
{
	protected $project = null;
	protected $targetListeners = array();
	protected $taskListeners = array();
	protected $messageListeners = array();
	
	/**
	 * Creates a new event dispatcher.
	 *
	 * @param      Project The project that governs the dispatcher.
	 */
	public function __construct(Project $project)
	{
		$this->project = $project;
		$this->project->addBuildListener($this);
	}
	
	/**
	 * Adds a new target listener.
	 *
	 * @param      AgaviIPhingTargetListener The target listener.
	 */
	public function addTargetListener(AgaviIPhingTargetListener $listener)
	{
		$this->targetListeners[] = $listener;
	}
	
	/**
	 * Adds a new task listener.
	 *
	 * @param      AgaviIPhingTaskListener The task listener.
	 */
	public function addTaskListener(AgaviIPhingTaskListener $listener)
	{
		$this->taskListeners[] = $listener;
	}
	
	/**
	 * Adds a new message listener.
	 *
	 * @param      AgaviIPhingMessageListener The message listener.
	 */
	public function addMessageListener(AgaviIPhingMessageListener $listener)
	{
		$this->messageListeners[] = $listener;
	}
	
	/**
	 * Handles the Phing build started event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function buildStarted(BuildEvent $phingEvent)
	{
		
	}
	
	/**
	 * Handles the Phing build finished event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function buildFinished(BuildEvent $phingEvent)
	{
		
	}
	
	/**
	 * Handles the Phing target started event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function targetStarted(BuildEvent $phingEvent)
	{
		$event = new AgaviPhingTargetEvent();
		$event->setSource($phingEvent->getTarget());
		$event->setTarget($phingEvent->getTarget());
		$event->setProject($phingEvent->getProject());
		
		foreach($this->targetListeners as $listener)
		{
			$listener->targetEntered($event);
		}
	}
	
	/**
	 * Handles the Phing target finished event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function targetFinished(BuildEvent $phingEvent)
	{
		$event = new AgaviPhingTargetEvent();
		$event->setSource($phingEvent->getTarget());
		$event->setTarget($phingEvent->getTarget());
		$event->setProject($phingEvent->getProject());
		
		foreach($this->targetListeners as $listener)
		{
			$listener->targetLeft($event);
		}
	}
	
	/**
	 * Handles the Phing task started event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function taskStarted(BuildEvent $phingEvent)
	{
		$event = new AgaviPhingTaskEvent();
		$event->setSource($phingEvent->getTask());
		$event->setTask($phingEvent->getTask());
		if($phingEvent->getTask()->getOwningTarget() !== null) {
			$event->setTarget($phingEvent->getTask()->getOwningTarget());
		}
		$event->setProject($phingEvent->getProject());
		
		foreach($this->taskListeners as $listener)
		{
			$listener->taskEntered($event);
		}
	}
	
	/**
	 * Handles the Phing task finished event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function taskFinished(BuildEvent $phingEvent)
	{
		$event = new AgaviPhingTaskEvent();
		$event->setSource($phingEvent->getTask());
		$event->setTask($phingEvent->getTask());
		if($phingEvent->getTask()->getOwningTarget() !== null) {
			$event->setTarget($phingEvent->getTask()->getOwningTarget());
		}
		$event->setProject($phingEvent->getProject());
		
		foreach($this->taskListeners as $listener)
		{
			$listener->taskLeft($event);
		}
	}
	
	/**
	 * Handles the Phing message logged event.
	 *
	 * @param       BuildEvent The Phing build event.
	 */
	public function messageLogged(BuildEvent $phingEvent)
	{
		$event = new AgaviPhingMessageEvent();
		$event->setSource($phingEvent->getSource());
		$event->setMessage($phingEvent->getMessage());
		$event->setPriority($phingEvent->getPriority());
		$event->setProject($phingEvent->getProject());
		
		foreach($this->messageListeners as $listener)
		{
			$listener->messageReported($event);
		}
	}
}

?>
