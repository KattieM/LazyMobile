<?php

namespace App\Http\Controllers;

use App\Project;
use App\Project_Attending;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Event_Attending;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */





    public function isUserAttending($event, $user){
        $user_loggedin = $user->id;

        $event_attending=Event_Attending::where('user_id', $user_loggedin)->where('event_id', $event->id)->get();

        if($event_attending->isEmpty()){
            $going="not going";
        }

        else{
            $going="going"; //set ungoing button
        }

        return $going;
    }



    public function getProjectsTeams($project){

        $team_id=$project->team->id;
        $team=Project_Attending::where('team_id', $team_id)->get();

        return $team;


    }


    private function getAllEventOrganizers($event){

        $organizer_role = Role::where('project/event', 'event')->where('title', 'organizer')->get()->first();
        $event_attending = Event_Attending::where('event_id', $event->id)->where('role_id', $organizer_role->id)->get()->first();
        return $event_attending->user_id;

    }

    public function returnEventsAndProjects(){

        $user=Auth::user();

        $users=User::take(12)->get();

        $events = Event::orderBy('date', 'desc')->take(4)->get();
        $projects = Project::orderBy('start_date', 'desc')->take(4)->get();
        $button="No button";


        //foreach event check the user's going/not going status
        $goings=array();
        foreach ($events as $event){
            $goings[$event->name]=$this->isUserAttending($event, $user);
            $organizers[$event->name]=$this->getAllEventOrganizers($event);

        }


        $teams=array();
        foreach ($projects as $project){
            $teams[$project->name]= $this->getProjectsTeams($project);
        }



        return response()->json(["projects"=>$projects, "events"=>$events, "users"=>$users, "goings"=>$goings, "teams"=>$teams, "user"=>$user, "organizers"=>$organizers]);


    }

    public function attendEvent(Request $request){

        $msg="";
        $event_id=$request['id'];
        $user_id=Auth::id();
        $role = Role::where('title', 'attendee')->where('project/event', 'event')->get()->first();
        $event_attendings = Event_Attending::where('user_id', $user_id)->where('event_id', $event_id)->get();
        if($event_attendings->isEmpty()){
            Event_Attending::create([ 'event_id' => $event_id, 'role_id' => $role->id, 'user_id' => $user_id]);

        }

        else {
            $msg="You have already attended this event!";
        }


        return response()->json(['msg'=>$msg]);


    }

    public function unattendEvent(Request $request){

        $user_id=Auth::id();
        $role = Role::where('title', 'attendee')->where('project/event','event')->get()->first();
        $event_id=$request['id'];
        $event_attendings=Event_Attending::where('user_id', $user_id)->where('event_id',$event_id)->where('role_id', $role->id)->get()->first();
        $event_attendings->delete();



    }

}
