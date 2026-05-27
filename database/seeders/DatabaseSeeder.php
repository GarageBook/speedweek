<?php

namespace Database\Seeders;

use App\Models\ChecklistItem;
use App\Models\Event;
use App\Models\MailTemplate;
use App\Models\Motorcycle;
use App\Models\Package;
use App\Models\ParticipantChecklistItem;
use App\Models\ParticipantProfile;
use App\Models\ProgrammeItem;
use App\Models\Registration;
use App\Models\TireRequest;
use App\Models\TravelInfo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::create(['name' => 'Speedweek Admin', 'email' => 'admin@speedweek.local', 'password' => Hash::make('password'), 'phone' => '+31000000000', 'is_admin' => true]);
        $rider = User::create(['name' => 'Demo Rider', 'email' => 'rider@speedweek.local', 'password' => Hash::make('password'), 'phone' => '+31600000000']);

        ParticipantProfile::create(['user_id'=>$rider->id,'salutation'=>'Mr','first_name'=>'Demo','last_name'=>'Rider','passport_full_name'=>'Demo Rider','date_of_birth'=>'1990-04-15','country'=>'Netherlands','address'=>'Main Street 1','city'=>'Amsterdam','postal_code'=>'1000AA','id_document_type'=>'passport','emergency_contact_name'=>'Demo Contact','emergency_contact_phone'=>'+31611111111']);

        $event = Event::create(['name'=>'Speedweek 2026','slug'=>'speedweek-2026','starts_at'=>'2026-11-25','ends_at'=>'2026-12-02','final_payment_due_at'=>'2026-10-01','location_name'=>'Almería, Spain','circuit_name'=>'Circuito de Almería','circuit_address'=>'Carretera AL-3102, Tabernas, Almería, Spain','hotel_name'=>'Hotel Punta del Cantal','hotel_address'=>'Av. del Mediterráneo, Mojácar, Almería, Spain','bike_dropoff_location'=>'Speedweek Transport Hub, NL','bike_dropoff_datetime'=>'2026-11-18 10:00:00','return_datetime'=>'2026-12-07 16:00:00','description'=>'Meerdaags circuit-event in Zuid-Spanje met rijden, begeleiding en praktische ontzorging.','status'=>'open']);

        $defs = [
            'full-package'=>['Full Package', 249500, ['circuit_riding_days','hotel_included','breakfast_included','dinner_included','flight_included','motorcycle_transport_included','daily_bus_transfer_included','fuel_included','pitbox_included','tire_service_available','instructor_guidance','transponder_timing','photography','certificate']],
            'independent'=>['Independent', 139500, ['circuit_riding_days','instructor_guidance','transponder_timing','photography','certificate','tire_service_available']],
            'spectator'=>['Spectator', 89500, ['hotel_included','breakfast_included','dinner_included','flight_included','daily_bus_transfer_included','photography']],
        ];
        $labels = ['circuit_riding_days'=>'Circuit riding days','hotel_included'=>'Hotel included','breakfast_included'=>'Breakfast included','dinner_included'=>'Dinner included','flight_included'=>'Flight included','motorcycle_transport_included'=>'Motorcycle transport included','daily_bus_transfer_included'=>'Daily bus transfer included','fuel_included'=>'Fuel included','pitbox_included'=>'Pitbox included','tire_service_available'=>'Tire service available','instructor_guidance'=>'Instructor guidance','transponder_timing'=>'Transponder/timing','photography'=>'Photography','certificate'=>'Certificate'];
        $packages=[]; $sort=1;
        foreach ($defs as $slug => [$name,$price,$included]) {
            $package = Package::create(['event_id'=>$event->id,'name'=>$name,'slug'=>$slug,'description'=>$name.' voor Speedweek 2026','price_cents'=>$price,'deposit_percentage'=>30,'sort_order'=>$sort++,'is_active'=>true]);
            foreach ($labels as $key=>$label) $package->features()->create(['key'=>$key,'label'=>$label,'included'=>in_array($key,$included, true)]);
            $packages[$slug]=$package;
        }

        $checklists = [
            ['travel','Check flight times'], ['motorcycle','Prepare motorcycle for transport'], ['gear','Pack race suit and helmet'], ['documents','Check passport validity'], ['tires','Confirm tire plan'], ['payment','Pay deposit invoice'],
        ];
        foreach ($checklists as $i => [$category,$title]) ChecklistItem::create(['event_id'=>$event->id,'title'=>$title,'category'=>$category,'sort_order'=>$i+1,'is_default'=>true]);

        $items = [
            ['Travel day to Almería','travel','2026-11-25 08:00','2026-11-25 20:00','Schiphol / Almería'],
            ['Welcome briefing','briefing','2026-11-25 21:00','2026-11-25 22:00','Hotel Punta del Cantal'],
            ['Track day 1','track_session','2026-11-26 09:00','2026-11-26 17:00','Circuito de Almería'],
            ['Track day 2','track_session','2026-11-27 09:00','2026-11-27 17:00','Circuito de Almería'],
            ['Rest day','rest_day','2026-11-28 10:00','2026-11-28 18:00','Mojácar'],
            ['Technical briefing','technical','2026-11-29 08:15','2026-11-29 08:45','Pitlane'],
            ['Track day 3','track_session','2026-11-29 09:00','2026-11-29 17:00','Circuito de Almería'],
            ['Group dinner','meal','2026-11-29 20:00','2026-11-29 22:30','Hotel Punta del Cantal'],
            ['Track day 4','track_session','2026-11-30 09:00','2026-11-30 17:00','Circuito de Almería'],
            ['Return travel','travel','2026-12-02 08:00','2026-12-02 20:00','Almería / Schiphol'],
        ];
        foreach ($items as [$title,$type,$start,$end,$location]) ProgrammeItem::create(['event_id'=>$event->id,'title'=>$title,'type'=>$type,'starts_at'=>$start,'ends_at'=>$end,'location'=>$location]);

        foreach ([['Onboarding','Welcome to {{ event }}','Hi {{ name }}, welcome to {{ event }} with {{ package }}.','onboarding'],['Payment reminder','Payment reminder for {{ event }}','Hi {{ name }}, please check your open Speedweek payment.','payment'],['Practical info','Practical information {{ event }}','Hi {{ name }}, here are the practical details for {{ event }}.','general']] as [$name,$subject,$body,$type]) MailTemplate::create(compact('name','subject','body','type') + ['is_active'=>true]);

        $registration = Registration::create(['user_id'=>$rider->id,'event_id'=>$event->id,'package_id'=>$packages['full-package']->id,'status'=>'confirmed','payment_status'=>'deposit_paid','single_room_requested'=>false,'checked_luggage_requested'=>true,'total_amount_cents'=>$packages['full-package']->price_cents,'deposit_amount_cents'=>74850,'confirmed_at'=>now()]);
        $motorcycle = Motorcycle::create(['user_id'=>$rider->id,'registration_id'=>$registration->id,'brand'=>'Yamaha','model'=>'R1','year'=>2022,'license_plate'=>'MTS-01','front_tire_size'=>'120/70 ZR17','rear_tire_size'=>'200/55 ZR17']);
        TireRequest::create(['registration_id'=>$registration->id,'motorcycle_id'=>$motorcycle->id,'brings_own_tires'=>true,'own_extra_sets_count'=>1,'wants_tire_service'=>true,'preferred_brand'=>'Pirelli','front_tire_size'=>'120/70 ZR17','rear_tire_size'=>'200/55 ZR17']);
        TravelInfo::create(['registration_id'=>$registration->id,'outbound_flight_number'=>'HV0001','outbound_departure_airport'=>'AMS','outbound_departure_at'=>'2026-11-25 08:30','outbound_arrival_airport'=>'LEI','outbound_arrival_at'=>'2026-11-25 11:30','return_flight_number'=>'HV0002','return_departure_airport'=>'LEI','return_departure_at'=>'2026-12-02 15:00','return_arrival_airport'=>'AMS','return_arrival_at'=>'2026-12-02 18:00','hotel_room_number'=>'TBD','bus_group'=>'A']);
        ChecklistItem::where('event_id',$event->id)->get()->each(fn ($item) => ParticipantChecklistItem::create(['registration_id'=>$registration->id,'checklist_item_id'=>$item->id]));
    }
}
