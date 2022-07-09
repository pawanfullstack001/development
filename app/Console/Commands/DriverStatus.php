<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscriber;
use App\DriverAvailability;
use App\Appuser;

class DriverStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Driver Subscription Update.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $driver = Appuser::where('subscribed',1)->get();
        if(count($driver)>0){
            foreach ($driver as $key => $value) {
                $userdetails['id'] = $value->id;
                $subs = Subscriber::where(['driver_id'=>$userdetails['id'],'expire'=>0])->orderBy('id','DESC')->first();

                if($subs){
                    if($subs->type==2){
                        $data = DriverAvailability::where('subscriber_id',$subs->id)->count();
                        if($data<=$subs->duration){
                            DriverAvailability::firstOrCreate(['driver_id'=>$userdetails['id'],'subscriber_id'=>$subs->id,'date'=>date('Y-m-d')]);
                        }else{
                            DriverAvailability::where('subscriber_id',$subs->id)->update(['status'=>1]);
                            Subscriber::where('id',$subs->id)->update(['expire'=>1]);
                            Appuser::where('id',$value->id)->update(['subscribed'=>0]);
                        }
                    }elseif($subs->type==1){
                        $now = strtotime(date('Y-m-d')); // or your date as well
                        $your_date = strtotime(date('Y-m-d',strtotime($subs->created_at)));
                        $datediff = $now - $your_date;
                        $sss = round($datediff / (60 * 60 * 24));
                        $remaindays = $subs->duration - $sss;
                        if($remaindays<=0){
                            Subscriber::where('id',$subs->id)->update(['expire'=>1]);
                            $userdetails->subscribed = 0;
                        }
                    }
                }
            }
        }
        echo "Done";
    }
}
