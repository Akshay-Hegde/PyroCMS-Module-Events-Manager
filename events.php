<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Events Manager Events
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */
class Events_Events_manager {
    
    protected $ci;
    
    public function __construct()
    {
        $this->ci =& get_instance();

		$this->ci->load->model('search/search_index_m');
        
        Events::register('streams_post_insert_entry', array($this, 'index_entry'));
		Events::register('streams_post_update_entry', array($this, 'update_index'));

		Events::register('whatever', array($this, 'custom'));
    }
    
    public function index_entry($trigger_data)
    {
		$event = (object) $trigger_data;
		
		if($event->stream->stream_namespace == 'philsquare_events_manager' && $event->stream->stream_slug == 'events')
		{
			$event_data = (object) $trigger_data['insert_data'];

			// @todo Need to add keywords
			$this->ci->search_index_m->index(
			    'philsquare_events_manager',
			    'event',
			    'events',
			    $event->entry_id,
			    'events_manager/event/' . date('Y/m/d/', strtotime($event_data->start)) . $event_data->slug,
			    $event_data->title,
			    $event_data->description,
			    array(
			        'cp_edit_uri'    => 'admin/events_manager/form/' . $event->entry_id,
			        'cp_delete_uri'  => 'admin/events_manager/delete/' . $event->entry_id
			    )
			);
		}
		
    }

	public function update_index($trigger_data)
	{
		$event = (object) $trigger_data;
		
		if($event->stream->stream_namespace == 'philsquare_events_manager' && $event->stream->stream_slug == 'events')
		{
			$event_data = (object) $trigger_data['update_data'];

			// @todo Figure out an better way to just update the search index entry
			$this->ci->search_index_m->drop_index('philsquare_events_manager', 'event', $event->entry_id);

			// @todo Need to add keywords
			$this->ci->search_index_m->index(
			    'philsquare_events_manager',
			    'event',
			    'events',
			    $event->entry_id,
			    'events_manager/event/' . date('Y/m/d/', strtotime($event_data->start)) . $event_data->slug,
			    $event_data->title,
			    $event_data->description,
			    array(
			        'cp_edit_uri'    => 'admin/events_manager/form/' . $event->entry_id,
			        'cp_delete_uri'  => 'admin/events_manager/delete/' . $event->entry_id
			    )
			);
		}
	}

	public function custom()
	{
		// Custom trigger set with Events::trigger('whatever')
	}
}
/* End of file events.php */