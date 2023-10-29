<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\SequenceBill;
use App\Models\Shope;
use App\Models\StaffScheduling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DateTime;
use PDF;
use Illuminate\Support\Facades\Artisan;


class CheckBox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $Sequences =  SequenceBill::where('Status', 1)->get();
        foreach ($Sequences as $key => $Sequence) {
            $startDay =  $Sequence->Scheduling->Start_Date;
            $endDay =  $Sequence->Scheduling->End_Date;
            $afterOneHoure = date('H:i', strtotime($endDay . ' + 1 hours'));
            $endDay  = date('H:i', strtotime($endDay . ''));
            $startDay =  date('H:i', strtotime($startDay . ''));
            $curr =  \Carbon\Carbon::now()->format('H:i');



            $diffShift = $this->getTimeDiff($startDay, $endDay);
            $diffShiftAfterOnehour = $this->getTimeDiff($startDay, $afterOneHoure);
            $diffCurrent = $this->getTimeDiff($startDay, $curr);
            if ($diffCurrent >= $diffShift && $diffCurrent > $diffShiftAfterOnehour) {

                // Start Close 

                $lastShift = StaffScheduling::where(['branch_id' =>  $Sequence->Scheduling->branch_id])->where('inventory_Officer_id', '!=', null)->orderBy('shift', 'desc')->first();
                // SequenceBill::where('id', $Sequence->id)->update([
                //     'Status' => 2
                // ]);
                $date = new DateTime('+1 day');
                $OneDay = $date->format('Y-m-d');
                $date = new DateTime('-1 day');
                $Day = $date->format('Y-m-d');
                if ($lastShift != null) {
                    if ($lastShift->id == $Sequence->schedule_id) {
                        $Box =   SequenceBill::where(['branch_id' =>  $Sequence->branch_id,])
                            ->whereBetween(
                                'Start_Date',
                                [
                                    $Day, $OneDay
                                ]
                            )
                            // ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
                            ->orderBy('created_at', 'DESC')->get();

                        $Incoming = [];
                        foreach ($Box as $key => $seq) {
                            $bills = Bill::where(['sequence_id' => $seq->id])->where('Status', '<>', 2)->get();
                            foreach ($bills as $key => $bill) {
                                if (array_key_exists($seq->id, $Incoming)) {
                                    $Incoming[$seq->id] +=  $bill->total;
                                } else {
                                    $Incoming[$seq->id] =  $bill->total;
                                }
                            }
                        }

                        $mailData   = ['Box' => $Box, 'Incoming' => $Incoming];
                        $owner  = Shope::where('id', $Sequence->Branch->shope_id)->first();

                        $mailData   = ['Box' => $Box, 'Incoming' => $Incoming, 'owner' => $owner->Owner];
                        $pdf = PDF::loadView('emails.afmail', $mailData)->setOptions(['defaultFont' => 'sans-serif']);

                        \Mail::send('emails.afmail', $mailData, function ($message) use ($owner, $pdf) {
                            $message->to(/*$owner->Owner->email */"nsh-alharbi@outlook.com", $owner->Owner->name)
                                ->subject("ملخص دوام يوم " . now())
                                ->attachData($pdf->output(), 'ملخص دوام يوم ' . now() . ".pdf");
                        });
                    }
                }


                // End Close 




            } else {

                $date = new DateTime('+1 day');
                $OneDay = $date->format('Y-m-d');
                $date = new DateTime('-1 day');
                $Day = $date->format('Y-m-d');
                $Box =   SequenceBill::where(['branch_id' =>  $Sequence->branch_id,])
                    ->whereBetween(
                        'Start_Date',
                        [
                            $Day, $OneDay
                        ]
                    )
                    // ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
                    ->orderBy('created_at', 'DESC')->get();

                $Incoming = [];
                foreach ($Box as $key => $seq) {
                    $bills = Bill::where(['sequence_id' => $seq->id])->where('Status', '<>', 2)->get();
                    foreach ($bills as $key => $bill) {
                        if (array_key_exists($seq->id, $Incoming)) {
                            $Incoming[$seq->id] +=  $bill->total;
                        } else {
                            $Incoming[$seq->id] =  $bill->total;
                        }
                    }
                }

                $mailData   = ['Box' => $Box, 'Incoming' => $Incoming];
                $owner  = Shope::where('id', $Sequence->Branch->shope_id)->first();

                $mailData   = ['Box' => $Box, 'Incoming' => $Incoming, 'owner' => $owner->Owner];
                $pdf = PDF::loadView('emails.afmail', $mailData)->setOptions(['defaultFont' => 'sans-serif']);

                \Mail::send('emails.afmail', $mailData, function ($message) use ($owner, $pdf) {
                    $message->to(/*$owner->Owner->email */"nsh-alharbi@outlook.com", $owner->Owner->name)
                        ->subject("ملخص دوام يوم " . now())
                        ->attachData($pdf->output(), 'ملخص دوام يوم ' . now() . ".pdf");
                });
            }
        }
    }
    function getTimeDiff($dtime, $atime)
    {
        $nextDay = $dtime > $atime ? 1 : 0;
        $dep = explode(':', $dtime);
        $arr = explode(':', $atime);
        $diff = abs(mktime($dep[0], $dep[1], 0, date('n'), date('j'), date('y')) - mktime($arr[0], $arr[1], 0, date('n'), date('j') + $nextDay, date('y')));
        $hours = floor($diff / (60 * 60));
        $mins = floor(($diff - ($hours * 60 * 60)) / (60));
        $secs = floor(($diff - (($hours * 60 * 60) + ($mins * 60))));
        if (strlen($hours) < 2) {
            $hours = "0" . $hours;
        }
        if (strlen($mins) < 2) {
            $mins = "0" . $mins;
        }
        if (strlen($secs) < 2) {
            $secs = "0" . $secs;
        }
        return $hours;
    }
}
