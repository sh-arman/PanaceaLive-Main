<?php

namespace Panacea\Console\Commands;

use Illuminate\Console\Command;
use Panacea\Code;
Use DB;

class DeleteCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panacea:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes codes from the code table where status=0 and char_length(code)=6';

    /**
     * Create a new command instance.
     *
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
        $quantity = 10000;
        try{
            # The command below was taking too long to execute, about 35 mins after running for about a month
            # I guess there are no more codes of 6 length that have status 0
            // Code::where('status', 0)
            //     ->where(DB::raw('CHAR_LENGTH(code)'), '=', 6)
            //     ->take($quantity)
            //     ->delete();

            # This command was issued after the previous deletion command was taking too long. This command
            # has status less than 196 because till 196 the codes generated had 6 character lenghts. So these
            # deletd codes are 6 lenght codes that have been generated and printer but are no longer in use.
            Code::where('status', '<' , 196)
                ->where('status', '!=' , 0)
                ->take($quantity)
                ->delete();

            $this->info("Codes deletion successful!");
            \Log::info("Codes deleted!");
        }
        catch (\Illuminate\Database\QueryException $e) {
            
        }
    }
}
