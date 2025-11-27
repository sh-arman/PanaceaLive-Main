<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Panacea\Http\Requests;
use Panacea\Http\Controllers\Controller;
use Panacea\Http\Controllers\NeuralNetworkController;
use Panacea\Prob_model;
use Panacea\Prob_model_action;

class ProbabilisticModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query_selector = "SELECT COUNT(check_history.id) as count, date(check_history.created_at) as check_date  ";
        $query_extension = "FROM check_history,code,print_order,medicine
                  WHERE check_history.code = code.code
                  AND code.status = print_order.id
                  AND print_order.medicine_id = medicine.id
                  AND (remarks='verified first time'
                  OR remarks='already verified')
                  AND print_order.id not in ('1','16','31','33','34','68','79','127','134')
                  AND phone_number not in ('8801675430523','8801674914686','8801844147757','8801676291391','8801847068188','8801818614154','8801881036730','8801551061185','8801820555512',
                  '8801924380281','8801759939863','8801629590549','8801844147741')
                  AND check_history.code not in ('S4C6HH','DMQZ3S')
                  AND expiry_date >= '2017-01-01'
                  ORDER BY mfg_date,check_history.code,check_date ASC, remarks DESC";
        $query = $query_selector . $query_extension;
        $total_rows = DB::select($query);
        $row_num =  $total_rows[0]->count;
        $loop = ceil($row_num/2000);
        //$loop=5;
        //echo $loop;

        /*$query_selector = "SELECT check_history.id,phone_number,check_history.code,remarks,source,date(check_history.created_at) as check_date,
                  medicine_name,mfg_date,expiry_date ";*/
        $query_selector = "SELECT check_history.id,phone_number,check_history.code,remarks,date(check_history.created_at) as check_date,
                  mfg_date,expiry_date ";
        $query = $query_selector . $query_extension;
        $reset_query = $query;
        $limit_start = 1;
        $limit_var = 2000;
        //$limit_var = 20;

        $array_checker = [0,0,0,0,1000,1000,1000,1000];
        for($i=0 ; $i<$loop; $i++){
            //for($i=0 ; $i<1; $i++){
            $query = $query . " Limit ".$limit_start.",".$limit_var;
            $data['check_data'] = DB::select($query);
            $data['check_data'] = $this->groupCheckData($data,$array_checker);
            $limit_start += 2000;
            //echo $query. "<br>";
            $query = $reset_query;
            //}
        }
    }

    /**
     * Process and parameter calculation
     * @param $data
     * @param $array_checker
     */
    protected function groupCheckData($data,&$array_checker){
        $temp = array();
        $codeString = '';
        $chk = 1;

        foreach($data['check_data'] as $check){
            $temp[$check->code]['percentage'] = '';
            $temp[$check->code]['phone_number'][] = $check->phone_number;
            $temp[$check->code]['mfg_date'] = $check->mfg_date;
            $temp[$check->code]['expiry_date'] = $check->expiry_date;
            //$temp[$check->code]['source'][] = $check->source;
            //$temp[$check->code]['medicine_name'] = $check->medicine_name;
            $temp[$check->code]['arraylength'] = count($temp[$check->code]['phone_number']);
            $temp[$check->code]['check_date'][] = $check->check_date;
            $temp[$check->code]['datediff_mfg'] = (int)date_diff(date_create($check->mfg_date),date_create($temp[$check->code]['check_date'][0]))->format("%a");
            $temp[$check->code]['unique_num'] = array_unique($temp[$check->code]['phone_number']);
            $temp[$check->code]['unique_number_times'] = array_count_values($temp[$check->code]['phone_number']);

            //1st param
            //When verification is done  after 30 days of MFG date
            if($temp[$check->code]['datediff_mfg'] <= 30 ){
                $initial_diff = $temp[$check->code]['datediff_mfg']/10; //fix value
                $temp[$check->code]['percentage'] += log($initial_diff)*$initial_diff; //fix calculation
            }elseif($temp[$check->code]['datediff_mfg'] >= 120){
                //When verification is done with more than 4months days after MFG date
                if($check->check_date >= '2016-05-24' && $check->check_date <= '2016-06-30'){
                    //When verification is done after May 24th, due to advertising, exceptional case
                    $var = date_diff(date_create($check->check_date),date_create('2016-05-24'))->format("%a");
                    if($var==0) $var=1.1; //fix value
                    $temp[$check->code]['percentage'] += (log($var/3)*($var/3)); //fix calculation
                }else{
                    //other than the exceptional
                    $middle_diff = ($temp[$check->code]['datediff_mfg'])/10;
                    $temp[$check->code]['percentage'] += log($middle_diff)*$middle_diff;
                }
            }else{
                //When verification within 1 to 4 months
                $diff_in_between = log($temp[$check->code]['datediff_mfg'])/10;
                $temp[$check->code]['percentage'] += log($diff_in_between)*$diff_in_between;
            }

            //2nd param
            //datediffs
            if($codeString!=$check->code) {
                $chk=0;
                $temp[$check->code]['datediff'][$chk] = 0;
            }
            else {
                $temp[$check->code]['datediff'][$chk] = (int)date_diff(date_create($temp[$check->code]['check_date'][0]),date_create($temp[$check->code]['check_date'][($chk+1) - 1]))->format("%a");
            }

            if($chk!=0) {
                $sum = array_sum($temp[$check->code]['datediff']);
                //$sum = $temp[$check->code]['datediff'];
                //print_r($sum);
                if ($sum < 14) $temp[$check->code]['percentage'] += $this->random_float(0.1,3.0);
                else $temp[$check->code]['percentage'] += $this->random_float(($sum/5), $sum);
            }else{
                $temp[$check->code]['percentage'] += $this->random_float(0.1,3.0);
            }

            //3rd param
            // # of verify, unique,total etc.
            $checksum = (count($temp[$check->code]['unique_num'])/$temp[$check->code]['arraylength']) + count($temp[$check->code]['unique_num'])/10;
            $total = $temp[$check->code]['arraylength'];
            $unique = count($temp[$check->code]['unique_num']);
            if($checksum<1) $temp[$check->code]['percentage'] += log($unique)*$unique;
            elseif($checksum>1.1) $temp[$check->code]['percentage'] += log($total/$unique)*($total/$unique);
            else{
                $temp[$check->code]['percentage'] += $this->random_float(0.1,3.0);
            }

            //4th param - important for different numbers record
            //first for each code, fetch its verified numbers, and all the entry of check history list for that number
            //for each numbers on check history find date difference between each of the verification
            // if any date difference is more than 14 days, mark that by +1 , and continue
            $i=0;
            $fourth_param_val=0;
            foreach($temp[$check->code]['phone_number'] as $number){
                $date_history['dates'] = DB::select("SELECT code,DATE(created_at) as date_only from check_history where phone_number=".$number.
                    " and remarks='verified first time'");
                //echo "<br>";
                $date_difference=array();
                foreach($date_history['dates'] as $dates){
                    array_push($date_difference,$dates->date_only);
                }
                $diffs=0;
                if(count($date_difference)>1) {
                    for ($len = 1; $len < count($date_difference); $len++) {
                        $temp_var = $date_difference[$len] - $date_difference[$len - 1];
                        if ($temp_var < 14) $diffs++;
                    }
                }
                $fourth_param_val += $diffs;
                $i++;
            }
            $temp[$check->code]['percentage'] += $fourth_param_val/20;

            if($temp[$check->code]['percentage'] < 0.00) $temp[$check->code]['percentage'] = $this->random_float(0.00,3.0);
            if($temp[$check->code]['percentage'] > 100.00) $temp[$check->code]['percentage'] = $this->random_float(98.00,100.0);


            /** ANN DATA **/
            $temp[$check->code]['verify_datediff_ann']  = ($temp[$check->code]['datediff_mfg']/365);
            $third_var_compute = count($temp[$check->code]['unique_num'])/$temp[$check->code]['arraylength'] + count($temp[$check->code]['unique_num'])/5;
            if($third_var_compute < 1) $temp[$check->code]['verify_ann'] = $this->random_float(0.01, 1.00);
            else $temp[$check->code]['verify_ann'] = $this->random_float(1.00, $third_var_compute);

            $fourth_var_compute = $this->random_float(($fourth_param_val/10)-5,($fourth_param_val/10)+5);
            $fourth_var_compute = $fourth_var_compute >=0 ? $fourth_var_compute : -$fourth_var_compute;
            $temp[$check->code]['user_action'] = $fourth_var_compute;
            /** END ANN DATA**/

            $codeString = $check->code;
            $chk++;
        }



        foreach($temp as $code=>$item){

            $sum = array_sum($item['datediff'])/14;
            if($sum == 0){
                $sum_value = $this->random_float(0.1, 3.0);
            }else $sum_value = $this->random_float(($sum/5), $sum);

            if($array_checker[0] < $item['verify_datediff_ann'])
                $array_checker[0] = $item['verify_datediff_ann'];
            if($array_checker[1] < $sum_value)
                $array_checker[1] = $sum_value;
            if($array_checker[2] < $item['verify_ann'])
                $array_checker[2] = $item['verify_ann'];
            if($array_checker[3] < $item['user_action'])
                $array_checker[3] = $item['user_action'];

            if($array_checker[4] > $item['verify_datediff_ann'])
                $array_checker[4] = $item['verify_datediff_ann'];
            if($array_checker[5] > $sum_value)
                $array_checker[5] = $sum_value;
            if($array_checker[6] > $item['verify_ann'])
                $array_checker[6] = $item['verify_ann'];
            if($array_checker[7] > $item['user_action'])
                $array_checker[7] = $item['user_action'];

            //print_r($sum_value." / ". $array_checker[1]." - ". $array_checker[5]." <br>");
        }

        foreach ($temp as $code=>$item){
            $sum = array_sum($item['datediff'])/14;
            if($sum <= 0){
                $sum_value = $this->random_float(0.1, 3.0);
            }else $sum_value = $this->random_float(($sum/5), $sum);

            $output_for_ANN[] = array(
                'code' => $code,
                'ann_datediff_from_mfg_to_verify' => (($item['verify_datediff_ann'] - $array_checker[4]) / ($array_checker[0] - $array_checker[4]))*10,
                'ann_checkdate_sum' => (($sum_value - $array_checker[5]) / ($array_checker[1] - $array_checker[5]))*10,
                'ann_total_verify' => (($item['verify_ann'] - $array_checker[6]) / ($array_checker[2] - $array_checker[6]))*10,
                'ann_user_action' => (($item['user_action'] - $array_checker[7]) / ($array_checker[3] - $array_checker[7]))*10,
                'ann_expected_output' => $item['percentage']/100
            );

            //print_r($sum_value." / ". $array_checker[1]." - ". $array_checker[5]." |" . (($sum_value - $array_checker[5]) / ($array_checker[1] - $array_checker[5]))*10 . " <br>");
        }

        //$first_names = array_column($output_for_ANN, 'ann_checkdate_sum');
        //print_r($first_names);

        $this->NNimplementation($output_for_ANN);

    }

    /**
     * A method to second to time
     * @param $s
     * @return string
     */
    public function secondsToTime($s)
    {
        $h = floor($s / 3600);
        $s -= $h * 3600;
        $m = floor($s / 60);
        $s -= $m * 60;
        return $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s);
    }

    /**
     * Neural Network implementation
     * @param $ann_array
     */
    public function NNimplementation($ann_array){
        $n = new NeuralNetworkController(4, 1, 1);
        $n->setVerbose(false);

        //$n->load('codes/neuraltest');
        // Add test-data to the network. In this case,
        // we want the network to learn the 'XOR'-function
        // $n->addTestData(array (.130, 0, .1, .1), array (1.0000));
        $i = 0;

        foreach($ann_array as $ann ){
            if(!$code_exists = Prob_model::where('code',$ann_array[$i]['code'])->first()) {
                $data = [
                    'code' => $ann_array[$i]['code'],
                    'expected' => $ann['ann_expected_output']*100,
                    'first' => $ann['ann_datediff_from_mfg_to_verify'],
                    'second'=> $ann['ann_checkdate_sum'],
                    'third'=> $ann['ann_total_verify'],
                    'fourth' => $ann['ann_user_action']
                ];
                Prob_model::create($data);

            }
            $n->addTestData(array ($ann['ann_datediff_from_mfg_to_verify']/10, $ann['ann_checkdate_sum']/10,
                $ann['ann_total_verify']/10, $ann['ann_user_action']/10), array ($ann['ann_expected_output']));
            $i++;
        }

        // we try training the network for at most $max times
        $max = 3;
        $i = 0;
        // train the network in max 1000 epochs, with a max squared error of 0.01
        while (!($success = $n->train(1000, 0.01)) && ++$i<$max) {
        }

        if ($success) {
            $epochs = $n->getEpoch();
        }

        for ($i = 0; $i < count($n->trainInputs); $i ++) {
            $output = $n->calculate($n->trainInputs[$i]);

            $actual = number_format((implode(", ", $output))*100.00,2);
            if($actual<0) $value_actual = -$actual;
            else $value_actual = $actual;

            //new way
            $data = [
                'expected' => number_format((implode(", ", $n->trainOutput[$i])) * 100.00, 2),
                'actual' => $value_actual
            ];
            Prob_model::where('code',$ann_array[$i]['code'])
                ->update($data);

            //echo "Result: " . $value_actual . "<br>";
        }

    }

    /**
     * AiModel show listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function aimodel(){
        $data['prob_highRisked'] = Prob_model::where('actual','>','79.99')
            ->orderby('actual','desc')
            ->limit(10)
            ->get();

        $data['prob_filtered'] = DB::select("(Select * from prob_model order by first desc limit 3)  UNION ( select * from prob_model order by second desc limit 3) UNION ( select * from prob_model order by third desc limit 3) UNION ( select * from prob_model order by fourth desc limit 3)");

        $data['prob_nonfiltered'] = Prob_model::where('steps','!=',0)
            ->limit(10)
            ->get();
        $data['type'] = 1;

        return view('panalytics.probabilistic',$data);
    }

    /**
     * Show all data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function alldata(){
        $data['prob_all'] = Prob_model::where('actual','>','79.99')
            ->orderby('actual','desc')
            ->get();
        $data['type'] = 0;

        return view('panalytics.probabilistic',$data);
    }

    /**
     * Randomize float value
     * @param $min
     * @param $max
     * @return mixed
     */
    function random_float ($min,$max) {
        return ($min+lcg_value()*(abs($max-$min)));
    }

    /**
     * Submit a code report
     * @param Request $request
     */
    public function submitAction(Request $request){

        $code = explode(" ",$request->code);
        $code_id = Prob_model::select('id')->where('code',$code[2])->first();

        if ($request->action == 'call_verifier') $progress = 1;
        elseif ($request->action == 'further_investigate') $progress = 2;
        else $progress = 3;
        $data = [
            'progress_number' => $progress,
            'update_details' => $request->description,
            'prob_model_id' => $code_id['id']
        ];

        if(!$code_exists = Prob_model_action::where('prob_model_id',$code_id['id'])->first()) {
            Prob_model_action::create($data);
            $data = [
                'steps' => 1
            ];
            Prob_model::where('id',$code_id['id'])->update($data);
        }else{
            Prob_model_action::where('prob_model_id',$code_id['id'])->update($data);
        }
    }

    /**
     * Get code details of a particular code
     * @param Request $request
     */
    public function getCode(Request $request){
        $code = explode(" ",$request->code);
        $query = "SELECT check_history.id, phone_number, remarks, source, check_history.created_at as check_date, medicine_name, medicine_type, medicine_dosage, mfg_date,
                  expiry_date, batch_number, print_order.created_at as generation_date from check_history, print_order, code, medicine where check_history.code = code.code
                  AND code.status = print_order.id AND print_order.medicine_id = medicine.id AND check_history.code = '" . $code[2] . "' order by check_history.created_at";
        $data['code_data'] = DB::select($query);
        echo json_encode($data) ;
        //return $data;
    }

    /**
     * This is on preliminary stage. Purpose is to make a finding pattern of a specific code and predict other same for similary category
     */
    public function findModelPattern(){
        $val = 1;
        $query = "SELECT prob_model_id, progress_number, update_details, code, expected, actual,
         first, second, third, fourth, steps from prob_model, prob_model_action where prob_model.id = prob_model_action.prob_model_id
         and prob_model_action.id = $val";
        $data['similar_data'] = DB::select($query);
        //print_r($data);
        echo json_encode($data);
    }

}
