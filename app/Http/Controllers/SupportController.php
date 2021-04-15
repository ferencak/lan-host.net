<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class SupportController extends Controller
{


    public $isError = false;
    private $languageController;

    public function __construct()
    {

      $applicationController = new ApplicationController();
      $this->languageController = $applicationController->languageController;
      $this->languageController->page('support');

    }

	  /**
    * Create ticket
    *
    * @param int $request
    * @return data
    */
  	public function createTicket($request)
  	{

  		$serviceController = new ServiceController();


  		$this->request = (object) $request;
  		$allowed_problems = array('1_1', '2_1', '3_1', '4_1', '5_1', '6_1', '7_1', '8_1', '9_1', '1_2', '2_2', '3_2', '4_2', '5_2', '6_2', '7_2', '8_2', '9_2');
  		$support = DB::table('support')->get();

  		$support->each(function($ticket){
  			$data = json_decode($ticket->data, true);
  		});

  		$unfilled = [];

  		if(!$this->request->ticket_server == "0"){
  			if(!$serviceController->checkOwner($_SESSION['user'], $this->request->ticket_server)){
  				echo throw_alert($this->languageController->fromFile('alert', 'danger'), $this->languageController->get('support_controller->owner_does_not_own_that_server'), 'danger');
              	$this->isError = true;
  			}else{
  				$server = $this->request->ticket_server;
  			}
  		}else{
  			$server = "0";
  		}

  		if(!in_array($this->request->ticket_problem_type, $allowed_problems)){
  			$unfilled[] = $this->languageController->get('support_controller->ticket_issue');
  		}

  		if(empty($this->request->ticket_name)){
  			$unfilled[] = $this->languageController->get('support_controller->ticket_name');
  		}

  		if(empty($this->request->ticket_text)){
  			$unfilled[] = "popis problému";
  		}

  		if(count($unfilled) > 0){
          $error_text = "{0} [{1}] {2}";
          for($i=0;$i<count($unfilled);$i++){
              if(count($unfilled) > 1){
                  $error_text = str_replace('{0}', $this->languageController->get('support_controller->these_rows'), $error_text);
                  $error_text = str_replace('{2}', $this->languageController->get('support_controller->are_not_filled'), $error_text);
              }else{
                  $error_text = str_replace('{0}', $this->languageController->get('support_controller->this_rows'), $error_text);
                  $error_text = str_replace('{2}', $this->languageController->get('support_controller->is_not_filled'), $error_text);
              }
              $error_text = str_replace('{1}', implode(', ', $unfilled), $error_text);
          }
          echo throw_alert($this->languageController->fromFile('alert','danger'), $error_text, 'danger');
          $this->isError = true;
      }


      $structure = json_encode([
          'ticket_data' => [
              'name' => $this->request->ticket_name,
              'text' => $this->request->ticket_text,
              'problem_type' => $this->request->ticket_problem_type,
              'server' => $server,
              'creator' => $_SESSION['user']->id,
              'time_created' => date('d.m.Y H:i')
          ],
          'ticket_status' => [
          	'status' => "opened",
          	'last_answer' => "client",
          	'last_answer_date_time' => date('d.m.Y H:i:s')
          ]
      ]);

      if($this->isError == false){
          DB::table('support')->insert(['data' => $structure], ['created_at' => date('d.m.Y H:i:s')]);
          echo throw_alert($this->languageController->fromFile('alert','success'), $this->languageController->get('support_controller->ticket_created'), 'success');
      }

  	}

	  /**
    * Get all tickets
    *
    * @return data
    */
    public function getTickets($for = "client")
    {
      if($for == "support"){
    	 return DB::table('support')->orderBy("id", "DESC")->get();
      }else{
       return DB::table('support')->where('data->ticket_data->creator', '=', \App\Http\Controllers\ClientController::getId())->orderBy("id", "DESC")->get();

      }

    }

    /**
    * Get last answer of ticket
    *
    * @param int $ticket_id
    * @return data
    */
    public function getLastAnswer($ticket_id)
    {

    	$data = json_decode(DB::table('support')->where('id', '=', $ticket_id)->get());

    	$data_prop = json_decode($data[0]->data);
  		switch($data_prop->ticket_status->last_answer) {
  			case 'client':
  				$last_answer = $this->languageController->get('support_controller->you');
  			break;
  			case 'support':
  				$last_answer = $this->languageController->get('support_controller->support');
  			break;
  		}
    	return $data_prop->ticket_status->last_answer_date_time.' '.$last_answer;

    }

    /**
    * Get status of ticket
    *
    * @param int $ticket_id
    * @return data
    */
    public function getStatus($ticket_id, $response = "return", $for = "client")
    {

    	$data = json_decode(DB::table('support')->where('id', '=', $ticket_id)->get());

    	$data_prop = json_decode($data[0]->data);
  		switch($data_prop->ticket_status->status) {
  			case 'opened':
  				$label = '<span class="label label-success label-small">' . $this->languageController->get('support_controller->opened') . '</span>';
  			break;
  			case 'closed':
  				$label = '<span class="label label-danger label-small">' . $this->languageController->get('support_controller->closed') . '</span>';
  			break;
  			case 'solved':
  				$label = '<span class="label label-success label-small">' . $this->languageController->get('support_controller->resolved') . '</span>';
  			break;
  			case 'in_solution':
  				$label = '<span class="label label-info label-small">' . $this->languageController->get('support_controller->resolving') . '</span>';
  			break;
  			case 'waiting_client':
          if($for == "support"){
  				  $label = '<span class="label label-warning label-small">' . $this->languageController->get('support_controller->waiting_for_user') . '</span>';
          }else{
            $label = '<span class="label label-warning label-small">'. $this->languageController->get('support_controller->waiting_for_you') .'</span>';
          }
  			break;
  			case 'waiting_support':
          if($for == "support"){
  				  $label = '<span class="label label-warning label-small">' . $this->languageController->get('support_controller->waiting_for_support') . '</span>';
          }else{
            $label = '<span class="label label-warning label-small">'. $this->languageController->get('support_controller->waiting_for_you') .'</span>';
          }
  			break;
  			default:
  				$label = '<span class="label label-success">' . $this->languageController->get('support_controller->opened') . '</span>';
  			break;
  		}
      if($response == "print"){
        print $label;
      }else{
        return $label;
      }

    }

    /**
    * Get an data from json
    *
    * @param int $id
    * @param string $selector
    * @param string $data
    * @return Data
    */ 
    public function get($id)
    {

    	return DB::table('support')->where('id', '=', $id)->get()[0];

    }

    /**
    * Check ticket owner
    *
    * @param int $client_id
    * @param int $ticket_id
    * @return Data
    */ 
    public function checkOwner($client_id, $ticket_id)
    {

        if(\App\Support::where([['data->ticket_data->creator', '=', $client_id], ['id', '=', $ticket_id]])){
            return "true";
        }else{
            return "false";
        }

    }

    /**
    * Add reply to ticket
    *
    * @param int $ticket_id
    * @param string $request
    * @param string $from
    * @return Data
    */ 
    public function addReply($ticket_id, $request, $from)
    {
      $this->request = (object) $request;

      $unfilled = [];

      if(empty($this->request->reply_message)){
          $unfilled[] = "odpověď ticketu";
      }

      if(count($unfilled) > 0){
          $error_text = "{0} [{1}] {2}";
          for($i=0;$i<count($unfilled);$i++){
              if(count($unfilled) > 1){
                  $error_text = str_replace('{0}', $this->languageController->get('support_controller->these_rows'), $error_text);
                  $error_text = str_replace('{2}', $this->languageController->get('support_controller->are_not_filled'), $error_text);
              }else{
                  $error_text = str_replace('{0}', $this->languageController->get('support_controller->this_rows'), $error_text);
                  $error_text = str_replace('{2}', $this->languageController->get('support_controller->is_not_filled'), $error_text);
              }
              $error_text = str_replace('{1}', implode(', ', $unfilled), $error_text);
          }
          echo throw_alert('Nastala chyba!', $error_text, 'danger');
          $this->isError = true;
      }


      $structure = json_encode([
          'reply_data' => [
              'ticket_id' => $ticket_id,
              'message' => $this->request->reply_message,
              'time_created' => date('d.m.Y H:i:s'),
              'from' => $from,
              'creator' => $_SESSION['user']->id
          ]
      ]);

      if($this->isError == false){
          DB::table('support_replies')->insert(['data' => $structure]);
          echo throw_alert($this->languageController->fromFile('alert','success'), $this->languageController->get('support_controller->ticket_reply_sent'), 'success');
      }


      $ticket_structure = json_decode($this->get($ticket_id)->data, true);
      if($from == "client"){
      	$ticket_structure['ticket_status']['status'] = 'waiting_support';
      }else{
				$ticket_structure['ticket_status']['status'] = 'waiting_client';
      }

      $ticket_structure['ticket_status']['last_answer'] = $from;
      $ticket_structure['ticket_status']['last_answer_date_time'] = date('d.m.Y H:i:s');

      \App\Support::where('id', '=', $ticket_id)->update(['data' => json_encode($ticket_structure)]);
      if($from == "support"){
        redirect_to('/administration/support/solve/' . $ticket_id, 3);
      }else{
        redirect_to('/support/solve/' . $ticket_id, 3);
      }

    }

    /**
    * Get all ticket replies by id
    *
    * @param int $ticket_id
    * @return Data
    */ 
    public function getReplies($ticket_id)
    {

      return DB::table('support_replies')->where('data->reply_data->ticket_id', '=', $ticket_id)->orderBy("id", "DESC")->get();

    }

    /**
    * Update ticket status
    *
    * @param int $ticket_id
    * @param string $status
    * @return Data
    */ 
    public function updateStatus($ticket_id, $status)
    {
      $ticket_structure = json_decode($this->get($ticket_id)->data, true);

      $ticket_structure['ticket_status']['status'] = $status;

      \App\Support::where('id', '=', $ticket_id)->update(['data' => json_encode($ticket_structure)]);

      echo throw_alert($this->languageController->fromFile('alert','success'), $this->languageController->get('status_updated'), 'success');

      redirect_to('/administration/support/solve/' . $ticket_id, 3);
    }
}
